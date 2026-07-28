# 10_MODULES - Modular Monolith Architecture

The system divides domain boundaries into 6 core modules stored in `Modules/`:

- **`Modules/Admin`**: Central Super Admin portal (tenants, subscriptions, packages, backups, operation issue triage).
- **`Modules/Center`**: Primary educational center portal (students, courses, schedules, sales, expenses, attendance, quizzes).
- **`Modules/Instructor`**: Dedicated teacher workspace (fast attendance sheet, timetables, groups, payouts).
- **`Modules/Campus`**: Multi-branch physical campus location management.
- **`Modules/Tenancy`**: Tenant resolution (`IdentifyTenant` middleware) and environment initialization.
- **`Modules/Api`**: RESTful API endpoints for mobile/third-party clients.
