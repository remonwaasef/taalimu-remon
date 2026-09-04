# 🌐 Localization Audit Report

Generated at: 2026-09-02 22:13:09

## Summary

- **Total Languages:** 2
- **Total Translation Files:** 132
- **Total Referenced Keys:** 2733
- **Total Missing Keys:** 4774
- **Total Unused Keys:** 8600
- **Total Hardcoded Strings:** 254
- **Total Syntax Errors:** 0

## Missing Keys (4774)

| Key | Language | Ref Count |
| :--- | :--- | :--- |
| `online_classes::notifications.reminder_body` | ar | 1 |
| `online_classes::notifications.reminder_body` | en | 1 |
| `Your session has expired. Please register again.` | ar | 1 |
| `Your session has expired. Please register again.` | en | 1 |
| `Selected plan or tenant not found.` | ar | 1 |
| `Selected plan or tenant not found.` | en | 1 |
| `PayPal does not support EGP. Please use Paymob.` | ar | 1 |
| `PayPal does not support EGP. Please use Paymob.` | en | 1 |
| `Paymob is only available for EGP payments.` | ar | 1 |
| `Paymob is only available for EGP payments.` | en | 1 |
| `This email is already registered. Please login with your password to link your Google account.` | ar | 1 |
| `This email is already registered. Please login with your password to link your Google account.` | en | 1 |
| `Unable to login with Google. Please try again.` | ar | 1 |
| `Unable to login with Google. Please try again.` | en | 1 |
| `Session expired. Please try again with Google.` | ar | 2 |
| `Session expired. Please try again with Google.` | en | 2 |
| `Registration is being processed. Please wait.` | ar | 1 |
| `Registration is being processed. Please wait.` | en | 1 |
| `messages.phone_already_taken` | ar | 1 |
| `messages.phone_already_taken` | en | 1 |
| `2FA is not configured. Please scan the QR code again.` | ar | 1 |
| `2FA is not configured. Please scan the QR code again.` | en | 1 |
| `Security setup complete.` | ar | 1 |
| `Security setup complete.` | en | 1 |
| `Invalid OTP code.` | ar | 3 |
| `Invalid OTP code.` | en | 3 |
| `Two-factor authentication disabled.` | ar | 1 |
| `Two-factor authentication disabled.` | en | 1 |
| `online_classes::messages.token_invalid` | ar | 1 |
| `online_classes::messages.token_invalid` | en | 1 |
| `هذه الميزة غير متوفرة في باقتك الحالية.` | ar | 1 |
| `هذه الميزة غير متوفرة في باقتك الحالية.` | en | 1 |
| `بوابة الطالب غير مفعلة لهذا المركز حالياً.` | ar | 1 |
| `بوابة الطالب غير مفعلة لهذا المركز حالياً.` | en | 1 |
| `هذه الميزة غير متوفرة في باقتك الحالية. يرجى الترقية للوصول إليها.` | ar | 1 |
| `هذه الميزة غير متوفرة في باقتك الحالية. يرجى الترقية للوصول إليها.` | en | 1 |
| `لا تملك صلاحية الوصول إلى لوحة المعلم.` | ar | 1 |
| `لا تملك صلاحية الوصول إلى لوحة المعلم.` | en | 1 |
| `center::courses.validation_title_required` | ar | 1 |
| `center::courses.validation_title_required` | en | 1 |
| `center::courses.validation_instructor_required` | ar | 1 |
| `center::courses.validation_instructor_required` | en | 1 |
| `center::courses.validation_schedules_required` | ar | 1 |
| `center::courses.validation_schedules_required` | en | 1 |
| `center::courses.validation_day_required` | ar | 1 |
| `center::courses.validation_day_required` | en | 1 |
| `center::courses.validation_start_time_required` | ar | 1 |
| `center::courses.validation_start_time_required` | en | 1 |
| `center::courses.validation_end_time_after` | ar | 1 |
| `center::courses.validation_end_time_after` | en | 1 |

## Key Discrepancies (34)

| Key | Present In | Missing In |
| :--- | :--- | :--- |
| `admin.view_all` | ar | en |
| `admin.tenants.table.joined_on_format` | ar | en |
| `admin.tenants.table.name` | ar | en |
| `admin.tenants.table.domain` | ar | en |
| `admin.tickets.user` | ar | en |
| `admin.tickets.center` | ar | en |
| `admin.tickets.date` | ar | en |
| `dashboard.center_performance` | ar | en |
| `dashboard.header.search_placeholder` | ar | en |
| `messages.total_expenses` | ar | en |
| `messages.net_profit` | ar | en |
| `messages.blade_1078` | ar | en |
| `messages.blade_1079` | ar | en |
| `sidebar.add_student` | ar | en |
| `students.profile.student_email` | ar | en |
| `students.profile.parent_email` | ar | en |
| `admin.internal_description` | en | ar |
| `features.automated_whatsapp_alerts` | en | ar |
| `features.multi_branch_support` | en | ar |
| `validation.any_of` | en | ar |
| `validation.encoding` | en | ar |
| `validation.in_array_keys` | en | ar |
| `validation.prohibited_if_accepted` | en | ar |
| `validation.prohibited_if_declined` | en | ar |
| `validation.required_if_declined` | en | ar |
| `classrooms.students` | en | ar |
| `dashboard.actions.created` | en | ar |
| `dashboard.actions.updated` | en | ar |
| `dashboard.actions.deleted` | en | ar |
| `dashboard.actions.restored` | en | ar |
| `dashboard.actions.Successful Login` | en | ar |
| `messages.blade_0123` | en | ar |
| `messages.blade_0124` | en | ar |
| `sidebar.list` | en | ar |

## Hardcoded Strings (254)

| File | String |
| :--- | :--- |
| `resources\views\auth\2fa\enable.blade.php` | `Cancel` |
| `resources\views\auth\complete-google-registration.blade.php` | `Paymob` |
| `resources\views\auth\complete-google-registration.blade.php` | `PayPal` |
| `resources\views\auth\partials\_register-step2.blade.php` | `Paymob` |
| `resources\views\auth\partials\_register-step2.blade.php` | `PayPal` |
| `resources\views\auth\unified-login.blade.php` | `Or continue with` |
| `resources\views\auth\unified-login.blade.php` | `Google` |
| `resources\views\components\ui\filter.blade.php` | `All` |
| `resources\views\components\ui\navbar.blade.php` | `Light Mode` |
| `resources\views\components\ui\navbar.blade.php` | `Dark Mode` |
| `resources\views\components\ui\navbar.blade.php` | `System Default` |
| `resources\views\components\ui\navbar.blade.php` | `Sign Out` |
| `resources\views\components\ui\navbar.blade.php` | `Sign Out` |
| `resources\views\design-system\index.blade.php` | `Taalimu` |
| `resources\views\design-system\index.blade.php` | `Overview` |
| `resources\views\design-system\index.blade.php` | `Dashboard` |
| `resources\views\design-system\index.blade.php` | `Students` |
| `resources\views\design-system\index.blade.php` | `Teachers` |
| `resources\views\design-system\index.blade.php` | `Classes` |
| `resources\views\design-system\index.blade.php` | `Attendance` |
| `resources\views\design-system\index.blade.php` | `Subscriptions` |
| `resources\views\design-system\index.blade.php` | `Payments` |
| `resources\views\design-system\index.blade.php` | `Reports` |
| `resources\views\design-system\index.blade.php` | `Messages` |
| `resources\views\design-system\index.blade.php` | `ADVANCED` |
| `resources\views\design-system\index.blade.php` | `Learning` |
| `resources\views\design-system\index.blade.php` | `Exams` |
| `resources\views\design-system\index.blade.php` | `Achievements` |
| `resources\views\design-system\index.blade.php` | `Reputation` |
| `resources\views\design-system\index.blade.php` | `Certificates` |
| `resources\views\design-system\index.blade.php` | `Settings` |
| `resources\views\design-system\index.blade.php` | `View Taalimu Platform` |
| `resources\views\design-system\index.blade.php` | `RW` |
| `resources\views\design-system\index.blade.php` | `Remon Wasef` |
| `resources\views\design-system\index.blade.php` | `Administrator` |
| `resources\views\design-system\index.blade.php` | `Color System` |
| `resources\views\design-system\index.blade.php` | `Primary` |
| `resources\views\design-system\index.blade.php` | `Secondary` |
| `resources\views\design-system\index.blade.php` | `Success` |
| `resources\views\design-system\index.blade.php` | `Warning` |
| `resources\views\design-system\index.blade.php` | `Error` |
| `resources\views\design-system\index.blade.php` | `Typography` |
| `resources\views\design-system\index.blade.php` | `Body Large` |
| `resources\views\design-system\index.blade.php` | `Caption` |
| `resources\views\design-system\index.blade.php` | `Design Tokens` |
| `resources\views\design-system\index.blade.php` | `Core design tokens` |
| `resources\views\design-system\index.blade.php` | `Components` |
| `resources\views\design-system\index.blade.php` | `Buttons` |
| `resources\views\design-system\index.blade.php` | `Inputs` |
| `resources\views\design-system\index.blade.php` | `Cards` |
