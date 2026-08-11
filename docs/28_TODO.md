# 28_TODO - Roadmap & Priority Checklist

### High Priority
- [x] **Redis Caching Driver Integration** (Cache/Session/Queue على Redis مع fallback تلقائي لقاعدة البيانات).
- [x] **Full-Text Search Engine Integration** (Laravel Scout + Meilisearch مع عزل المستأجرين وfallback إلى LIKE).
- [x] **Enterprise Database Scaling Evaluation** (رُفض تقسيم tenant_id لأسباب قاطعة — انظر 37_DATABASE_SCALING.md؛ الخطة: MariaDB 10.6+ + RANGE شهري للجداول بلا FK).

### Medium Priority
- [x] **Role Permission Granularity UI** (Sub-roles for accountants & receptionists — preset templates, tenant-scoped permission matrix, cache invalidation on CRUD).
- [x] **Image Upload WebP Auto-Compression** (`app/Traits/HandlesFileUploads.php` — GD/Imagick, alpha-preserving, animated-GIF guard, keep-smaller-only, configurable via `config/uploads.php`).

### Completed
- [x] Complete AI-First Numbered Knowledge Operating System (`00_` through `35_`).
- [x] Composite performance database indexes across 20+ tenant tables.
- [x] Operation issue exception triage system (`OperationIssue`).
