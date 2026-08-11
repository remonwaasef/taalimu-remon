# 27_DECISIONS - Architectural Decision Records (ADR)

- **ADR-001**: Single-Database Multi-Tenancy Strategy (`tenant_id` scope isolation).
- **ADR-002**: Modular Monolith Architecture (`nwidart/laravel-modules` boundary isolation).
- **ADR-003**: Hybrid Frontend Strategy (Blade for public pages, Inertia.js React 19 for dashboards).
- **ADR-004**: Database Partitioning — reject partitioning by `tenant_id` (FK + unique-key constraints, rebuild risk, partition-count ceiling); adopt time-based RANGE partitioning for FK-free append-heavy tables only, plus MariaDB 10.6+ standardization. Full analysis in [37_DATABASE_SCALING.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/37_DATABASE_SCALING.md).
