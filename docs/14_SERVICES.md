# 14_SERVICES - Domain Service Layer Directory

Business logic is decoupled into 35+ domain Service classes inside `app/Services/`:

- **`TenantRegistrationService`**: Center onboarding, user provisioning, setting defaults.
- **`FinanceService`**: Invoice creation, payment receipts, student ledger updates.
- **`AttendanceService`**: Timetable attendance tracking & parent WhatsApp alert triggers.
- **`SubscriptionService`**: SaaS plan changes, trial management, feature gating.
- **`PayoutService`**: Instructor commission calculation and payout execution.
- **`WhatsAppService` & `TelegramService`**: Messaging integrations and automated reporting.
- **`OperationIssueService`**: Exception capture, deduplication, triage.
- **`ScheduleConflictService`**: Timetable double-booking validation.
- **`SearchService`**: Unified student/course full-text search (Scout/Meilisearch) with tenant isolation and automatic LIKE fallback.
