# 24_STYLE_GUIDE - Coding Standards & Rules

- **PSR-12 & Laravel Pint**: Mandatory code formatting rules (`vendor/bin/pint`).
- **Thin Controllers & Single Responsibility**: Delegate domain business logic to Service classes in `app/Services/`.
- **Early Return Guard Clauses**: Avoid deeply nested conditional loops.
- **Explicit Transactions**: Multi-table database operations must use `DB::transaction()`.
