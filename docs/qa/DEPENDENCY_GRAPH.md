# TAALIMU DEPENDENCY GRAPH

## 1. ENTITY DEPENDENCY CHAIN

The correct operational order for Taalimu:

```
PLATFORM LEVEL (Global - super_admin only)
├── Package
│   └── Feature
│       └── PackageFeature (pivot)
└── SiteSetting

TENANT LEVEL
├── Tenant
│   ├── User (admin/secretary/accountant)
│   ├── Subscription → Package
│   └── Branch
│       ├── Stage
│       │   └── Grade
│       │       └── Student → User
│       │           ├── Guardian (pivot: guardian_student)
│       │           ├── Enrollment → Course → User (instructor)
│       │           │   └── Sale → SaleItem → Payment
│       │           │       └── Invoice
│       │           ├── Attendance → Schedule → Course
│       │           ├── Quiz → Lesson → Section → Course
│       │           │   └── QuizAttempt → User
│       │           ├── Assignment → Lesson
│       │           │   └── AssignmentSubmission → User
│       │           └── Certificate → Course
│       ├── Instructor → User
│       │   ├── Commission → Sale
│       │   └── Payout
│       ├── Classroom
│       │   └── Asset
│       ├── Course
│       │   ├── Section → Lesson
│       │   │   ├── CourseResource
│       │   │   ├── Quiz → Question → QuestionOption
│       │   │   └── Assignment → AssignmentSubmission
│       │   ├── Schedule → Course + Instructor + Classroom
│       │   └── OnlineClass → Schedule + Zoom
│       │       ├── OnlineClassParticipant
│       │       ├── ClassRecording
│       │       └── VideoProgress → VideoAccessLog
│       ├── Booking → Student + Classroom
│       ├── Ticket → User
│       │   └── TicketMessage
│       ├── Expense
│       ├── Coupon
│       └── Notification
```

---

## 2. REQUIRED DEPENDENCIES (Must Exist Before)

| Entity | Required Before Can Create | Notes |
|--------|---------------------------|-------|
| **Tenant** | Everything (tenant-scoped) | Created via registration flow |
| **Package** | Subscription | Platform-level, super_admin creates |
| **Feature** | PackageFeature | Platform-level |
| **Subscription** | Feature checks (limits) | Tenant must have active subscription |
| **Stage** | Grade | Academic hierarchy: Stage → Grade |
| **Grade** | Student | Student belongs to Grade |
| **User** | Student, Instructor | User is the auth entity |
| **Student** | Enrollment, Attendance, Sale | Core entity |
| **Instructor** | Course assignment, Schedule | Must have linked User |
| **Course** | Section, Lesson, Schedule, Enrollment, Quiz | Core entity |
| **Section** | Lesson | Course → Section → Lesson |
| **Lesson** | Quiz, Assignment, Resource | Within Section |
| **Schedule** | Attendance, OnlineClass | Links Course + Instructor + Classroom |
| **Classroom** | Booking, Schedule | Physical/virtual space |
| **Enrollment** | Attendance, Certificate, Quiz attempt | Links Student + Course |
| **Sale** | Payment, Invoice | Financial record |
| **Payment** | Receipt | Payment against Sale |
| **Quiz** | QuizAttempt, Question | Assessment entity |
| **Assignment** | AssignmentSubmission | Homework entity |

---

## 3. OPTIONAL DEPENDENCIES

| Entity | Optional Dependency | Impact |
|--------|-------------------|--------|
| **Guardian** | Student (linked via pivot) | Parent portal access |
| **Branch** | Student, Instructor | Multi-branch only |
| **Asset** | Classroom | Inventory tracking |
| **Coupon** | Sale | Discount on sales |
| **Booking** | Classroom | Room reservation |
| **OnlineClass** | Schedule | Zoom integration |
| **Certificate** | Course completion | Optional credential |

---

## 4. DELETION DEPENDENCIES (What Breaks)

| If Deleted | Cascade/Restrict | Impact |
|-----------|-----------------|--------|
| **Tenant** | CASCADE on all related | All tenant data destroyed |
| **Student** | RESTRICT on Enrollment | Cannot delete if enrolled |
| **Course** | RESTRICT on Enrollment, Schedule | Cannot delete if students enrolled or scheduled |
| **Instructor** | RESTRICT on Course, Schedule | Cannot delete if assigned |
| **Classroom** | RESTRICT on Schedule | Cannot delete if has schedules |
| **Schedule** | RESTRICT on Attendance | Cannot delete if attendance recorded |
| **Lesson** | RESTRICT on Quiz, Assignment | Cannot delete if has quiz/assignment |
| **Sale** | RESTRICT on Payment | Cannot delete if payments exist |
| **User** | RESTRICT on Student, Instructor | Cannot delete if has profile |
| **Grade** | RESTRICT on Student | Cannot delete if students assigned |
| **Package** | RESTRICT on Subscription | Cannot delete if subscribers exist |

---

## 5. VALID WORKFLOW SEQUENCES

### 5.1 New Center Onboarding
```
1. Register → Create Tenant + User (center_admin)
2. Login → Onboarding wizard
3. Configure Settings (locale, academic template)
4. Create Stages → Grades
5. Create Instructors → Assign Courses
6. Create Classrooms
7. Create Courses → Sections → Lessons
8. Create Schedules (Course + Instructor + Classroom)
9. Add Students → Enroll in Courses
10. Mark Attendance
11. Create Sales → Record Payments
12. View Analytics
```

### 5.2 Daily Operations
```
1. Login → Dashboard
2. Check Attendance (mark/modify)
3. View Notifications (overdue payments, absent students)
4. Record Payments
5. Create New Students → Enroll
6. Update Course Content
7. View Reports
```

### 5.3 Financial Flow
```
1. Create Sale (or auto-create via enrollment)
2. Add Sale Items (courses, materials)
3. Apply Coupon (optional)
4. Record Payment (partial or full)
5. Generate Invoice
6. Send Payment Reminder (if overdue)
7. Process Refund (if needed)
8. Calculate Commission (instructor)
9. Process Payout
```

---

## 6. INVALID WORKFLOWS (Should Be Blocked)

| Invalid Workflow | Expected Behavior | Evidence |
|-----------------|-------------------|----------|
| Create Student without Grade | Validation error | StoreStudentRequest |
| Enroll Student in non-existent Course | 404 | Route model binding |
| Create Schedule without Course | Validation error | ScheduleController |
| Record Payment > Sale amount | Validation error | SaleController::addPayment |
| Delete Course with Enrollments | RESTRICT error | FK constraint |
| Delete Instructor with Courses | RESTRICT error | FK constraint |
| Create Attendance for past schedule | Allowed (with date check) | AttendanceService |
| Cross-tenant access | 403/404 | TenantScope + Policy |

---

## 7. MISSING ONBOARDING GUIDANCE

| Area | Gap | Impact |
|------|-----|--------|
| Post-registration | No guided tour after first login | Center admin may miss features |
| Stage/Grade setup | Manual process, no wizard | Incorrect academic structure |
| First course creation | No template or guidance | Inconsistent course setup |
| Payment gateway setup | Requires manual config | Delayed monetization |
| WhatsApp setup | Requires API credentials | No guided integration |
| Student import | CSV format not obvious from UI | Import failures |

---

## 8. CIRCULAR DEPENDENCY CHECK

| Pattern | Status | Notes |
|---------|--------|-------|
| Student ↔ Enrollment | OK | Enrollment links Student + Course (no cycle) |
| Course ↔ Schedule | OK | Schedule links Course + Instructor + Classroom (no cycle) |
| User ↔ Student | OK | One-directional (User has one Student) |
| Sale ↔ Payment | OK | One-directional (Sale has many Payments) |
| **No circular dependencies detected** | ✅ | Clean dependency graph |

---

*Generated during Pass 1 Discovery & Audit*
*Last Updated: 2026-09-02*