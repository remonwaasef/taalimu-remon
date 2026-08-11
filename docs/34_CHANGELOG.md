# 34_CHANGELOG - Application Release History

All notable changes to the Taalimu.com platform will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Added
- **Enterprise Database Scaling Evaluation**: Live schema audit (51 tenant tables, ~56 FK tables, MariaDB 10.4 local / MariaDB prod). Verdict: partitioning by `tenant_id` rejected (unique-key rule, FK ban on partitioned tables pre-MariaDB 10.6, rebuild cost, partition-count ceiling). Recorded as ADR-004 with a phased plan in `docs/37_DATABASE_SCALING.md`.
- **Database monitoring**: New `db:monitor-sizes` command reports table sizes to Telegram weekly (Sunday 07:00) with growth thresholds (1M rows / 512 MB) to drive scaling decisions.
- **Full-Text Search (Laravel Scout + Meilisearch)**: `SearchService` performs tenant-isolated, ranked searches over `Student` and `Course` (both now `Searchable` with `tenant_id` in their indexed payloads). Wired into the async student picker (`center.students.search`) and the grid queries (`StudentQuery`/`CourseQuery`). Falls back to safe LIKE matching when the engine is unreachable; local dev uses the `database` Scout driver.
- **Redis Caching & Session Integration**: Cache, Session, and Queue drivers switched to Redis with dedicated databases (queue=0, cache=1, session=2) via `REDIS_DB`, `REDIS_CACHE_DB`, `REDIS_SESSION_DB`.
- **Graceful Redis Fallback**: `AppServiceProvider::configureResilientCaching()` pings Redis at boot and automatically falls back to database drivers when unreachable, logging a warning once per process.
- **`predis/predis` (^3.5)**: Installed as a pure-PHP Redis client for environments without the phpredis extension (local dev); production keeps `REDIS_CLIENT=phpredis`.
- **Security Hardening**: Overhauled packages to patched versions — `dompdf/dompdf` (3.1.6) and `guzzlehttp/guzzle` (7.15.x) — resolving CVE-2026-59941/2/3, CVE-2026-56722, and guzzle advisories; `composer audit` is now clean.
- Created [SESSION_START.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/SESSION_START.md) and [MASTER_CONTEXT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/MASTER_CONTEXT.md) aliases pointing to [00_AI_BOOT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/00_AI_BOOT.md) and [01_MASTER_CONTEXT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/01_MASTER_CONTEXT.md).
- Integrated Task Classification Matrix (UI, Feature, DB, Controller, Route, Auth, Permissions, API, Performance, Bug Fix) into [00_AI_BOOT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/00_AI_BOOT.md) and [README_AI.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/README_AI.md).
- Consolidated Clean Documentation System ([DOCUMENTATION_CLEANUP_REPORT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/DOCUMENTATION_CLEANUP_REPORT.md) & [DOCUMENTATION_INDEX.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/DOCUMENTATION_INDEX.md)).
- Complete AI-First Numbered Knowledge Operating System inside `docs/` (`00_AI_BOOT.md` through `35_KNOWN_LIMITATIONS.md`).
- Project root entry point manifests ([PROJECT_MANIFEST.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/PROJECT_MANIFEST.md) & [README_AI.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/README_AI.md)).
- Quality assurance and release guides ([29_CODE_REVIEW.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/29_CODE_REVIEW.md), [30_TESTING_GUIDE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/30_TESTING_GUIDE.md), [31_DEPLOYMENT_GUIDE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/31_DEPLOYMENT_GUIDE.md), [32_RELEASE_CHECKLIST.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/32_RELEASE_CHECKLIST.md)).

---

## [1.0.0] - 2026-07-28

### Added
- Initial production release of Taalimu.com SaaS Educational Platform.
- Multi-tenancy architecture with single database `tenant_id` partitioning.
- 6 Modular monolith domains: `Admin`, `Center`, `Instructor`, `Campus`, `Tenancy`, `Api`.
- Payment gateway integrations for PayPal, Paymob, and local cash billing.
- WhatsApp parent alert system and Telegram automated reporting bot.
- Realtime WebSockets server support using Laravel Reverb.
- Automated system exception triage system (`OperationIssue`).
