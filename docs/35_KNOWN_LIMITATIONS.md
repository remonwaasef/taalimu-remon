# 35_KNOWN_LIMITATIONS - System Boundaries & Constraints

> **Purpose**: Documents architectural limitations, third-party bottlenecks, technical debt, and potential future scaling improvements.

---

# Architectural Limitations

### 1. Single-Database Multi-Tenancy Boundary
- **Description**: All tenants share a single database instance with table-level `tenant_id` partitioning.
- **Limitation**: Large enterprise clients requiring isolated database schemas cannot be hosted without custom infrastructure setup.
- **Mitigation**: Critical composite indexes (`tenant_id`, `created_at`, `deleted_at`) exist on 20+ tables.

### 2. Third-Party WhatsApp Messaging Throttling
- **Description**: Attendance and debt alerts rely on third-party WhatsApp API bandwidth.
- **Limitation**: Bulk dispatching 10,000+ alerts simultaneously can hit rate limits or trigger spam blocks.
- **Mitigation**: Messages are offloaded to background queue jobs with rate limits.

### 3. Synchronous PDF Generation Overhead
- **Description**: `dompdf` converts HTML templates to PDF using PHP CPU cycles.
- **Limitation**: Large batch PDF invoice rendering can spike memory usage.
- **Mitigation**: Batch PDF generation is delegated to asynchronous queue background workers (`queue:listen`).

---

# Technical Debt & Roadmap Improvements

- **Full-Text Search Engine**: ✅ Implemented — Laravel Scout + Meilisearch powers student/course searches (`SearchService`) with automatic LIKE fallback when the engine is unreachable. Remaining: evaluate expanding to more models (invoices, quizzes) and enabling `SCOUT_DRIVER=meilisearch` on the production server with `php artisan scout:sync-index-settings` + `scout:import`.
- **Redis Cache Layer**: ✅ Implemented — cache/session/queue run on Redis with automatic database fallback.
- **Database Partitioning**: ✅ Evaluated — partitioning by `tenant_id` was rejected (ADR-004); time-based RANGE partitioning for FK-free tables (e.g., `activity_log`) is the recommended retention strategy. See [37_DATABASE_SCALING.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/37_DATABASE_SCALING.md).
