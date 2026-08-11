# 28_TODO - Roadmap & Priority Checklist

### High Priority
- [x] **Redis Caching Driver Integration** (Cache/Session/Queue على Redis مع fallback تلقائي لقاعدة البيانات).
- [x] **Full-Text Search Engine Integration** (Laravel Scout + Meilisearch مع عزل المستأجرين وfallback إلى LIKE).
- [x] **Enterprise Database Scaling Evaluation** (رُفض تقسيم tenant_id لأسباب قاطعة — انظر 37_DATABASE_SCALING.md؛ الخطة: MariaDB 10.6+ + RANGE شهري للجداول بلا FK).

### Medium Priority
- [ ] **Role Permission Granularity UI** (Sub-roles for accountants & receptionists).
- [ ] **Image Upload WebP Auto-Compression** (`app/Traits/HandlesFileUploads.php`).

### Completed
- [x] Complete AI-First Numbered Knowledge Operating System (`00_` through `35_`).
- [x] Composite performance database indexes across 20+ tenant tables.
- [x] Operation issue exception triage system (`OperationIssue`).
