# TAALIMU DATABASE INTEGRITY AUDIT

## 1. SCHEMA OVERVIEW

| Metric | Value |
|--------|-------|
| Total Migrations | 81+ |
| Date Range | 2025-11 to 2026-09 |
| Database Driver | MySQL (primary), SQLite (dev) |
| Foreign Keys | Enabled (`DB_FOREIGN_KEYS=true`) |
| Soft Deletes | Applied to critical tables |

---

## 2. TABLE INVENTORY

### 2.1 Core Tables

| Table | Columns | Soft Deletes | Unique Constraints | FK Constraints | Indexes |
|-------|---------|-------------|-------------------|---------------|---------|
| tenants | id, name, email, domain, settings, status, timezone, ... | No | domain (unique) | None | idx_tenants_domain |
| users | id, tenant_id, name, email, password, role, qr_identifier, ... | Yes | email (per tenant) | tenant_id → tenants | idx_users_tenant_role, idx_users_email |
| students | id, tenant_id, user_id, grade_id, code, name, status, ... | Yes | code (per tenant) | user_id → users, grade_id → grades, tenant_id → tenants | idx_students_tenant_id, idx_students_code |
| instructors | id, tenant_id, user_id, name, commission_rate, ... | No | - | user_id → users, tenant_id → tenants | idx_instructors_user_id |
| courses | id, tenant_id, name, price, status, instructor_id, ... | Yes | - | instructor_id → instructors, tenant_id → tenants | idx_courses_tenant_id |
| enrollments | id, tenant_id, user_id, course_id, progress, ... | Yes | user+course (unique) | user_id → users, course_id → courses | idx_enrollments_tenant_id |
| schedules | id, tenant_id, course_id, instructor_id, classroom_id, ... | No | - | course_id → courses, instructor_id → instructors, classroom_id → classrooms | idx_schedules_tenant_id, idx_schedules_time |
| attendances | id, tenant_id, student_id, schedule_id, status, session_date, ... | No | student+schedule+date (unique) | student_id → students, schedule_id → schedules | idx_attendances_tenant_id |

### 2.2 Financial Tables

| Table | Columns | Soft Deletes | Unique Constraints | FK Constraints | Indexes |
|-------|---------|-------------|-------------------|---------------|---------|
| sales | id, tenant_id, student_id, total_amount, paid_amount, status, ... | Yes | - | student_id → students, tenant_id → tenants | idx_sales_tenant_id |
| sale_items | id, sale_id, item_type, item_id, amount, ... | No | - | sale_id → sales | idx_sale_items_type_id |
| payments | id, sale_id, amount, reference_number, method, ... | Yes | reference_number (global) | sale_id → sales | idx_payments_sale_id |
| invoices | id, tenant_id, sale_id, amount, status, ... | Yes | - | sale_id → sales, tenant_id → tenants | idx_invoices_tenant_id |
| refunds | id, sale_id, payment_id, amount, reason, ... | Yes | - | sale_id → sales, payment_id → payments | idx_refunds_sale_id |
| expenses | id, tenant_id, category, amount, date, ... | Yes | - | tenant_id → tenants | idx_expenses_tenant_id |
| commissions | id, tenant_id, instructor_id, sale_id, amount, ... | Yes | - | instructor_id → instructors, sale_id → sales | idx_commissions_tenant_id |
| payouts | id, tenant_id, instructor_id, amount, status, ... | Yes | - | instructor_id → instructors, tenant_id → tenants | idx_payouts_tenant_id |

### 2.3 Assessment Tables

| Table | Columns | Soft Deletes | Unique Constraints | FK Constraints | Indexes |
|-------|---------|-------------|-------------------|---------------|---------|
| quizzes | id, tenant_id, lesson_id, title, ... | No | - | lesson_id → lessons, tenant_id → tenants | idx_quizzes_tenant_id |
| questions | id, quiz_id, text, type, ... | No | - | quiz_id → quizzes | idx_questions_quiz_id |
| question_options | id, question_id, text, is_correct, ... | No | - | question_id → questions | idx_options_question_id |
| quiz_attempts | id, user_id, quiz_id, score, completed_at, ... | Yes | - | user_id → users, quiz_id → quizzes | idx_quiz_attempts_user_id |
| assignments | id, lesson_id, title, due_date, ... | No | - | lesson_id → lessons | idx_assignments_lesson_id |
| assignment_submissions | id, assignment_id, user_id, file_path, grade, ... | No | - | assignment_id → assignments, user_id → users | idx_submissions_user_id |

### 2.4 Other Tables

| Table | Purpose |
|-------|---------|
| branches | Multi-branch support |
| stages | Academic stages (primary, preparatory, secondary) |
| grades | Grade levels within stages |
| classrooms | Physical/virtual classrooms |
| assets | Inventory management |
| course_resources | Files attached to lessons |
| course_instructor | Pivot: course ↔ instructor |
| guardian_student | Pivot: guardian ↔ student |
| notifications | In-app notifications |
| tickets / ticket_messages | Support tickets |
| operation_issues / issue_timeline / issue_attachments | Admin operation tracking |
| user_consents | GDPR cookie consent |
| site_settings | Platform settings |
| coupons | Discount codes |
| point_logs | Gamification points |
| certificates | Course completion certificates |
| online_classes / online_class_participants / class_recordings | Zoom integration |
| video_progress / video_access_logs | Video tracking |
| online_checkouts | Platform-level checkout map |
| password_reset_tokens | Password reset (scoped by tenant_id) |
| personal_access_tokens | Sanctum API tokens |
| model_has_roles / model_has_permissions / role_has_permissions | Spatie Permission |
| failed_jobs | Queue failed jobs |
| cache | Cache store |
| jobs | Queue jobs |

---

## 3. FOREIGN KEY ANALYSIS

### 3.1 Cascade Rules (Critical)

| Table | Column | References | On Delete | On Update | Risk |
|-------|--------|------------|-----------|-----------|------|
| students | user_id | users.id | RESTRICT | CASCADE | ✅ Safe |
| students | grade_id | grades.id | RESTRICT | CASCADE | ✅ Safe |
| students | tenant_id | tenants.id | RESTRICT | CASCADE | ✅ Safe |
| courses | instructor_id | instructors.id | RESTRICT | CASCADE | ✅ Safe |
| courses | tenant_id | tenants.id | RESTRICT | CASCADE | ✅ Safe |
| enrollments | user_id | users.id | RESTRICT | CASCADE | ✅ Safe |
| enrollments | course_id | courses.id | RESTRICT | CASCADE | ✅ Safe |
| schedules | course_id | courses.id | RESTRICT | CASCADE | ✅ Safe |
| schedules | instructor_id | instructors.id | RESTRICT | CASCADE | ✅ Safe |
| schedules | classroom_id | classrooms.id | RESTRICT | CASCADE | ✅ Safe |
| attendances | student_id | students.id | RESTRICT | CASCADE | ✅ Safe |
| attendances | schedule_id | schedules.id | RESTRICT | CASCADE | ✅ Safe |
| sales | student_id | students.id | RESTRICT | CASCADE | ✅ Safe |
| payments | sale_id | sales.id | RESTRICT | CASCADE | ✅ Safe |
| invoices | sale_id | sales.id | RESTRICT | CASCADE | ✅ Safe |
| quizzes | lesson_id | lessons.id | RESTRICT | CASCADE | ✅ Safe |
| questions | quiz_id | quizzes.id | RESTRICT | CASCADE | ✅ Safe |
| quiz_attempts | user_id | users.id | RESTRICT | CASCADE | ✅ Safe |
| quiz_attempts | quiz_id | quizzes.id | RESTRICT | CASCADE | ✅ Safe |
| certificates | student_id | students.id | RESTRICT | CASCADE | ✅ Safe |
| certificates | course_id | courses.id | RESTRICT | CASCADE | ✅ Safe |

### 3.2 Issue: Missing FK on Some Tables

| Table | Missing FK | Risk |
|-------|-----------|------|
| tickets | user_id → users.id | LOW (manual link) |
| notifications | user_id → users.id | LOW (nullable) |
| point_logs | student_id → students.id | LOW |

---

## 4. CHECK CONSTRAINTS

| Table | Column | Constraint | Migration |
|-------|--------|-----------|-----------|
| students | monthly_fee | `CHECK (monthly_fee >= 0)` | 2026_06_02 |
| courses | price | `CHECK (price >= 0)` | 2026_06_02 |
| sales | total_amount | `CHECK (total_amount >= 0)` | 2026_06_02 |
| sales | paid_amount | `CHECK (paid_amount >= 0)` | 2026_06_02 |
| payments | amount | `CHECK (amount > 0)` | 2026_06_02 |
| users | role | `CHECK (role IN (...))` | 2026_06_02 |
| attendances | status | `CHECK (status IN (...))` | 2026_06_02 |

---

## 5. UNIQUE CONSTRAINTS

| Table | Columns | Scope | Migration |
|-------|---------|-------|-----------|
| tenants | domain | Global | 0000_01_01_000000 |
| users | email | Per tenant | 2026_05_02 |
| students | code | Per tenant | 2026_06_07 |
| payments | reference_number | Global | 2026_08_10 |
| enrollments | user_id + course_id | Per tenant | 2026_09_01 |
| attendances | student_id + schedule_id + session_date | Per tenant | 2025_11_29 |
| personal_access_tokens | token | Global | 2019_12_14 |

---

## 6. INDEX ANALYSIS

### 6.1 Performance Indexes (50+)

| Table | Index | Columns | Purpose |
|-------|-------|---------|---------|
| users | idx_users_tenant_role | tenant_id, role | Role-based queries |
| users | idx_users_email | email | Login lookup |
| students | idx_students_tenant_id | tenant_id | Tenant filtering |
| students | idx_students_code | code | Student lookup |
| students | idx_students_phone | phone, tenant_id | Phone search |
| courses | idx_courses_tenant_id | tenant_id | Tenant filtering |
| schedules | idx_schedules_tenant_id | tenant_id | Tenant filtering |
| schedules | idx_schedules_time | day_of_week, start_time | Time-based queries |
| attendances | idx_attendances_tenant_id | tenant_id | Tenant filtering |
| attendances | idx_attendances_schedule | schedule_id, session_date | Schedule-based |
| sales | idx_sales_tenant_id | tenant_id | Tenant filtering |
| sales | idx_sales_student | student_id | Student history |
| payments | idx_payments_sale_id | sale_id | Payment lookup |
| enrollments | idx_enrollments_tenant_id | tenant_id | Tenant filtering |
| enrollments | idx_enrollments_course | course_id | Course enrollment |
| quizzes | idx_quizzes_tenant_id | tenant_id | Tenant filtering |
| quiz_attempts | idx_quiz_attempts_user_id | user_id | User attempts |
| certificates | idx_certificates_tenant_id | tenant_id | Tenant filtering |
| assets | idx_assets_tenant_id | tenant_id | Tenant filtering |
| course_resources | idx_course_resources_tenant_id | tenant_id | Tenant filtering |
| activity_log | idx_activity_log_created_at | created_at | Log queries |

### 6.2 Missing Indexes (Potential)

| Table | Missing Index | Impact |
|-------|--------------|--------|
| tickets | user_id, tenant_id | Slow ticket queries |
| notifications | user_id, read_at | Slow notification queries |
| online_classes | schedule_id | Slow class lookup |

---

## 7. ORPHAN RECORD CHECK

| Pattern | Risk | Evidence |
|---------|------|----------|
| Student without User | LOW | FK constraint prevents |
| Enrollment without Course | LOW | FK constraint prevents |
| Payment without Sale | LOW | FK constraint prevents |
| Attendance without Schedule | LOW | FK constraint prevents |
| Quiz without Lesson | LOW | FK constraint prevents |
| Course without Tenant | LOW | TenantScope prevents |

---

## 8. DATA TYPE ANALYSIS

| Column | Type | Issue |
|--------|------|-------|
| students.risk_reasons | JSON | Array cast, nullable |
| tenant.settings | JSON (encrypted) | EncryptedSettings cast |
| users.password | varchar(255) | Hashed via cast |
| users.google2fa_secret | varchar(255) | Encrypted via cast |
| users.phone_verification_code | varchar(255) | Encrypted via cast |
| payments.amount | decimal(10,2) | ✅ Appropriate |
| sales.total_amount | decimal(10,2) | ✅ Appropriate |
| sales.paid_amount | decimal(10,2) | ✅ Appropriate |

---

## 9. SOFT DELETE AUDIT

| Table | Has Soft Deletes | Cascade on Force Delete |
|-------|-----------------|------------------------|
| students | ✅ Yes | enrollments, sales, attendances |
| courses | ✅ Yes | sections, lessons, quizzes |
| sales | ✅ Yes | payments, invoices, refunds |
| enrollments | ✅ Yes | None (leaf) |
| quiz_attempts | ✅ Yes | None (leaf) |
| assignments | ✅ Yes | submissions |
| instructors | ✅ Yes | courses, commissions |
| invoices | ✅ Yes | None (leaf) |
| payments | ✅ Yes | None (leaf) |
| refunds | ✅ Yes | None (leaf) |
| expenses | ✅ Yes | None (leaf) |
| commissions | ✅ Yes | None (leaf) |
| payouts | ✅ Yes | None (leaf) |

---

## 10. CONCLUSION

**Database Integrity: GOOD**

| Check | Status | Notes |
|-------|--------|-------|
| Foreign Keys | ✅ STRONG | RESTRICT on all critical relationships |
| Unique Constraints | ✅ GOOD | Email per tenant, code per tenant, reference global |
| Check Constraints | ✅ GOOD | Financial amounts >= 0, role/status enums |
| Indexes | ✅ GOOD | 50+ indexes on hot paths |
| Soft Deletes | ✅ GOOD | Applied to critical tables |
| Data Types | ✅ APPROPRIATE | JSON for flexible data, encrypted for secrets |

**Remaining Gaps**:
- Missing FK on tickets.user_id, notifications.user_id
- No retention policy for activity_log table
- No database partitioning (evaluated and rejected)
- Some indexes missing on notification/ticket tables

---

*Generated during Pass 1 Discovery & Audit*
*Last Updated: 2026-09-02*