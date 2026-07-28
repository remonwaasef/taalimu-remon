# 03_AI_RULES - AI Assistant Operating Rules

This document defines the mandatory rules every AI assistant must follow while working on this project.

These rules are strict and take precedence over convenience, assumptions, or shortcuts.

Failure to follow these rules can lead to architectural inconsistencies, duplicated logic, outdated documentation, unnecessary token usage, and unstable code.

---

# Primary Objective

Maintain, improve, and extend the project while:

* Preserving architecture.
* Preserving coding standards.
* Preserving consistency.
* Minimizing unnecessary code scanning.
* Avoiding duplicate logic.
* Keeping documentation synchronized with the source code.

---

# Documentation Is The Primary Source Of Truth

Always read: `docs/01_MASTER_CONTEXT.md` before performing any task.

Then read only the documentation files directly related to the requested feature.

Never ignore the documentation. Never bypass the documentation. Never assume undocumented behavior.

---

# Required Reading Order

1. `PROJECT_MANIFEST.md` / `README_AI.md`
2. `docs/00_AI_BOOT.md`
3. `docs/01_MASTER_CONTEXT.md`
4. Relevant feature documentation (`docs/05_` to `docs/25_`)
5. Required source files only
6. Implementation
7. Documentation updates (`docs/33_AFTER_EVERY_CHANGE.md` & `docs/34_CHANGELOG.md`)

---

# Minimal Inspection Rule

Never scan the entire project unless absolutely necessary.
Inspect only the minimum number of files required to complete the task.

---

# Architecture Preservation Rule

Do not change the architecture unless explicitly requested.

Preserve:
* Folder structure
* Design patterns
* Service layer
* Single-database multi-tenancy (`BelongsToTenant` trait, `tenant_id` scope)
* Modular Monolith (`Modules/*`)
* Dependency injection
* Naming conventions

---

# Reuse Before Create

Before creating a Service, Component, Trait, Helper, Repository, View, or Utility, search for an existing implementation and reuse it.

---

# Thin Controllers & Single Responsibility

Controllers must only validate input, delegate work to Services (`app/Services/`), and return responses. Keep methods small and focused on a single responsibility.

---

# Database & Route Safety Rules

Never rename database tables, columns, route names, controllers, or classes unless explicitly requested. Always write database modifications using Laravel migrations inside `database/migrations/`.

---

# Security & Performance

Always enforce authorization checks (`CheckAdminRole`, `CheckSubscription`, Policies), validate inputs via Form Requests, prevent SQL injection/XSS, and use eager loading to avoid N+1 queries.

---

# Large Changes Rule

Before modifying more than five files: explain the implementation plan, files affected, risks, and expected outcome, then wait for user approval.

---

# Documentation Synchronization

After every completed change, update all affected documentation files. Documentation must always reflect the current source code.
