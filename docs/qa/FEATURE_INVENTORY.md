# TAALIMU FEATURE INVENTORY

## 1. TENANT REGISTRATION & ONBOARDING

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Tenant Registration | ✓ | - | - | - | - | - | Public | N/A (pre-tenant) | Package selection | - | Welcome email | - | - | - |
| Tenant Onboarding | - | ✓ | ✓ | - | - | - | center_admin | ✓ | - | Tenant exists | - | Logo upload | - | - |
| Package Selection | - | ✓ | ✓ | - | ✓ | ✓ | center_admin | ✓ | Package features | Tenant | - | - | - | - |
| Coupon Validation | - | ✓ | - | - | ✓ | - | Public | ✓ | Coupon limits | Package | - | - | - | ✓ |

---

## 2. AUTHENTICATION & USER MANAGEMENT

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Unified Login (Central) | - | ✓ | - | - | - | - | Public | N/A | - | - | - | - | - | ✓ |
| SSO to Tenant | - | ✓ | - | - | - | - | Authenticated | ✓ | - | Tenant exists | - | - | - | ✓ |
| Tenant Login | - | ✓ | - | - | - | - | Public (per tenant) | ✓ | - | - | - | - | - | ✓ |
| Magic Login (QR) | - | ✓ | - | - | - | - | Public (signed URL) | ✓ | - | Student exists | - | - | - | ✓ |
| Password Reset | ✓ | ✓ | ✓ | - | - | - | Public | ✓ (scoped) | - | - | Email | - | - | ✓ |
| Two-Factor Auth | ✓ | ✓ | ✓ | ✓ | - | - | Authenticated | ✓ | - | - | - | - | - | ✓ |
| Social Login (Google) | - | ✓ | - | - | - | - | Public | N/A | - | - | - | - | - | ✓ |
| User Management | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | manage users | ✓ | max_users? | Role/Permission | Email | Avatar | - | ✓ |
| Role Management | ✓ | ✓ | ✓ | ✓ | - | - | feature:advanced_roles | ✓ | - | Permissions | - | - | - | ✓ |
| Permission Matrix | - | ✓ | - | - | - | - | manage users | ✓ | - | Roles | - | - | - | - |

---

## 3. STUDENT MANAGEMENT

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Student CRUD | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | view/create/edit/delete students | ✓ | max_students | User, Grade | Email/SMS | Photo, ID card | Export | ✓ |
| Student Import (CSV) | ✓ | - | - | - | - | - | create students | ✓ | max_students | Grade, User | Queue job | CSV template | Import log | ✓ |
| Student Export | - | ✓ | - | - | ✓ | ✓ | view students | ✓ | - | - | - | Excel/CSV | - | ✓ |
| Bulk Actions | ✓ | - | ✓ | ✓ | ✓ | ✓ | edit students | ✓ | max_students | - | - | - | - | ✓ |
| Student Portal | - | ✓ | ✓ | - | - | - | Student role | ✓ | - | Enrollment | In-app | - | - | ✓ |
| Guardian Linking | ✓ | ✓ | ✓ | ✓ | ✓ | - | view students | ✓ | - | Guardian model | WhatsApp | - | - | ✓ |
| Student Self-Registration | ✓ | ✓ | - | - | - | - | Public (token) | ✓ | max_students | Course/Group | Email/WhatsApp | - | - | ✓ |
| ID Card Generation | - | ✓ | - | - | - | - | view students | ✓ | - | - | - | PDF | - | ✓ |
| Statement/History | - | ✓ | - | - | - | - | view students | ✓ | - | Sales, Attendance | - | PDF | Statement | ✓ |
| Debt Reminder | - | - | - | - | - | - | edit students | ✓ | - | Sales | WhatsApp/Email | - | - | ✓ |

---

## 4. INSTRUCTOR MANAGEMENT

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Instructor CRUD | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | view/create/edit/delete instructors | ✓ | max_instructors | User | Email | Photo | Export | ✓ |
| Course Assignment | ✓ | ✓ | ✓ | ✓ | - | - | edit instructors | ✓ | - | Course | - | - | - | ✓ |
| Schedule Assignment | ✓ | ✓ | ✓ | ✓ | - | - | manage schedule | ✓ | - | Schedule | - | - | - | ✓ |
| Payout Management | ✓ | ✓ | ✓ | - | ✓ | ✓ | manage billing | ✓ | - | Sales, Commission | Email | - | Payout report | ✓ |
| Instructor Portal | - | ✓ | ✓ | - | - | - | Instructor role | ✓ | - | Schedule, Courses | In-app | - | - | ✓ |
| Commission Rules | ✓ | ✓ | ✓ | ✓ | - | - | manage settings | ✓ | - | Package/Feature | - | - | - | ✓ |

---

## 5. COURSE MANAGEMENT

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Course CRUD | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | view/create/edit/delete courses | ✓ | max_courses | Instructor, Grade | - | Thumbnail | - | ✓ |
| Curriculum Builder | ✓ | ✓ | ✓ | ✓ | - | - | edit courses | ✓ | - | Course | - | - | - | ✓ |
| Sections & Lessons | ✓ | ✓ | ✓ | ✓ | - | - | edit courses | ✓ | - | Course | - | Videos, Files | - | ✓ |
| Resources (Files) | ✓ | ✓ | - | ✓ | - | - | edit courses | ✓ | - | Lesson | - | Upload | - | ✓ |
| Course Enrollment | ✓ | ✓ | - | ✓ | ✓ | - | edit courses | ✓ | max_students | Student, Schedule | WhatsApp/Email | - | - | ✓ |
| Quick Enroll | ✓ | - | - | - | - | - | edit courses | ✓ | max_students | Student | - | - | - | ✓ |
| Course Player | - | ✓ | - | - | - | - | Enrolled student | ✓ | - | Enrollment | - | Video streaming | Progress | ✓ |
| Public Course Pages | - | ✓ | - | - | ✓ | ✓ | Public | ✓ | - | - | - | - | SEO | ✓ |
| AI Content Assistant | ✓ | - | ✓ | - | - | - | create courses | ✓ | Feature: ai_content | External API | - | - | - | ✓ |

---

## 6. GROUP/CLASS MANAGEMENT

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Group CRUD | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | view/create/edit/delete groups | ✓ | max_groups? | Course, Instructor | - | - | - | ✓ |
| Student Grouping | ✓ | ✓ | ✓ | ✓ | - | - | edit groups | ✓ | - | Student | - | - | - | ✓ |

---

## 7. SCHEDULE MANAGEMENT

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Schedule CRUD | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | manage schedule | ✓ | feature:daily_schedules | Course, Instructor, Classroom | - | - | - | ✓ |
| Conflict Detection | - | ✓ | - | - | ✓ | - | manage schedule | ✓ | - | Schedule | AJAX API | - | - | ✓ |
| Available Slots | - | ✓ | - | - | ✓ | - | manage schedule | ✓ | - | Schedule | AJAX API | - | - | ✓ |
| Recurring Schedules | ✓ | ✓ | ✓ | ✓ | - | - | manage schedule | ✓ | - | Schedule | - | - | - | ✓ |
| Classroom Management | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | manage schedule | ✓ | max_classrooms | Branch | - | - | - | ✓ |
| Booking System | ✓ | ✓ | ✓ | ✓ | - | - | manage schedule | ✓ | - | Classroom | - | - | - | ✓ |

---

## 8. ATTENDANCE TRACKING

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Manual Attendance | ✓ | ✓ | ✓ | - | ✓ | ✓ | feature:attendance_tracking | ✓ | - | Schedule, Student | - | - | - | ✓ |
| QR Attendance | ✓ | ✓ | ✓ | - | - | - | Public (signed) | ✓ | feature:attendance_tracking | Schedule, Student | WhatsApp | QR Code | - | ✓ |
| QR Live Status | - | ✓ | - | - | - | - | feature:attendance_tracking | ✓ | - | Schedule | Real-time | - | - | ✓ |
| Bulk Absent | ✓ | - | - | - | - | - | feature:attendance_tracking | ✓ | - | Schedule | - | - | - | ✓ |
| Offline Sync | ✓ | - | - | - | - | - | feature:offline_attendance | ✓ | - | Queue | - | - | - | ✓ |
| Attendance Reports | - | ✓ | - | - | ✓ | ✓ | view reports | ✓ | feature:attendance_tracking | - | - | PDF/Excel | Analytics | ✓ |

---

## 9. FINANCE & BILLING

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Sale/Invoice CRUD | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | manage billing | ✓ | - | Student, Course | Email/WhatsApp | PDF | - | ✓ |
| Payment Recording | ✓ | ✓ | - | ✓ | ✓ | ✓ | manage billing | ✓ | - | Sale | WhatsApp/Email | Receipt PDF | - | ✓ |
| Partial Payments | ✓ | ✓ | - | - | - | - | manage billing | ✓ | - | Sale | - | - | - | ✓ |
| Payment Refund | ✓ | ✓ | - | - | - | - | manage billing | ✓ | - | Payment | - | - | - | ✓ |
| Overdue Tracking | - | ✓ | - | - | ✓ | ✓ | manage billing | ✓ | - | Sale | WhatsApp/Email | - | Overdue report | ✓ |
| Student Statement | - | ✓ | - | - | - | - | view students | ✓ | - | Sale, Payment | - | PDF | Statement | ✓ |
| Expense Tracking | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | manage billing | ✓ | - | - | - | Receipt | - | ✓ |
| Commission Calculation | Auto | ✓ | - | - | - | - | manage billing | ✓ | - | Sale, Instructor | - | - | Commission report | ✓ |
| Payout Processing | ✓ | ✓ | ✓ | - | - | - | manage billing | ✓ | - | Commission | Email | - | Payout report | ✓ |
| Coupon System | ✓ | ✓ | ✓ | ✓ | ✓ | - | manage settings | ✓ | - | Sale | - | - | - | ✓ |
| Online Payments | ✓ | ✓ | - | - | - | - | Student/Public | ✓ | feature:online_payments | Paymob/PayPal | Webhook | - | - | ✓ |

---

## 10. QUIZ & ASSESSMENT

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Quiz CRUD | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | feature:manage_exams | ✓ | - | Course, Lesson | - | - | - | ✓ |
| Question Bank | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | feature:manage_exams | ✓ | - | Category | - | - | - | ✓ |
| Quiz Attempt | ✓ | ✓ | - | - | - | - | Enrolled student | ✓ | - | Quiz | In-app | - | Result | ✓ |
| Auto-Grading | Auto | ✓ | - | - | - | - | - | ✓ | - | Question options | - | - | - | ✓ |
| Assignments | ✓ | ✓ | ✓ | ✓ | - | - | edit courses | ✓ | - | Lesson | - | File upload | - | ✓ |
| Assignment Submission | ✓ | ✓ | - | - | - | - | Enrolled student | ✓ | - | Assignment | - | File upload | - | ✓ |
| Grading | - | ✓ | ✓ | - | - | - | edit courses | ✓ | - | Submission | - | - | - | ✓ |
| Leaderboard | - | ✓ | - | - | - | - | feature:manage_exams | ✓ | - | Quiz attempts | - | - | Leaderboard | ✓ |
| Certificate Generation | ✓ | ✓ | - | - | - | - | feature:manage_exams | ✓ | - | Course completion | Email | PDF | - | ✓ |

---

## 11. ONLINE CLASSES

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Online Class CRUD | ✓ | ✓ | ✓ | ✓ | ✓ | - | manage schedule | ✓ | - | Schedule, Zoom | - | - | - | ✓ |
| Zoom Integration | ✓ | ✓ | - | - | - | - | manage schedule | ✓ | - | Zoom API | Webhook | Recording | - | ✓ |
| Live Participation | - | ✓ | - | - | - | - | Enrolled student | ✓ | - | Online Class | - | - | Attendance | ✓ |
| Recording Access | - | ✓ | - | - | - | - | Enrolled student | ✓ | - | Class recording | - | Video | Progress | ✓ |

---

## 12. ANALYTICS & REPORTING

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Dashboard Stats | - | ✓ | - | - | - | - | Authenticated | ✓ | - | All modules | - | - | Real-time | ✓ |
| Student Analytics | - | ✓ | - | - | ✓ | ✓ | view reports | ✓ | - | Students, Attendance | - | - | Charts | ✓ |
| Instructor Analytics | - | ✓ | - | - | ✓ | ✓ | view reports | ✓ | - | Instructors, Courses | - | - | Charts | ✓ |
| Course Analytics | - | ✓ | - | - | ✓ | ✓ | view reports | ✓ | - | Courses, Enrollment | - | - | Charts | ✓ |
| Financial Reports | - | ✓ | - | - | ✓ | ✓ | feature:financial_reports | ✓ | - | Sales, Payments | - | PDF/Excel | P&L, Commissions, Taxes | ✓ |
| Attendance Analytics | - | ✓ | - | - | ✓ | ✓ | feature:attendance_tracking | ✓ | - | Attendance | - | - | Charts | ✓ |
| Profit/Loss | - | ✓ | - | - | - | - | feature:financial_reports | ✓ | - | Sales, Expenses | - | PDF | P&L | ✓ |

---

## 13. SETTINGS & CONFIGURATION

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| General Settings | - | ✓ | ✓ | - | - | - | manage settings | ✓ | - | Tenant | - | Logo, Favicon | - | ✓ |
| Academic Settings | - | ✓ | ✓ | - | - | - | manage settings | ✓ | - | Stages, Grades | - | - | - | ✓ |
| Email Templates | - | ✓ | ✓ | - | - | - | manage settings | ✓ | - | - | - | - | - | ✓ |
| Reminder Settings | - | ✓ | ✓ | - | - | - | manage settings | ✓ | - | - | - | - | - | ✓ |
| Branch Management | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | feature:multi_branch | ✓ | max_branches | - | - | - | - | ✓ |
| Academic Templates | - | ✓ | ✓ | - | - | - | manage settings | ✓ | - | Stages, Grades | - | - | - | ✓ |

---

## 14. COMMUNICATION & NOTIFICATIONS

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| In-App Notifications | Auto | ✓ | ✓ | - | - | - | Authenticated | ✓ | - | All actions | Bell icon | - | - | ✓ |
| WhatsApp Notifications | Auto | - | - | - | - | - | - | ✓ | feature:whatsapp | Tenant credentials | Queue | - | Delivery log | ✓ |
| Email Notifications | Auto | - | - | - | - | - | - | ✓ | - | SMTP config | Queue | - | - | ✓ |
| Telegram Alerts | Auto | - | - | - | - | - | Super admin | Global | - | Bot token | Queue | - | - | ✓ |
| Bug Report Widget | ✓ | ✓ | - | - | - | - | Authenticated | ✓ | - | - | - | Screenshot | - | ✓ |

---

## 15. SUPPORT & TICKETS

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Ticket CRUD | ✓ | ✓ | ✓ | - | ✓ | ✓ | Authenticated | ✓ | - | - | Email/In-app | Attachment | - | ✓ |
| Ticket Reply | ✓ | ✓ | - | - | - | - | Authenticated | ✓ | - | Ticket | Email/In-app | - | - | ✓ |
| Admin Bug Reports | - | ✓ | ✓ | - | ✓ | - | super_admin | Global | - | - | - | Screenshot | - | ✓ |

---

## 16. SUBSCRIPTION & BILLING (PLATFORM)

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Package Management | ✓ | ✓ | ✓ | ✓ | ✓ | - | super_admin | Global | - | Features | - | - | - | ✓ |
| Feature Management | ✓ | ✓ | ✓ | ✓ | - | - | super_admin | Global | - | Packages | - | - | - | ✓ |
| Subscription Management | ✓ | ✓ | ✓ | ✓ | ✓ | - | super_admin | Global | - | Tenant, Package | Email | - | - | ✓ |
| Usage Monitoring | - | ✓ | - | - | - | - | super_admin | Global | - | SubscriptionService | Telegram | - | - | ✓ |
| Coupon Management | ✓ | ✓ | ✓ | ✓ | ✓ | - | super_admin | Global | - | Packages | - | - | - | ✓ |
| Paymob Webhook | Auto | ✓ | - | - | - | - | - | Global | - | HMAC verify | Telegram | - | - | ✓ |
| PayPal Webhook | Auto | ✓ | - | - | - | - | - | Global | - | Signature verify | - | - | - | ✓ |

---

## 17. ADMIN PANEL (SUPER ADMIN)

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Tenant Management | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | super_admin | Global | - | - | - | - | - | ✓ |
| Package Management | ✓ | ✓ | ✓ | ✓ | ✓ | - | super_admin | Global | - | Features | - | - | - | ✓ |
| Feature Management | ✓ | ✓ | ✓ | ✓ | - | - | super_admin | Global | - | Packages | - | - | - | ✓ |
| Operation Issues | ✓ | ✓ | ✓ | - | ✓ | ✓ | super_admin | Global | - | - | - | Attachment | - | ✓ |
| Backup Management | ✓ | ✓ | - | - | - | - | super_admin | Global | - | spatie/laravel-backup | - | Backup files | - | ✓ |
| Consent Reports | - | ✓ | - | - | - | - | super_admin | Global | - | user_consents | - | CSV | GDPR | ✓ |
| Site Settings | - | ✓ | ✓ | - | - | - | super_admin | Global | - | - | - | - | - | ✓ |

---

## 18. GDPR & COMPLIANCE

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Data Export | ✓ | ✓ | - | - | - | - | Authenticated | ✓ | - | All user data | - | JSON/PDF | - | ✓ |
| Data Deletion | ✓ | - | - | ✓ | - | - | Authenticated | ✓ | - | All user data | - | - | - | ✓ |
| Cookie Consent | ✓ | ✓ | ✓ | - | - | - | Public | Global | - | - | - | - | Report | ✓ |

---

## 19. PWA & MOBILE

| Feature | Status | Notes |
|---------|--------|-------|
| Offline Page | ✓ | /offline route |
| Service Worker | Partial | Needs verification |
| PWA Manifest | - | Not found |
| Mobile Navigation | ✓ | Responsive sidebar |
| QR Scanner | ✓ | Attendance marking |
| Push Notifications | - | Not implemented |
| App Install Prompt | - | Not implemented |

---

## 20. SEARCH & DISCOVERY

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Global Search (Meilisearch) | - | ✓ | - | - | ✓ | - | Authenticated | ✓ (explicit filter) | feature:search | Scout | - | - | - | ✓ |
| Student Search (AJAX) | - | ✓ | - | - | ✓ | - | view students | ✓ | - | - | - | - | - | ✓ |
| Course Search (Public) | - | ✓ | - | - | ✓ | ✓ | Public | ✓ | - | - | - | - | SEO | ✓ |

---

## 21. FILE MANAGEMENT

| Feature | Create | Read | Update | Delete | Search | Filter | Permissions | Tenant Isolation | Subscription Limits | Dependencies | Notifications | Files | Reports | APIs |
|---------|--------|------|--------|--------|--------|--------|-------------|------------------|---------------------|--------------|---------------|-------|---------|------|
| Course Resources | ✓ | ✓ | - | ✓ | - | - | edit courses | ✓ | - | Lesson | - | Upload | - | ✓ |
| Student Documents | ✓ | ✓ | - | ✓ | - | - | edit students | ✓ | - | Student | - | Upload | - | ✓ |
| Instructor Documents | ✓ | ✓ | - | ✓ | - | - | edit instructors | ✓ | - | Instructor | - | Upload | - | ✓ |
| Asset/Inventory | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | manage schedule | ✓ | max_classrooms? | Classroom | - | - | - | ✓ |
| Profile Photos | ✓ | ✓ | ✓ | - | - | - | Authenticated | ✓ | - | User | - | Upload | - | ✓ |

---

## SUMMARY STATISTICS

| Category | Features | CRUD Complete | AuthZ Covered | Tenant Isolated | Subscription Gated | Tested |
|----------|----------|---------------|---------------|-----------------|-------------------|--------|
| Tenant/Onboarding | 4 | 3/4 | N/A | N/A | Partial | Partial |
| Auth/User Mgmt | 9 | 7/9 | ✓ | ✓ | Partial | ✓ |
| Student Mgmt | 10 | 10/10 | ✓ | ✓ | ✓ | ✓ |
| Instructor Mgmt | 6 | 6/6 | ✓ | ✓ | ✓ | Partial |
| Course Mgmt | 9 | 9/9 | ✓ | ✓ | ✓ | ✓ |
| Schedule Mgmt | 6 | 6/6 | ✓ | ✓ | Feature-gated | Partial |
| Attendance | 6 | 5/6 | ✓ | ✓ | Feature-gated | ✓ |
| Finance/Billing | 11 | 11/11 | ✓ | ✓ | - | Partial |
| Quiz/Assessment | 9 | 9/9 | ✓ | ✓ | Feature-gated | Partial |
| Online Classes | 4 | 4/4 | ✓ | ✓ | - | Partial |
| Analytics | 7 | 0/7 (Read-only) | ✓ | ✓ | Feature-gated | Partial |
| Settings | 6 | 0/6 (Config) | ✓ | ✓ | Feature-gated | - |
| Communication | 5 | 1/5 (Auto) | ✓ | ✓ | Feature-gated | Partial |
| Support/Tickets | 3 | 3/3 | ✓ | ✓ | - | Partial |
| Platform Billing | 7 | 6/7 | ✓ | Global | - | Partial |
| Admin Panel | 7 | 6/7 | ✓ | Global | - | Partial |
| GDPR | 3 | 3/3 | ✓ | ✓ | - | Partial |
| PWA/Mobile | 7 | 2/7 | - | - | - | - |
| Search | 3 | 0/3 | ✓ | ✓ | Feature-gated | - |
| File Mgmt | 5 | 5/5 | ✓ | ✓ | - | Partial |

**Total Features: ~127**
**Fully Tested: ~65%**
**Critical Gaps: PWA, Financial concurrent tests, IDOR route tests, Import stress tests**

---

*Generated during Pass 1 Discovery & Audit*
*Last Updated: 2026-09-02*