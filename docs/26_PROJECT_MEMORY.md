# 26_PROJECT_MEMORY - Long-Term Project Invariants

- **Architecture Decisions**: Single-database multi-tenancy (`BelongsToTenant` trait), Modular Monolith (`Modules/*`), Hybrid Blade + Inertia React 19 UI.
- **Business Rule Invariants**: Tenant data isolation, subscription feature gating, mandatory DB transactions for financial operations.
- **Things Future AI Should Never Change**: Do NOT remove `tenant_id` database partitioning, do NOT collapse modular monorepo structure back into flat `app/`, do NOT modify database schemas without Laravel migrations.
