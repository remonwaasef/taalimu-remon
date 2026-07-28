# PROJECT MANIFEST - Taalimu.com

> **Primary AI Entry Point**: This manifest provides the essential technical overview, system constraints, folder structure, and entry points for any AI assistant operating on this repository.

---

## Project Specification

- **Project Name**: Taalimu.com (Multi-Tenant Educational Center & LMS SaaS Platform)
- **Purpose**: Automates educational center operations including tenant onboarding, student enrollments, course delivery, attendance logging, financial sales/invoicing, WhatsApp alerts, and instructor payouts.
- **Target Users**: Super Admins, Center Owners / Academy Managers, Instructors, Students, Guardians / Parents.
- **Project Type**: Web Application & SaaS API Platform.
- **Laravel Version**: `12.x`
- **PHP Version**: `^8.2 | ^8.4`
- **Database Engine**: MySQL / MariaDB (Single-database multi-tenancy with `tenant_id` partitioning)
- **Frontend Stack**: Hybrid (Blade + Alpine.js micro-interactions + Inertia.js React 19 interactive dashboards + TailwindCSS 3.4 + Bootstrap 5.3 + Vite 7).
- **Backend Stack**: PHP 8.4, Laravel 12, Modular Monolith (`nwidart/laravel-modules`), Laravel Reverb (WebSockets), Spatie Permission / Activitylog / Backup, Sanctum API tokens.
- **Architecture Style**: Modular Monolith Domain Architecture (`Admin`, `Center`, `Instructor`, `Campus`, `Tenancy`, `Api`).
- **Documentation Location**: `docs/` directory (Numbered sequential order `00_` to `35_`).
- **AI Entry Point**: [docs/00_AI_BOOT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/00_AI_BOOT.md) and [docs/01_MASTER_CONTEXT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/01_MASTER_CONTEXT.md).
- **Current Status**: Production Baseline (`v1.0.0` - Core 90% Complete).

---

## Important Rules for AI Assistants
1. **Always Read Entry Points First**: Read `docs/00_AI_BOOT.md` and `docs/01_MASTER_CONTEXT.md` before taking any action.
2. **Minimal Code Inspection**: Inspect only target files directly related to the user request.
3. **Preserve Architecture**: Maintain single-database multi-tenancy isolation (`BelongsToTenant` trait, `tenant_id` scope) and modular monolith bounds (`Modules/*`).
4. **Thin Controllers**: Keep controllers thin; delegate domain logic to `app/Services/`.
5. **Database Safety**: Never modify database schemas without creating Laravel migration files inside `database/migrations/`.
6. **Documentation Synchronization**: Update corresponding `docs/` files and [docs/34_CHANGELOG.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/34_CHANGELOG.md) after every completed task.

---

## Coding Philosophy
- Adhere strictly to **PSR-12** and **Laravel Pint** formatting.
- Follow **SOLID** principles and Single Responsibility Principle (SRP).
- Wrap multi-table database operations inside explicit `DB::transaction()` blocks.
- Use early return guard clauses to reduce nesting.

---

## Development Workflow Summary
```bash
# Local development server launch
composer dev

# Running tests
php artisan test
npx playwright test

# Code formatting
vendor/bin/pint
```
