# 12_MODELS - Eloquent Model Directory

Core Eloquent models reside in `app/Models/` and modular sub-directories.

- **`Tenant`**: Represents center entity (`subdomain`, `type`, `status`).
- **`User`**: Authenticatable entity (`BelongsToTenant`, Spatie `HasRoles`, Sanctum `HasApiTokens`).
- **`Student`**: Learner profile (`BelongsToTenant`, `SoftDeletes`, debt ledger).
- **`Guardian`**: Parent profile (linked via `guardian_student` pivot).
- **`Course`**: Academic course offering (`belongsTo` Stage/Grade).
- **`Attendance`**: Timetable slot attendance (`status`: present, absent, late).
- **`Sale` & `Invoice`**: POS orders, billing invoices, payments.
- **`Subscription`**: Center SaaS plan subscription.
- **`OperationIssue`**: System crash triage log.
