# Online Classes & Recorded Lessons — Setup Guide

This guide covers the complete setup for the **Online Classes + Recorded Lessons + Video Protection** system in Taalimu.

---

## 1. Prerequisites

- Taalimu platform running (Laravel 12 + Modules)
- PHP 8.2+, MySQL 8+, Redis
- Supervisor for queue workers
- Cron for scheduler (`schedule:run` every minute)

---

## 2. Zoom Integration Setup

### 2.1 Server-to-Server OAuth App (Meeting Management)

1. Go to [Zoom App Marketplace](https://marketplace.zoom.us/) → **Develop** → **Build App**
2. Choose **Server-to-Server OAuth**
3. App name: `Taalimu Online Classes`
4. **Scopes** (under *Feature* → *Meeting*):
   - `meeting:write:admin`
   - `meeting:read:admin`
   - `recording:read:admin`
   - `webhook:read:admin`
5. Activate the app
6. Copy **Account ID**, **Client ID**, **Client Secret**

### 2.2 Meeting SDK (JWT App) — Embedded Client

1. In Zoom Marketplace → **Build App** → **Meeting SDK** → **JWT**
2. App name: `Taalimu Meeting SDK`
3. Copy **SDK Key** and **SDK Secret**

> **Note**: Meeting SDK is free for basic usage. This gives the "embedded in Taalimu" experience without Video SDK per-minute costs.

### 2.3 Webhook Endpoint

1. In the Server-to-Server OAuth app → **Feature** → **Event Subscriptions**
2. Add endpoint: `https://YOUR_DOMAIN/webhooks/zoom`
3. Subscribe to events:
   - `meeting.started`
   - `meeting.ended`
   - `recording.completed`
4. Copy **Webhook Secret Token**

---

## 3. Storage Setup (Cloudflare R2 Recommended)

### 3.1 Create R2 Bucket

1. Cloudflare Dashboard → **R2** → **Create Bucket**
2. Name: `taalimu-recordings` (or your choice)
3. **CORS Policy** (required for video streaming):
```json
[
  {
    "AllowedOrigins": ["https://YOUR_DOMAIN", "https://*.YOUR_DOMAIN"],
    "AllowedMethods": ["GET", "HEAD"],
    "AllowedHeaders": ["Range", "Authorization", "Content-Type"],
    "ExposeHeaders": ["Content-Length", "Content-Range", "Accept-Ranges"],
    "MaxAgeSeconds": 3600
  }
]
```
4. Create **API Token** with `Object Read & Write` permissions for the bucket
5. Copy **Account ID**, **Access Key ID**, **Secret Access Key**, **Bucket Name**

### 3.2 Alternative: AWS S3

Standard S3 bucket with same CORS policy. Note: S3 has egress fees; R2 has zero egress.

---

## 4. Environment Variables

Add to your `.env` (production: `.env.production`):

```env
# ========================================
# ZOOM INTEGRATION
# ========================================
ZOOM_ACCOUNT_ID=your_account_id
ZOOM_CLIENT_ID=your_client_id
ZOOM_CLIENT_SECRET=your_client_secret
ZOOM_SDK_KEY=your_sdk_key
ZOOM_SDK_SECRET=your_sdk_secret
ZOOM_WEBHOOK_SECRET_TOKEN=your_webhook_secret

# ========================================
# RECORDINGS STORAGE (R2 or S3)
# ========================================
# Cloudflare R2 (recommended — zero egress fees)
R2_ACCESS_KEY_ID=your_r2_access_key
R2_SECRET_ACCESS_KEY=your_r2_secret
R2_BUCKET=taalimu-recordings
R2_ENDPOINT=https://<ACCOUNT_ID>.r2.cloudflarestorage.com
R2_REGION=auto
R2_USE_PATH_STYLE_ENDPOINT=true
R2_URL=https://pub-<bucket>.r2.dev  # optional public domain

# OR AWS S3
# AWS_ACCESS_KEY_ID=...
# AWS_SECRET_ACCESS_KEY=...
# AWS_DEFAULT_REGION=us-east-1
# AWS_BUCKET=...
# AWS_ENDPOINT=...

# Recordings driver: auto (R2→S3→local) | r2 | s3 | local
RECORDINGS_STORAGE_DRIVER=auto
RECORDINGS_URL_TTL=300  # presigned URL TTL in seconds

# ========================================
# OPTIONAL: Concurrent playback per tenant
# ========================================
# Set per tenant in settings JSON:
# "online_classes": { "concurrent_mode": "block" }  # block | warn | end
```

---

## 5. Run Migrations

```bash
php artisan migrate --force
```

Creates/updates:
- `online_classes` (extends with uuid, access_mode, recording_status, zoom fields)
- `online_class_participants`
- `class_recordings`
- `video_access_logs`
- `video_progress`

---

## 6. Queue Workers & Scheduler

### Supervisor Config (Production)

Ensure your supervisor runs the queue worker with all named queues:

```ini
[program:taalimu-worker]
command=php /path/to/taalimu/artisan queue:work redis --queue=high,whatsapp,notifications,gamification,default --sleep=3 --tries=3 --max-time=3600
numprocs=2
autostart=true
autorestart=true
user=taalimu
stdout_logfile=/path/to/taalimu/storage/logs/worker.log
```

### Cron for Scheduler (Required)

```bash
* * * * * cd /path/to/taalimu && php artisan schedule:run >> /dev/null 2>&1
```

> **Critical**: The `online-classes:send-reminders` command runs every minute via scheduler to send T-15min notifications. Without cron, reminders won't fire.

---

## 7. Verify Installation

### 7.1 Check Routes

```bash
php artisan route:list --name=online
```

Should show:
- `instructor.online_classes.*` (CRUD + start/end/join-token)
- `instructor.recordings.*` (list + analytics)
- `campus.classes.index` (student dashboard)
- `campus.recordings.watch` (secure player)
- `campus.recordings.token/progress` (AJAX)
- `webhooks.zoom` (POST)
- `video.stream` (GET)

### 7.2 Test Flow

1. **Instructor** → Online Classes → Add New → Platform: Zoom → Fill details → Save
2. Edit class → Verify auto-recording toggle + access mode selector
3. At class time → Instructor clicks **Start Class** → Joins via embedded SDK
4. Students → Campus → My Classes → **Live Now** card → Join
5. Instructor clicks **End Class** → Recording processes → Students get notification
6. Students → Recorded Lessons → Click → Secure player with watermark + resume

---

## 8. Troubleshooting

| Issue | Cause | Fix |
|---|---|---|
| "Zoom not configured" | Missing env vars | Check all 6 Zoom env vars |
| Webhook 400/401 | Signature mismatch | Verify `ZOOM_WEBHOOK_SECRET_TOKEN` matches Zoom app |
| Recording stuck "processing" | Download failed | Check R2 credentials + CORS + network |
| Player shows "token invalid" | Token expired/refetch failed | Check `RECORDINGS_URL_TTL`, queue worker running |
| CSP blocks Zoom SDK | Production CSP too strict | Verify `ContentSecurityPolicy` middleware has `source.zoom.us` + `wss://*.zoom.us` |
| Reminders not sent | Scheduler not running | Add cron for `schedule:run` |

---

## 9. Security Checklist (Post-Deploy)

- [ ] All Zoom secrets in `.env` only (never in frontend)
- [ ] `ZOOM_WEBHOOK_SECRET_TOKEN` matches Zoom app exactly
- [ ] R2 bucket CORS allows your domain + Range headers
- [ ] HTTPS enforced (Zoom SDK requires secure context)
- [ ] `RECORDINGS_URL_TTL` ≤ 300s (short-lived playback URLs)
- [ ] Concurrent mode configured per tenant needs
- [ ] Queue worker processes `default` queue (recordings job)
- [ ] Scheduler cron active (reminders)

---

## 10. Architecture Overview

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│  Instructor │────▶│  Laravel    │────▶│   Zoom API  │
│  Creates    │     │  Service    │     │  (S2S OAuth)│
│  Class      │     │  Provisions │     │  Meeting    │
└─────────────┘     │  Session    │     └──────┬──────┘
                    └──────┬──────┘            │
                           │                   │
                    ┌──────▼──────┐     ┌──────▼──────┐
                    │  Student    │     │  Webhook    │
                    │  Joins via  │     │  Receiver   │
                    │  SDK (role=0)│     │  (HMAC)     │
                    └──────┬──────┘     └──────┬──────┘
                           │                   │
                    ┌──────▼──────┐     ┌──────▼──────┐
                    │  Attendance │     │  Recording  │
                    │  Heartbeats │     │  Job → R2   │
                    └──────┬──────┘     └──────┬──────┘
                           │                   │
                    ┌──────▼───────────────────▼──────┐
                    │   Secure Playback Pipeline      │
                    │  Token (5min, user-bound)       │
                    │  → /video/stream/{token}        │
                    │  → R2 presigned / Local stream  │
                    │  + Dynamic Watermark Overlay    │
                    └─────────────────────────────────┘
```

---

## 11. Key Files Reference

| Layer | Files |
|---|---|
| **Models** | `app/Models/{OnlineClass,OnlineClassParticipant,ClassRecording,VideoAccessLog,VideoProgress}.php` |
| **Interfaces** | `app/Interfaces/{VideoProviderInterface,VideoStorageInterface}.php` |
| **Services** | `app/Services/{ZoomService,OnlineClassService,PlaybackTokenService,VideoStorage/*}.php` |
| **Policies** | `app/Policies/{OnlineClassPolicy,ClassRecordingPolicy}.php` |
| **Jobs** | `app/Jobs/{ProcessClassRecordingJob,NotifyClassStartedJob,NotifyRecordingAvailableJob}.php` |
| **Controllers** | `app/Http/Controllers/{ZoomWebhookController,VideoStreamController}.php`<br>`Modules/Instructor/.../{OnlineClass,OnlineClassSession,ClassRecording}Controller.php`<br>`Modules/Campus/.../StudentClassController.php` |
| **Views** | `Modules/Instructor/.../online_classes/{index,create,edit,show}.blade.php`<br>`Modules/Instructor/.../recordings/{index,show}.blade.php`<br>`Modules/Campus/.../{classes,watch,live-classroom}.blade.php` |
| **Config** | `config/{filesystems,services}.php`, `bootstrap/app.php` (CSRF), `routes/{web,console}.php` |
| **Translations** | `Modules/{Instructor,Campus,Center}/lang/{ar,en,fr}/{online_classes,classes,sidebar}.php` |

---

## 12. Support

For issues:
1. Check `storage/logs/laravel.log` and `storage/logs/security.log`
2. Verify queue worker is processing `default` queue
3. Check Zoom webhook delivery logs in Zoom App Marketplace
4. Ensure R2 bucket CORS and credentials are correct

---

*Generated for Taalimu Online Classes System v1.0*