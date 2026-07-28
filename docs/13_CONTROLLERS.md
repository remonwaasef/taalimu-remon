# 13_CONTROLLERS - HTTP Controller Map

Controllers validate HTTP requests, delegate work to Services, and return views or JSON payloads.

- **Central Controllers (`app/Http/Controllers/`)**: `LandingController`, `RegistrationController`, `UnifiedAuthController`, `PaymentController`, `PayPalWebhookController`, `PaymobWebhookController`, `PhoneVerificationController`, `SocialAuthController`.
- **Admin Module Controllers (`Modules/Admin/Controllers/`)**: `AdminController`, `TenantController`, `SubscriptionController`, `BackupController`, `OperationIssueController`, `SettingsController`.
- **Center Module Controllers (`Modules/Center/Controllers/`)**: `CenterController`, `StudentController`, `CourseController`, `AttendanceController`, `SaleController`, `QuizController`, `AnalyticsController`.
- **Instructor Module Controllers (`Modules/Instructor/Controllers/`)**: `InstructorController`, `AttendanceController`, `GroupController`, `ScheduleController`.
