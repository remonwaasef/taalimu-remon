# 19_AUTHORIZATION - Authorization Architecture & Policies

- **Spatie RBAC**: Roles and permissions (`roles`, `permissions`, `model_has_roles`).
- **Custom Middleware Gates**: `CheckAdminRole` (Super Admin), `CheckSubscription` (Active plan), `CheckFeature` (Package feature flags), `CheckEnrollment` (Course access).
- **Eloquent Policies (`app/Policies/`)**: `StudentPolicy`, `CoursePolicy`, `AttendancePolicy`, `SalePolicy`, `AssignmentPolicy`, `TenantPolicy`, `TicketPolicy`.
