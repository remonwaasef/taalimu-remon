# Online Classes System — Architecture Document

## Overview

This document describes the architecture of the **Online Classes + Recorded Lessons + Video Protection** system integrated into the Taalimu platform.

---

## Design Principles

1. **Tenant-First**: Every model uses `BelongsToTenant` trait → automatic global scope isolation.
2. **Provider Abstraction**: `VideoProviderInterface` + `VideoStorageInterface` allow swapping Zoom/R2 for Daily/Agora/Bunny without business logic changes.
3. **Server-Side Secrets**: All Zoom credentials, SDK signatures, storage keys stay in Laravel; frontend receives only short-lived opaque tokens.
4. **Defense in Depth**: Policies + signed tokens + watermark + concurrent guard + hashed access logs.
5. **MVP Scope**: Zoom Meeting SDK only, Cloudflare R2 storage, Blade UI. No DRM, no transcoding, no K8s.

---

## Data Model

```
┌─────────────────┐       ┌──────────────────────┐
│  online_classes │       │ online_class_particip│
│  (extended)     │◀─────▶│  (attendance)        │
└────────┬────────┘       └──────────┬───────────┘
         │                            │
         ▼                            ▼
┌─────────────────┐       ┌──────────────────────┐
│ class_recordings│       │   video_progress     │
│  (storage)      │       │  (resume playback)   │
└────────┬────────┘       └──────────┬───────────┘
         │                            │
         ▼                            ▼
┌─────────────────────────────────────────────────┐
│           video_access_logs                     │
│  (token_issued, playback_denied, session_conflict│
│   ip_hash, user_agent_hash — privacy-first)     │
└─────────────────────────────────────────────────┘
```

### Key Tables

| Table | Purpose | Tenant Isolation |
|---|---|---|
| `online_classes` | Schedule + Zoom session metadata | `BelongsToTenant` (FK `tenant_id`) |
| `online_class_participants` | Allowlist + attendance heartbeats | `BelongsToTenant` |
| `class_recordings` | Archived recording metadata + storage ref | `BelongsToTenant` |
| `video_progress` | Per-user resume position + completion | `BelongsToTenant` |
| `video_access_logs` | Security audit trail (hashed IP/UA) | `BelongsToTenant` |

---

## Service Layer

### Interfaces (Abstraction Boundaries)

| Interface | Implementations | Responsibility |
|---|---|---|
| `VideoProviderInterface` | `ZoomService` | Meeting CRUD, SDK signatures, recording fetch |
| `VideoStorageInterface` | `LocalVideoStorage`, `CloudVideoStorage` (R2/S3) | Store-from-URL, presigned playback URL, delete |

### Core Services

| Service | Dependencies | Key Methods |
|---|---|---|
| `OnlineClassService` | `VideoProviderInterface` | `createClass()`, `updateClass()`, `startClass()`, `endClass()`, `markJoined()`, `heartbeat()`, `syncAllowlist()` |
| `PlaybackTokenService` | Cache | `issue()`, `resolve()`, `touch()`, `enforceConcurrentSessions()` |
| `ZoomService` | HTTP Client, Cache | `createSession()`, `getJoinContext()`, `fetchRecording()`, `accessToken()` |
| `VideoStorageManager` | — | `forRecording()`, `default()` (factory) |

---

## Security Flow

### Playback Token Issuance

```
Student → POST /campus/recordings/{uuid}/token
    │
    ▼
Policy::view($user, $recording)  ← tenant + enrollment check
    │
    ▼
PlaybackTokenService::issue()
    ├─ Concurrent session check (cache key: video_session:{user}:{rec})
    ├─ Generate 64-char opaque token
    ├─ Cache[playback_token:{token}] = {user_id, tenant_id, recording_id} TTL 300s
    └─ Log video_access_logs (token_issued, ip_hash, ua_hash)
    │
    ▼
Return {token, ttl}
```

### Video Streaming

```
<video src="/video/stream/{token}">
    │
    ▼
VideoStreamController::__invoke($token)
    │
    ├─ PlaybackTokenService::resolve($token, $user)
    │   ├─ Cache hit?
    │   ├─ user_id matches?
    │   └─ Recording STATUS_READY?
    │
    ├─ $recording->storage_provider === 'local'
    │   └─ Stream file via Symfony StreamedResponse (Range support)
    │
    └─ Cloud (R2/S3)
        └─ 302 Redirect → Storage::temporaryUrl($key, 300s)
```

### Concurrent Session Guard

| Mode | Behavior |
|---|---|
| `block` (default) | 2nd device gets 429 + log `session_conflict` |
| `warn` | Allows but logs conflict |
| `end` | Overwrites session key (1st device fails next heartbeat) |

---

## Zoom Integration

### Server-to-Server OAuth (Management)

- Token cached 55 min (`zoom:oauth:access_token`)
- Endpoints: `POST /users/me/meetings`, `PATCH /meetings/{id}`, `DELETE /meetings/{id}`, `GET /meetings/{id}/recordings`

### Meeting SDK Signature (Embedded Client)

```php
// ZoomService::getJoinContext()
$header = base64url(json_encode(['alg'=>'HS256','typ'=>'JWT']));
$payload = base64url(json_encode([
    'appKey' => $sdkKey,
    'sdkKey' => $sdkKey,
    'mn' => $meetingNumber,
    'role' => $isHost ? 1 : 0,
    'iat' => $iat,
    'exp' => $exp,
    'tokenExp' => $exp,
]));
$signature = base64url(hash_hmac('sha256', "$header.$payload", $sdkSecret, true));
return "$header.$payload.$signature";  // JWT-style, passed to ZoomMtg.join()
```

- Role `1` = host (instructor), `0` = attendee (student)
- Expires in 2 hours (covers full session window)

### Webhook Receiver

```
POST /webhooks/zoom
    │
    ├─ endpoint.url.validation → return {plainToken}
    ├─ Verify x-zm-signature (HMAC-SHA256 of "v0:{ts}:{body}")
    ├─ Replay guard: |ts - now| ≤ 300s + event-id dedupe cache (3 days)
    └─ Dispatch by event:
        meeting.started     → OnlineClass::startClass() + NotifyClassStartedJob
        meeting.ended       → OnlineClass::endClass() (attendance finalize)
        recording.completed → ProcessClassRecordingJob (idempotent via unique index)
```

---

## Storage Abstraction

| Provider | Class | Playback |
|---|---|---|
| **Local** | `LocalVideoStorage` | Controller streams via `Range` (no URL exposed) |
| **R2** | `CloudVideoStorage('r2')` | `Storage::temporaryUrl()` → presigned GET 300s |
| **S3** | `CloudVideoStorage('s3')` | Same as R2 |

**Factory**: `VideoStorageManager::default()` → auto-detects R2 → S3 → Local.

---

## Notifications & Scheduling

| Job/Command | Trigger | Recipients |
|---|---|---|
| `NotifyClassStartedJob` | `meeting.started` webhook | `OnlineClass::allowedUserIds()` |
| `NotifyRecordingAvailableJob` | `ProcessClassRecordingJob` success | Same |
| `SendOnlineClassRemindersCommand` | Scheduler (every min) | T-15min window, `reminder_sent_at` guard |

All use `OnlineClassNotification` (database + optional mail via `SiteSetting::enable_email_notifications`).

---

## Frontend (Blade)

| Page | Module | Key Features |
|---|---|---|
| `instructor.online_classes.index` | Instructor | Search + filters + recording badge + actions |
| `instructor.online_classes.create/edit` | Instructor | Platform toggle (Zoom/manual), auto_recording, access_mode (course/selected), multi-select students |
| `instructor.online_classes.show` | Instructor | **Embedded Zoom SDK** (host), live participants, start/end AJAX |
| `instructor.recordings.index/show` | Instructor | Analytics cards + per-student progress table |
| `campus.classes.index` | Student | Live Now / Upcoming / Recorded grid with progress bars |
| `campus.watch` | Student | **Secure Player**: token fetch, dynamic watermark (name + masked ID + Taalimu + timestamp, moves every 8s), throttled progress save |
| `campus.live-classroom` | Student | Embedded Zoom SDK (attendee) + watermark |

---

## Testing Strategy

| Category | Tests |
|---|---|
| **Security** | Cross-tenant 403/404, cross-student token denial, expired token, webhook replay/duplicate |
| **Functional** | Teacher creates → Student sees → Recording appears → Watch → Progress resumes |
| **Webhook** | Valid signature accepted, invalid rejected, duplicate idempotent |
| **Performance** | Progress throttle (15s), eager loading, indexes |

Run: `php artisan test --filter="OnlineClass|Recording|Video"`

---

## Deployment Checklist

- [ ] Zoom credentials in `.env` (6 vars)
- [ ] R2 bucket + CORS + API token
- [ ] `webhooks/zoom` in CSRF exempt (`bootstrap/app.php`)
- [ ] Supervisor worker includes `default` queue
- [ ] Cron for `schedule:run` (reminders)
- [ ] CSP allows `source.zoom.us` + `wss://*.zoom.us`
- [ ] HTTPS enforced (Zoom SDK requirement)

---

## Future Extensibility

| Add | How |
|---|---|
| **Daily/Agora Provider** | Implement `VideoProviderInterface` + register in `OnlineClassService` |
| **Bunny Stream Storage** | Implement `VideoStorageInterface` + add case in `VideoStorageManager` |
| **DRM (Widevine/FairPlay)** | Extend `VideoStorageInterface` with `drmPlaybackUrl()`, use `Shaka Player` |
| **Mobile API** | Add routes in `Modules/Api` using same Policies + `PlaybackTokenService` |
| **AI Features** | Transcript search, chapter detection, engagement scoring — consume `video_access_logs` + `video_progress` |

---

*Architecture v1.0 — Taalimu Online Classes System*