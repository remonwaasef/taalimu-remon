# 20_PERMISSIONS - Role & Permission Matrix

| Role Code | Scope | Capabilities |
| :--- | :--- | :--- |
| `super_admin` | System-Wide | Central admin portal (`/admin`), tenants, SaaS packages, backups, operation issues. |
| `admin` / `center_owner` | Tenant-Wide | Full control over center courses, students, attendance, sales, and settings. |
| `instructor` | Assigned Scope | Manage assigned courses, view timetables, mark attendance, track commissions. |
| `student` | Self Scope | View enrolled courses, submit assignments, take quizzes, view attendance. |
| `guardian` | Linked Children | View linked children's attendance, payment balances, receive WhatsApp alerts. |
