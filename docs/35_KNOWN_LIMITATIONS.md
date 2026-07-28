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

- **Full-Text Search Engine**: Replace MySQL `LIKE` queries with Laravel Scout + Meilisearch for instant student and course lookups.
- **Redis Cache Layer**: Transition default file cache driver to Redis for high-speed session storage.
- **Database Partitioning**: Evaluate database table partitioning by `tenant_id` for enterprise accounts.
