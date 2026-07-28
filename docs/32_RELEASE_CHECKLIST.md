# 32_RELEASE_CHECKLIST - Production Release Protocol

> **Purpose**: Pre-release, deployment, and post-deployment checklist to ensure zero downtime and zero regressions during production releases.

---

## 1. Pre-Release Verification Checklist
- [ ] All automated PHPUnit tests pass (`php artisan test`).
- [ ] All Playwright E2E browser tests pass (`npx playwright test`).
- [ ] Code formatted with Laravel Pint (`vendor/bin/pint --test`).
- [ ] Database migrations tested in staging environment (`php artisan migrate --dry-run`).
- [ ] Environmental variables verified in `.env.production`.
- [ ] [docs/34_CHANGELOG.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/34_CHANGELOG.md) updated with release version tag.

---

## 2. Deployment Phase Checklist
- [ ] Put application into maintenance mode if breaking migrations exist (`php artisan down --secret="deploy-secret"`).
- [ ] Execute production deployment script (`./deploy.sh`).
- [ ] Run pending migrations (`php artisan migrate --force`).
- [ ] Rebuild production asset bundle (`npm run build`).
- [ ] Bring application out of maintenance mode (`php artisan up`).

---

## 3. Post-Deployment Verification Checklist
- [ ] Verify central landing page (`https://taalimu.com`) loads without SSL or HTTP errors.
- [ ] Verify tenant subdomain resolution (`https://demo.taalimu.com`).
- [ ] Confirm queue workers are actively processing jobs (`supervisorctl status`).
- [ ] Check Sentry error log dashboard for any new exception spikes.
- [ ] Verify payment webhook endpoints are receiving requests.
