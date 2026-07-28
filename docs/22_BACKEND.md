# 22_BACKEND - Server Execution Stack & Queues

- **Request Dispatcher**: `IdentifyTenant` middleware binds active tenant context and applies Eloquent global scopes.
- **Service Container & Providers**: `AppServiceProvider`, `AuthServiceProvider`, `BaseModuleServiceProvider`.
- **Queue Workers (`app/Jobs/`)**: Async tasks (`ImportStudentsJob`, `ProcessBugReportNotifications`, `SendWhatsAppNotification`, `SyncUserToKlaviyo`).
- **Scheduled Tasks (`app/Console/Commands/`)**: `SendPaymentRemindersCommand`, `SendWaLinkRemindersCommand`, `SendDailyTelegramReport`.
