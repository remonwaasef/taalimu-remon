# 29_CODE_REVIEW - Code Review Checklist & Standards

> **Purpose**: Serves as a quality gate checklist for reviewing code changes before committing to main/production branches.

---

## Code Review Checklist

### 1. Architecture & Design
- [ ] Maintains **Modular Monolith** structure (`Modules/*`).
- [ ] Preserves single-database multi-tenancy isolation (`BelongsToTenant` trait, `tenant_id` scope).
- [ ] Business logic resides in **Services** (`app/Services/`), NOT in Controllers.
- [ ] Reuses existing helpers, traits, and service classes rather than duplicating logic.

### 2. SOLID & Clean Code Principles
- [ ] Methods adhere to Single Responsibility Principle (SRP) and do not exceed ~30 lines.
- [ ] Dependencies are injected via constructor injection.
- [ ] Code uses early return guard clauses to prevent deep nesting.
- [ ] Expressive, descriptive variable and method names (`$totalUserOrders` vs `$data`).

### 3. Database & Query Performance
- [ ] All database modifications are implemented via Laravel migrations.
- [ ] Multi-table database updates are wrapped in `DB::transaction()`.
- [ ] Queries utilize Eager Loading (`with()`) to prevent N+1 query bugs.
- [ ] Composite performance indexes exist on foreign keys (`tenant_id`, `created_at`).

### 4. Security & Authorization
- [ ] Request input is validated via Form Request classes or `$request->validate()`.
- [ ] Authentication checks (`auth`, `auth:sanctum`) are present.
- [ ] Authorization policies (`$this->authorize()`) and custom middleware gates (`CheckAdminRole`, `CheckSubscription`) are enforced.
- [ ] SQL Injection, XSS, and CSRF protection headers (`ContentSecurityPolicy`, `BasicWAF`) are active.

### 5. Testing & Verification
- [ ] Automated tests exist for new features or bug fixes (`php artisan test`).
- [ ] End-to-End Playwright JS tests pass without visual regressions.

### 6. Documentation Sync
- [ ] Corresponding documentation files inside `docs/` have been updated.
- [ ] [docs/34_CHANGELOG.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/34_CHANGELOG.md) has an entry under `[Unreleased]`.
