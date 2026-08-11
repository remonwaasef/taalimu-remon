# 20_PERMISSIONS - Role & Permission Matrix

| Role Code | Scope | Capabilities |
| :--- | :--- | :--- |
| `super_admin` | System-Wide | Central admin portal (`/admin`), tenants, SaaS packages, backups, operation issues. |
| `admin` / `center_owner` | Tenant-Wide | Full control over center courses, students, attendance, sales, and settings. |
| `instructor` | Assigned Scope | Manage assigned courses, view timetables, mark attendance, track commissions. |
| `student` | Self Scope | View enrolled courses, submit assignments, take quizzes, view attendance. |
| `guardian` | Linked Children | View linked children's attendance, payment balances, receive WhatsApp alerts. |

## Tenant Sub-Roles (Custom Roles)

Tenant admins can create granular custom sub-roles for staff (e.g. accountants, receptionists)
from one-click preset templates in `center.roles` (gated by the `advanced_roles` feature):

- **Accountant preset**: sales/expenses/billing view+manage, reports & analytics.
- **Secretary / Reception preset**: student CRUD, schedules, attendance, sales view/create.
- **Cashier preset**: students/courses view, sales create, billing view.
- **Staff preset**: read-only access to students, courses, schedules, attendance.

## Enforcement Rules

- Tenants only ever see **center-scope** permissions; system groups (`centers`, `tenants`)
  are hidden from the matrix and rejected server-side (`PermissionService::SYSTEM_GROUPS`).
- Core role names (`User::RESERVED_ROLE_NAMES`) cannot be reused or renamed by tenants.
- System roles (NULL `tenant_id`) render read-only inside a tenant's role editor.
