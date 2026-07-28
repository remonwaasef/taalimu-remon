# 09_FEATURES - Primary Feature Catalog

## System Feature Inventory

### 1. Multi-Tenant Onboarding & Domain Management
- **Description**: Automated creation of tenant accounts, subdomain allocation (e.g., `academy.taalimu.com`), custom domain binding, and setup wizards.
- **Components**: `RegistrationController`, `TenantRegistrationService`, `IdentifyTenant` middleware.
- **Tables Involved**: `tenants`, `users`, `site_settings`.

### 2. Multi-Tier Educational Structure (Stages & Grades)
- **Description**: Supports flexible academic structures including Primary, Middle, Secondary, or customized international curricula (e.g., French System template).
- **Components**: `Stage`, `Grade`, `SettingsService`.
- **Tables Involved**: `stages`, `grades`.

### 3. Student Management & Enrolment System
- **Description**: Student registration, registration token creation, bulk CSV import, profile management, and classroom allocation.
- **Components**: `StudentController`, `StudentService`, `StudentImportService`, `ImportStudentsJob`.
- **Tables Involved**: `students`, `enrollments`, `classrooms`, `guardians`, `guardian_student`.

### 4. Course & Curriculum Management
- **Description**: Creation of courses, sections, lessons, online class links, and resource attachments (PDFs, videos).
- **Components**: `CourseController`, `CurriculumController`, `CourseService`.
- **Tables Involved**: `courses`, `sections`, `lessons`, `course_resources`, `course_instructor`.

### 5. Attendance & Schedule Management
- **Description**: Classroom scheduling, conflict checking, real-time student attendance marking (Present, Absent, Late with minutes), and automatic parent WhatsApp notifications.
- **Components**: `AttendanceController`, `AttendanceService`, `ScheduleConflictService`.
- **Tables Involved**: `schedules`, `attendances`, `classrooms`.

### 6. Quizzes, Question Bank & Assignments
- **Description**: Online quiz creation, categorised question bank (multiple choice, true/false), automated grading, student attempts, and assignment submission reviews.
- **Components**: `QuizController`, `QuestionBankController`, `AssignmentController`, `QuizService`.
- **Tables Involved**: `quizzes`, `questions`, `question_options`, `question_categories`, `quiz_attempts`, `assignments`, `assignment_submissions`.

### 7. Financial Management, Invoicing & Sales
- **Description**: Course fee billing, custom discounts, coupon validation, POS sales creation, partial payment tracking, expense logging, and student debt tracking.
- **Components**: `SaleController`, `BillingController`, `ExpenseController`, `FinanceService`, `StudentLedgerService`.
- **Tables Involved**: `sales`, `sale_items`, `invoices`, `payments`, `expenses`, `coupons`.

### 8. Instructor Commissions & Payouts
- **Description**: Support for fixed percentage or per-student commission rules, automated payout calculation, and payment execution logging.
- **Components**: `InstructorController`, `PayoutService`.
- **Tables Involved**: `instructors`, `commissions`, `payouts`.

### 9. SaaS Subscriptions & Packages (Center Owners)
- **Description**: Center owner subscription management, free trials, term-based pricing, feature flag gating, and PayPal / Paymob payment handling.
- **Components**: `SubscriptionController`, `PaymentController`, `SubscriptionService`, `PayPalService`.
- **Tables Involved**: `packages`, `features`, `package_features`, `subscriptions`, `subscription_logs`.

### 10. Automated Messaging (WhatsApp & Telegram)
- **Description**: Automated WhatsApp reminders for overdue payments, attendance alerts, and direct WhatsApp chat links. Daily and weekly financial summaries sent to Telegram channels.
- **Components**: `WhatsAppService`, `TelegramService`, `SendPaymentRemindersCommand`, `SendWaLinkRemindersCommand`.
- **Tables Involved**: `payment_reminders`, `students`, `users`.

### 11. Support Ticket & Operation Issue System
- **Description**: Internal helpdesk ticket system for center owners and a detailed Operation Issue tracker for system crashes with timeline logging and attachments.
- **Components**: `TicketController`, `OperationIssueController`, `OperationIssueService`, `IssueLogger`.
- **Tables Involved**: `tickets`, `ticket_messages`, `operation_issues`, `issue_timeline`, `issue_attachments`.

### 12. Security, Audit Logging & Compliance (GDPR)
- **Description**: Two-factor authentication (2FA), activity logging for user actions, cookie consent recording, WAF request inspection, and GDPR data export/erasure tools.
- **Components**: `TwoFactorController`, `GdprController`, `ConsentReportController`, `BasicWAF` middleware.
- **Tables Involved**: `activity_log`, `user_consents`, `users`.
