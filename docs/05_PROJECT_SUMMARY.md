# 05_PROJECT_SUMMARY - Executive Overview & Mission

## Executive Overview
**Taalimu.com** is a multi-tenant Educational Management SaaS & LMS platform engineered for educational centers, private tutoring academies, campuses, and independent instructors. The system provides complete operational automation—from tenant onboarding and domain routing to student management, course delivery, attendance logging, automated financial transactions, parent communication (WhatsApp/Telegram), and instructor payouts.

---

## Target Audience & User Roles
1. **Super Admins**: Central system managers who oversee tenants, manage SaaS subscriptions, handle support tickets, manage server backups, and resolve system operational issues.
2. **Center Owners / Academy Managers**: Tenant admins who manage their specific educational center, set up branches, define classrooms, recruit instructors, manage student enrollments, issue invoices, and track revenue.
3. **Instructors**: Teachers who view assigned courses, track student attendance, publish lesson content, create assignments & quizzes, and view commission payouts.
4. **Students**: Learners who log into center portals, view registered courses, participate in online classes, submit assignments, take quizzes, and track attendance/grades.
5. **Guardians / Parents**: Parents linked to students who receive real-time attendance alerts, automated WhatsApp payment/debt reminders, and student performance reports.

---

## Technical Stack Summary
- **Backend Framework**: Laravel 12.x / PHP 8.4
- **Database**: MySQL / MariaDB (Single database multi-tenancy with `tenant_id` scope isolation)
- **Frontend Engine**: Blade Templates + Alpine.js micro-interactions + Inertia.js (React 19) for rich interactive dashboards.
- **Styling & UI**: TailwindCSS 3.4 + Bootstrap 5.3 + custom design system CSS tokens.
- **Async & Realtime**: Laravel Queues, Laravel Reverb (WebSockets), Spatie ActivityLog & Backup.
