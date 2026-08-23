# Environment Variables Reference — Online Classes System

All variables below are **required** unless marked `(optional)`.

---

## Zoom Integration

| Variable | Description | Example |
|---|---|---|
| `ZOOM_ACCOUNT_ID` | Server-to-Server OAuth Account ID | `abc123XYZ` |
| `ZOOM_CLIENT_ID` | Server-to-Server OAuth Client ID | `xYz789...` |
| `ZOOM_CLIENT_SECRET` | Server-to-Server OAuth Client Secret | `sEcReT...` |
| `ZOOM_SDK_KEY` | Meeting SDK (JWT App) Key | `sDkKeY...` |
| `ZOOM_SDK_SECRET` | Meeting SDK (JWT App) Secret | `sDkSeCrEt...` |
| `ZOOM_WEBHOOK_SECRET_TOKEN` | Webhook endpoint validation secret | `whSecTok...` |

> **Source**: Zoom App Marketplace → Your Apps → Credentials / Feature tabs.

---

## Recordings Storage

### Cloudflare R2 (Recommended)

| Variable | Description | Example |
|---|---|---|
| `R2_ACCESS_KEY_ID` | R2 API Token Access Key | `r2AccKey...` |
| `R2_SECRET_ACCESS_KEY` | R2 API Token Secret | `r2SecKey...` |
| `R2_BUCKET` | Bucket name | `taalimu-recordings` |
| `R2_ENDPOINT` | R2 S3-compatible endpoint | `https://<ACCOUNT_ID>.r2.cloudflarestorage.com` |
| `R2_REGION` | Region (use `auto`) | `auto` |
| `R2_USE_PATH_STYLE_ENDPOINT` | Must be `true` for R2 | `true` |
| `R2_URL` | (Optional) Public bucket URL for direct access | `https://pub-xxx.r2.dev` |

### AWS S3 (Alternative)

| Variable | Description | Example |
|---|---|---|
| `AWS_ACCESS_KEY_ID` | IAM Access Key | `AKIA...` |
| `AWS_SECRET_ACCESS_KEY` | IAM Secret Key | `secKey...` |
| `AWS_DEFAULT_REGION` | Region | `us-east-1` |
| `AWS_BUCKET` | Bucket name | `taalimu-recordings` |
| `AWS_ENDPOINT` | Custom endpoint (if not standard) | `https://s3.us-east-1.amazonaws.com` |

---

## Recordings Behavior

| Variable | Default | Description |
|---|---|---|
| `RECORDINGS_STORAGE_DRIVER` | `auto` | `auto` \| `r2` \| `s3` \| `local` — `auto` picks first configured cloud |
| `RECORDINGS_URL_TTL` | `300` | Presigned playback URL TTL in seconds (5 min recommended) |

---

## Tenant Settings (Per-Center, JSON in `tenants.settings`)

Configure via UI or seeder:

```json
{
  "online_classes": {
    "concurrent_mode": "block"
  }
}
```

| Key | Values | Default | Description |
|---|---|---|---|
| `concurrent_mode` | `block` \| `warn` \| `end` | `block` | Device sharing policy: block 2nd device, warn only, or end 1st session |

---

## Example .env Block

```env
# Zoom
ZOOM_ACCOUNT_ID=abc123XYZ
ZOOM_CLIENT_ID=xYz789
ZOOM_CLIENT_SECRET=sEcReT123
ZOOM_SDK_KEY=sDkKeY456
ZOOM_SDK_SECRET=sDkSeCrEt789
ZOOM_WEBHOOK_SECRET_TOKEN=whSecTok999

# R2 Storage
R2_ACCESS_KEY_ID=r2AccKey111
R2_SECRET_ACCESS_KEY=r2SecKey222
R2_BUCKET=taalimu-recordings
R2_ENDPOINT=https://abcd1234.r2.cloudflarestorage.com
R2_REGION=auto
R2_USE_PATH_STYLE_ENDPOINT=true
R2_URL=https://pub-taalimu.r2.dev

# Recordings
RECORDINGS_STORAGE_DRIVER=auto
RECORDINGS_URL_TTL=300
```

---

## Validation Checklist

Before deploy, ensure:

- [ ] All 6 Zoom vars set
- [ ] R2 (or S3) credentials valid + bucket exists
- [ ] R2 CORS allows your domain + `Range` header
- [ ] `ZOOM_WEBHOOK_SECRET_TOKEN` matches Zoom app exactly
- [ ] HTTPS on production (Zoom SDK requires secure context)
- [ ] Queue worker runs `default` queue
- [ ] Scheduler cron active (`schedule:run` every minute)

---

*Variables are read via `config('services.zoom.*')`, `config('services.r2.*')`, `config('services.recordings.*')`.*