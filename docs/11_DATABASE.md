# 11_DATABASE - Relational Database Schema Reference

## Overview
Taalimu.com utilizes a single relational database (MySQL/MariaDB) with tenant data partitioning via `tenant_id` foreign key columns. Foreign keys feature explicit CASCADE or RESTRICT actions, composite indexes for performance, and check constraints for financial precision.

---

## Primary Table Schema Groups

### 1. Tenancy & SaaS System Tables
- **`tenants`**: Central tenant entity (`id`, `uuid`, `name`, `subdomain`, `domain`, `type`, `status`, `onboarding_status`, `notes`, `created_at`, `updated_at`).
- **`packages`**: SaaS subscription plans (`id`, `name`, `price`, `term_price`, `currency`, `billing_cycle`, `max_students`, `max_courses`, `is_active`).
- **`features`**: Defined system capabilities (`id`, `name`, `code`, `description`).
- **`package_features`**: Pivot linking packages to enabled features (`package_id`, `feature_id`).
- **`subscriptions`**: Active tenant subscriptions (`id`, `tenant_id`, `package_id`, `type`, `status`, `starts_at`, `ends_at`, `trial_ends_at`, `paypal_subscription_id`).
- **`subscription_logs`**: Subscription action audit trail (`id`, `tenant_id`, `subscription_id`, `action`, `notes`, `created_at`).

### 2. Core User & Auth Tables
- **`users`**: Central authentication table (`id`, `tenant_id`, `name`, `email`, `phone`, `password`, `role`, `status`, `google_id`, `qr_identifier`, `two_factor_secret`, `remember_token`).
- **`roles` & `permissions`**: Spatie RBAC tables (`roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`).
- **`personal_access_tokens`**: Sanctum API tokens.

### 3. Academic Structure Tables
- **`stages`**: Academic education levels (`id`, `tenant_id`, `name`, `order`, `deleted_at`).
- **`grades`**: Academic grades/years (`id`, `tenant_id`, `stage_id`, `name`, `deleted_at`).
- **`classrooms`**: Physical or virtual classrooms (`id`, `uuid`, `tenant_id`, `branch_id`, `name`, `capacity`, `image`).
- **`courses`**: Academic courses (`id`, `tenant_id`, `stage_id`, `grade_id`, `subject_name`, `registration_token`, `price`, `status`, `deleted_at`).
- **`sections`**: Course curriculum modules (`id`, `course_id`, `title`, `order`).
- **`lessons`**: Specific course lessons (`id`, `section_id`, `title`, `video_url`, `content`, `order`).
- **`course_resources`**: Downloadable course materials (`id`, `course_id`, `title`, `file_path`, `file_type`).
- **`course_instructor`**: Pivot assigning instructors to courses (`course_id`, `instructor_id`).

### 4. Student & Guardian Domain
- **`students`**: Registered student profiles (`id`, `tenant_id`, `user_id`, `first_name`, `last_name`, `code`, `phone`, `birth_date`, `notes`, `payment_status`, `deleted_at`).
- **`guardians`**: Parent profiles (`id`, `tenant_id`, `user_id`, `name`, `phone`, `email`, `relationship`).
- **`guardian_student`**: Pivot connecting guardians to students (`guardian_id`, `student_id`).
- **`enrollments`**: Course enrollment records (`id`, `tenant_id`, `student_id`, `course_id`, `status`, `enrolled_at`).
- **`lesson_progress`**: Lesson completion tracker (`id`, `student_id`, `lesson_id`, `completed_at`).

### 5. Attendance & Scheduling
- **`schedules`**: Timetable slots (`id`, `tenant_id`, `course_id`, `classroom_id`, `instructor_id`, `day_of_week`, `start_time`, `end_time`, `location`).
- **`attendances`**: Attendance records (`id`, `tenant_id`, `schedule_id`, `student_id`, `status` ['present','absent','late'], `late_minutes`, `date`).

### 6. Financial & Commerce Domain
- **`sales`**: POS and enrollment sales orders (`id`, `tenant_id`, `student_id`, `subtotal`, `discount`, `tax`, `total`, `payment_status`, `deleted_at`).
- **`sale_items`**: Line items within a sale (`id`, `sale_id`, `course_id`, `price`).
- **`invoices`**: Billing invoice records (`id`, `tenant_id`, `sale_id`, `student_id`, `amount`, `status`, `due_date`).
- **`payments`**: Payment transactions (`id`, `tenant_id`, `invoice_id`, `amount`, `payment_method`, `transaction_id`, `status`).
- **`expenses`**: Center operational expenses (`id`, `tenant_id`, `category`, `amount`, `description`, `expense_date`).
- **`commissions`**: Instructor earnings (`id`, `tenant_id`, `instructor_id`, `course_id`, `amount`, `status`).
- **`payouts`**: Commission disbursement records (`id`, `tenant_id`, `instructor_id`, `amount`, `status`, `payout_date`).
- **`coupons`**: Discount codes (`id`, `tenant_id`, `code`, `discount_type`, `discount_value`, `expires_at`).

### 7. Assessment & Quizzes
- **`quizzes`**: Quiz definitions (`id`, `tenant_id`, `course_id`, `title`, `duration_minutes`, `pass_mark`).
- **`questions`**: Question bank items (`id`, `quiz_id`, `category_id`, `question_text`, `type`, `points`).
- **`question_options`**: Options for multiple choice questions (`id`, `question_id`, `option_text`, `is_correct`).
- **`quiz_attempts`**: Student quiz submissions (`id`, `quiz_id`, `student_id`, `score`, `passed`, `completed_at`).

### 8. System Support & Diagnostics
- **`tickets` & `ticket_messages`**: Helpdesk ticket records.
- **`operation_issues`**, **`issue_timeline`**, **`issue_attachments`**: Production system exception tracking.
- **`bug_reports`**: User-submitted bug reports.
- **`activity_log`**: Audit trail of all database mutations.
- **`user_consents`**: GDPR cookie consent records.
