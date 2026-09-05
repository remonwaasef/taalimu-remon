# 34_CHANGELOG - Application Release History

All notable changes to the Taalimu.com platform will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Added
- **Center Dashboard Complete Visual Redesign & Operational Hierarchy (2026-09-06)**:
  - Redesigned `Modules/Center/resources/views/index.blade.php` to transform the center dashboard into an executive command center.
  - Replaced hardcoded `font-inter` in `resources/views/layouts/app-next.blade.php` with dynamic locale-based typography (`font-cairo` for Arabic, `font-inter` for English/Latin), resolving system font degradation and disjointed Arabic text rendering.
  - Upgraded KPI metrics grid into 4 high-value cards: Active Students with enrollment trend, Today's Scheduled Sessions (`sessionsToday`), Weekly Attendance Rate (`attendanceRate`), and Monthly Revenue with contextual overdue warnings (`overdueAmount`).
  - Replaced 4 massive blank quick-action tiles with a sleek, compact Smart Quick Actions Dock with micro-interactions, modal triggers, and zero wasted vertical space.
  - Upgraded split operational lists: Recent Student Registrations now features 1-click WhatsApp messaging buttons, status pills, and relative timestamps; Active Groups features enrolled count chips and teacher avatars.
  - Replaced hardcoded English `View All` text with localized `center::dashboard.view_all` (`عرض الكل`) and directionally correct RTL arrow indicators.
  - Enhanced `x-ui.stats-card` component to support proper RTL BiDi text wrapping and font inheritance.

- **Status & Financial Balance Badge Organization in Students Directory (2026-09-05)**:
  - Redesigned the "الحالة" column into "الحالة والموقف المالي" (`status_and_balance`) in `Modules/Center/resources/views/students/index.blade.php`.
  - Disentangled academic account status (`نشط` / `متوقف`) from financial dues, presenting each as distinct visual badges.
  - Converted raw red text balances into interactive financial pills (`متبقي: X ج.م`) with direct Quick Pay trigger on click, and clean `خالص` badges for zero-balance students.
  - Added full trilingual localization support in Arabic, English, and French.
- **Visual Distinction for Suspended vs Active Courses (2026-09-05)**:
  - Added dedicated amber badges, pulsing indicators, and explicit "معطل" tags for suspended courses in the main students directory table (`index.blade.php`).
  - Redesigned suspended course cards in the edit view (`edit.blade.php`) with prominent corner badges, warm amber tint, warning notice, and bold green "تنشيط الكورس" CTA button.
  - Updated student profile courses tab (`_show-tab-courses.blade.php`) with contextual status styling.
- **Student Course Suspension & Unenrollment Management (2026-09-05)**:
  - Added migration `2026_09_05_194500_add_suspended_to_enrollments_status_enum.php` extending `enrollments.status` enum with `'suspended'`.
  - Added route `PATCH center.students.courses.toggle-status` and `StudentController@toggleCourseStatus` to toggle student course enrollment status between active and suspended.
  - Added route `DELETE center.students.courses.unenroll` and `StudentController@unenrollCourse` with SweetAlert2 confirmation to remove student course enrollment safely.
  - Updated enrolled courses cards in `Modules/Center/resources/views/students/edit.blade.php` with dynamic status badges (`active` green vs `suspended` amber with animation) and direct action buttons (pause/resume & unenroll).
  - Added trilingual localization in Arabic, English, and French (`center::students`).
- **Professional Enrolled Courses Column in Students Directory Table (2026-09-05)**:
  - Added modern, responsive enrolled courses column in the main students table (`Modules/Center/resources/views/students/index.blade.php`).
  - Displays compact, styled chips with course title, active status indicator, and direct link to course details.
  - Added a smart dropdown popover for students enrolled in more than 2 courses displaying all courses with teacher names.
  - Added an interactive empty state quick-action chip (`+ تسجيل في كورس`) to register unenrolled students with a single click.
  - Eager loaded `enrollments.course.instructor` in `StudentController@index` to avoid N+1 queries.
  - Integrated course titles into smart instant search for real-time filtering by course name.
- **Professional Enrolled Courses Display in Student Edit Screen (2026-09-05)**:
  - Added dedicated modern courses & groups section in the student edit screen (`Modules/Center/resources/views/students/edit.blade.php`).
  - Displays rich course cards with subject badge, course title, instructor, active status badge, progress percentage & bar, enrollment date, price, and direct link to course details.
  - Added quick course enrollment modal right inside the edit view to register the student into new courses instantly with one click, while disabling already enrolled courses.
- **Smart Dynamic WhatsApp Message on Quick Action (2026-09-05)**:
  - Enabled context-aware automatic pre-filled WhatsApp message on the quick action button next to Quick Pay.
  - Generates polite debt reminder with outstanding balance and center name when `total_balance > 0`, and general check-in/support message when balance is clear (`total_balance <= 0`).
  - Added full trilingual localization support (AR, EN, FR).

### Fixed
- **Student Edit Quick Course Modal Empty List Resolution (2026-09-05)**:
  - Removed restrictive `where('status', 'active')` filter from `StudentController@edit` method and unified it with `Course::orderBy('title')->get()`, ensuring all published center courses are populated in the selection dropdown.
  - Added real-time all-courses-enrolled detection on modal opening in the edit view.
- **Student Edit Page Quick Enroll Button Activation (2026-09-05)**:
  - Converted `@section('scripts')` to `@push('scripts')` in `edit.blade.php` so that modal opening handlers and validation scripts are properly rendered into `@stack('scripts')` of the layout.
  - Added direct Alpine `@click` dispatch to the quick enrollment buttons.
- **Student Edit Page 500 Error Resolution (2026-09-05)**:
  - Fixed `BadMethodCallException: Call to undefined method App\Models\Course::forTenant()` by scoping courses via `Course::where('tenant_id', ...)` and adding universal `scopeForTenant` to `BelongsToTenant` trait.
  - Added null safety checks for enrollment date formatting in student edit view.
- **Student Quick Course Enrollment - Disabled State for Enrolled Courses (2026-09-05)**:
  - Added HTML `disabled` attribute to courses in which the student is already enrolled, preventing re-selection.
  - Added visual dimming and clear single status suffix ` - (مسجل بالفعل)`.
  - Added missing localization key `quick_enroll_desc_short` across AR/EN/FR.
  - Added automatic detection and notification when a student is already enrolled in all available courses.
- **Student Quick Course Enrollment Modal Activation (2026-09-05)**:
  - Fixed non-responsive "Enroll in Course" (`+`) quick action button in students list.
  - Eliminated DOMContentLoaded null element access errors caused by Alpine modal `<template>` encapsulation.
  - Replaced faulty Select2 initialization inside teleported modal with robust native responsive selector.
  - Added direct and delegated modal launcher `openQuickEnrollModal` and form submit handler `handleEnrollSubmit`.
- **Student Quick Action WhatsApp Direct Launch (2026-09-05)**:
  - Fixed student table action WhatsApp button (next to Quick Pay) to launch WhatsApp Web directly on desktop (`web.whatsapp.com/send?phone=...`) bypassing the `api.whatsapp.com` interstitial landing page ("Share on WhatsApp").
  - Added smart cross-platform device detection (`openDirectWhatsApp`) for both student table action bar and student profile view.
- **Student WhatsApp Credentials Security & Direct Desktop Launch (2026-09-05)**:
  - Eliminated plaintext password exposure in public browser GET URLs (`api.whatsapp.com/send?text=...`) across Student Registration Cards and Password Tickets.
  - Introduced cryptographically signed temporary Magic Login Links (`center.login.magic` valid for 24h) for WhatsApp messages, allowing students to access their accounts and set passwords without URL credential leakage.
  - Implemented direct WhatsApp Web launching (`web.whatsapp.com/send`) on desktop to bypass the intermediate `api.whatsapp.com` landing page ("Continue to WhatsApp Web").
  - Added smart action dropdown on WhatsApp buttons (Direct Web, Desktop App protocol, Copy Magic Link, and Safe Clipboard Copy).

### Changed
- **Student Edit Form Streamlined to Match Create Form (2026-09-05)**:
  - Removed legacy, redundant fields from the edit student form (`Modules/Center/resources/views/students/edit.blade.php`) to align exactly with the clean creation form: removed national ID, birth date, gender, address, profile photo, emergency phone, parent job, parent relation, school name, and section type.
  - Eliminated synthetic email display (`stdX.domain@taalimu.com`) in the edit email input so users only see and edit authentic email addresses.
  - Displayed student code as a non-intrusive header badge rather than a large readonly input field.
  - Updated `StudentProfileService::updateStudent` to safely preserve existing legacy database values when omitted from the streamlined form.
- **Student Registration & Password Reset Ticket UX Cleanup (2026-09-05)**:
  - Removed internal synthetic placeholder email (`stdX.domain@taalimu.com`) from student registration and password reset cards, showing email only when an authentic custom email exists.
  - Eliminated redundant "إرسال إيميل" (Send Email) button targeting fake addresses.
  - Replaced over-complicated 4-option WhatsApp dropdown with a streamlined 2-button action layout:
    - 🟢 Direct WhatsApp Send (`web.whatsapp.com` on PC, native app on mobile).
    - 📋 Copy All Data (direct copy to clipboard with toast notification).
  - Streamlined WhatsApp notification text to contain only essential login credentials (Name, Phone Number, Password, Platform URL).
- **Unified Login Screen Complete Overhaul (2026-09-05)**:
  - Transformed the unified login (`taalimu.com/login`) into a modern 2-column SaaS split layout with rich branding, value propositions, and interactive features.
  - Added Taalimu brand mark, platform badges, key feature highlights, and customer trust proof on the presentation panel.
  - Fixed corrupted placeholder encoding (`ç€â€...`) on the password field and added interactive show/hide password toggle.
  - Integrated "Remember Me" checkbox, "Forgot Password" link (`route('password.request')`), Google SSO button, and clear free-trial registration link.
  - Refined floating dark mode toggle with backdrop blur and responsive behavior.
  - Added missing French auth translations for full trilingual localization compliance (AR/EN/FR).
- **Streamlined Data Entry & Launchpad Quick Modals (2026-09-04)**:
  - Added lightweight 1-click Quick Modals for adding Instructors, Courses, and Students directly from the Launchpad without page navigation.
  - Implemented progressive disclosure on full Instructor and Course creation forms, grouping secondary/optional fields inside modern collapsible `<details>` sections to eliminate visual overwhelm.
  - Allowed courses to be created with flexible schedules (optional at creation time, preventing blocking validation).
  - Enhanced controller JSON responses for seamless asynchronous AJAX modal submissions.
  - Made instructor commission rate and type mandatory with clear UX guidance and default values in both Quick Modal and full creation form.

### Fixed
- **Actions Dropdown Double-Toggle Fix (2026-09-04)**:
  - Removed `bs-compat.js` import from [app.js](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/resources/js/app.js) to eliminate duplicate Bootstrap event listeners.
  - Root cause: `libs.min.js` (real Bootstrap 5) and `bs-compat.js` (custom shim) both registered `data-bs-toggle="dropdown"` click handlers, causing dropdowns to open and immediately close (double-toggle).
  - Fix affects ALL pages: Students, Instructors, Courses, and any view using Bootstrap dropdowns.

- **Button Contrast, Disappearance on Hover, and Legacy Banner Overrides Fix (2026-09-04)**:
  - Removed obsolete white-on-white header banner CSS overrides in [taalimu-unified.css](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/public/assets/hope-ui/css/taalimu-unified.css) that caused `.btn-outline-secondary` and titles to blend into the light background.
  - Defined all missing button tokens in `:root` (`--color-secondary`, `--btn-light-*`, `--btn-secondary-*`, etc.) in `design-tokens.css` and `taalimu-unified.css`.
  - Added rock-solid high-contrast background and hover states for `.btn-light`, `.btn-secondary`, and all `.btn-outline-*` variants so they never disappear or turn white on transparent on hover.
  - Upgraded [instructors/create.blade.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/Modules/Center/resources/views/instructors/create.blade.php) and [instructors/edit.blade.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/Modules/Center/resources/views/instructors/edit.blade.php) to use `<x-ui.page-header>` and `<x-ui.button>`.

- **Clean Center Initialization & Unified Login Response Fix (2026-09-04)**:
  - Removed automatic demo data provisioning from [RegistrationController.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/app/Http/Controllers/RegistrationController.php) so that newly registered centers start 100% clean with zero mock students, courses, or instructors.
  - Fixed 500 error in [UnifiedAuthController.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/app/Http/Controllers/UnifiedAuthController.php) caused by calling undefined `response()->setContent()`, switching to standard `response()`.
  - Handled optional phone number in [TenantRegistrationService.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/app/Services/TenantRegistrationService.php) with fallback null-coalescing operators.

- **Ultra-Clean White Premium Canvas (#FAFAFA) with Layered Luxury Shadows (2026-08-30)**:
  - Upgraded dashboard body background from slate-mist `#EEF2F6` to ultra-clean white `#FAFAFA` for a pristine, Apple/Notion-grade aesthetic.
  - Introduced multi-layered luxury shadow system (`shadow-xs` → `shadow-xl`) with soft negative offsets for floating card depth.
  - Updated border token from `#E2E8F0` to crisp `#EAEFF2` across `design-tokens.css`, `tailwind.css`, `x-ui.card`, and `x-ui.table`.
  - Framed Students Management interface inside a luxury main card container with bordered search inputs, modernized filter tabs, and separated table header.
  - Re-framed Attendance and Schedule screens inside structured cards with clear headers.
  - Restructured Student Creation Form into distinct, framed sub-sections with sharp bordered inputs.

- **Migrate Billing Collection Modal to `<x-ui.modal>` (2026-08-30)**:
  - Replaced legacy Bootstrap modal in [sales/account.blade.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/Modules/Center/resources/views/sales/account.blade.php) with Alpine-based `<x-ui.modal>`.
  - Fixed issue where invisible fixed modal wrapper was intercepting page clicks and making buttons/inputs unresponsive.
  - Bound custom event `open-modal` to seamlessly populate student balance and name instantly upon click.

- **On-The-Fly Quick Entity Creation (Instructors & Classrooms) (2026-08-30)**:
  - Added seamless inline Quick Creator buttons (`+ إضافة معلم جديد`, `+ إضافة قاعة جديدة`) across all Course and Schedule creation/edit forms.
  - Implemented [_quick-classroom-modal.blade.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/Modules/Center/resources/views/partials/_quick-classroom-modal.blade.php) and enhanced [_quick-instructor-modal.blade.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/Modules/Center/resources/views/partials/_quick-instructor-modal.blade.php) with instant AJAX submission.
  - Newly created instructors and classrooms are immediately added and auto-selected in dropdowns without losing page state or filled form data.

- **Strict Relational Prerequisites & Mandatory Foreign Dependencies (2026-08-30)**:
  - Enforced strict `required` validation on `course_id`, `classroom_id`, `day_of_week`, `start_time`, and `end_time` in [ScheduleController.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/Modules/Center/app/Http/Controllers/ScheduleController.php) and [StoreCourseRequest.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/app/Http/Requests/Center/StoreCourseRequest.php), preventing orphaned or incomplete records.
  - Added smart prerequisite guidance banner on [schedules/create.blade.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/Modules/Center/resources/views/schedules/create.blade.php) when courses or classrooms are missing.

- **Automatic Course Instructor Binding in Schedule Forms (2026-08-30)**:
  - When choosing a course in [schedules/create.blade.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/Modules/Center/resources/views/schedules/create.blade.php) or [schedules/edit.blade.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/Modules/Center/resources/views/schedules/edit.blade.php), the assigned instructor is automatically selected via real-time JavaScript binding.
  - In `ScheduleController@store` and `@update`, if `instructor_id` is omitted, the course's designated instructor is automatically assigned as fallback.

- **Strict 1-Session Per Course Per Day & Centralized Schedule Sync (2026-08-30)**:
  - Enforced strict business rule in `ScheduleController::getConflictError()` forbidding more than 1 schedule session per course on the same day.
  - Added day-level deduplication in `CourseService::syncSchedules()`.
  - Improved weekly schedule board query to show full weekly schedule grouped accurately by day and start time without pagination truncation.

- **Smart 1-Tap QR Attendance & Anti-Fraud Device Lock (2026-08-30)**:
  - Implemented persistent remember-session on mobile devices for seamless one-tap attendance on future QR scans without re-entering credentials.
  - Added anti-fraud Device Lock (`taalimu_dev_lock`) binding each physical phone to a single student profile, preventing proxies and attendance fraud.
  - Enhanced attendance success view with student name, course title, and live check-in timestamps.

- **Fixed 403 Forbidden on Mobile QR Scan Form Submission (2026-08-30)**:
  - Fixed HMAC signature mismatch when submitting the scan-login form to a distinct POST endpoint.
  - Configured `center.attendance.markByQr` to handle both `GET` and `POST` directly on `request()->fullUrl()`, ensuring the signed URL signature passes with 100% validity.

- **Instant Student Phone/Code Verification on Mobile QR Scan (2026-08-30)**:
  - Enabled quick attendance verification by registered **Phone Number** or **Student Code** without requiring passwords in physical classrooms.
  - Provided tab switching between quick phone verification and full email/password authentication.
  - Inlined standalone responsive CSS with Cairo/Inter typography, completely eliminating mobile styling glitches.

- **Fixed 500 Error on QR Attendance Scan Page (2026-08-30)**:
  - Resolved fatal `Call to a member function can() on null` when unauthenticated students scanned QR codes on mobile.
  - Previously, `scan-login.blade.php` and `success.blade.php` inherited the center admin layout (`_sidebar-next`), which assumed an authenticated admin user.
  - Migrated both views to standalone `layouts.auth-minimal` with a responsive mobile-first login and confirmation interface.

- **Redesigned Interactive QR Code Attendance Screen & Projector Mode (2026-08-30)**:
  - Transformed the static QR page into an interactive live presentation center (`Modules/Center/resources/views/attendance/qr.blade.php`).
  - Added dynamic 60s countdown timer with an animated progress bar and seamless client-side AJAX refresh without page reloads.
  - Added Fullscreen Projector Mode (`وضع العرض للبروجيكتور والشاشات الذكية`) for crystal-clear classroom smart board presentation.
  - Added Real-time Live Attendance Counter & Feed polling every 5s with audio scan chimes and recent attendee avatars.
  - Added quick copy link and manual refresh controls.

- **Fixed Application Timezone and Accurate Session Status Calculation (2026-08-30)**:
  - Corrected `APP_TIMEZONE` in `.env` and `config/app.php` from `UTC` to `Africa/Cairo` (matching local time UTC+3).
  - Previously, `now()` was 3 hours behind local time in UTC, causing past morning sessions (e.g. 10:00 AM - 11:00 AM) to be erroneously calculated as upcoming.
  - Updated `IdentifyTenant` middleware and views to parse session times with explicit timezone awareness.

- **Fixed Manual Attendance Action Buttons Hover Contrast (2026-08-30)**:
  - Replaced ambiguous outline button styles on "حاضر / متأخر / غائب" with dedicated high-contrast solid and soft states.
  - On hover, buttons smoothly transition with clear colored backgrounds and white text, completely eliminating the white-on-white text disappearance.
  - Enhanced Attendance Sheet page layout with crisp card borders, structured headers, and clean typography.

- **Enhanced Today's Attendance Sessions with Live, Upcoming & Ended Statuses (2026-08-30)**:
  - Added live status indicators distinguishing between Live Now (🟢 جارية الآن with glowing pulse), Upcoming (🔵 قادمة), and Ended (⚪ منتهية).
  - Added quick filter tabs (الكل, الجارية الآن, القادمة, المنتهية) to immediately switch between session states.
  - Fixed time display directionality (LTR) preventing reversed clock text (`PM 01:00 - 02:00 PM`).
  - Tailored action buttons per session state (e.g. view absentees for ended sessions, highlighted take attendance for live sessions).

- **Fixed Student Creation Form Submission & Phone Verification (2026-08-30)**:
  - Center `StudentController::checkPhone()` was returning `{exists: bool}` but JS expected `{status: 'exists'|'available'}` — fixed response format with tenant isolation and student name.
  - Fixed course selection checkboxes where container click events conflicted with `<label for>` clicks causing double-toggle and validation failure.
  - Added `defaultPrevented` check to global submit listeners and status indicators so validation failures do not freeze the submit button on "جاري التنفيذ...".
  - Added pre-step validation on Wizard Next button and auto-reset safety timeout for form buttons.
- **Aligned All Dashboards & UI Components with Taalimu Brand Green Identity (2026-08-30)**:
  - Fixed blue/indigo color dominance across Center, Admin, and Instructor dashboards.
  - Updated Launchpad onboarding banner (`Modules/Center/resources/views/partials/launchpad.blade.php`), progress bars, active step badges, buttons, and setup modals to use the official Taalimu green palette (`#168F7C`, `brand-primary`, `brand-50`, `brand-600`).
  - Updated Stats Cards, Quick Action cards, and table links in `Modules/Center/resources/views/index.blade.php`.
  - Updated global `x-ui.stats-card` component to display positive trends in emerald/brand green rather than indigo.
  - Replaced legacy indigo colors in `student-profile-card`, `class-card`, `teacher-card`, `recent-activity-card`, and `class-progress-card`.
  - Rebuilt assets via Vite successfully.

- **Resolved 500 Server Error on Login and Register Pages (2026-08-29)**:
  - Replaced strict `@include` statements with safe `@includeIf` for missing header, sticky CTA, and footer partials in `resources/views/layouts/landing-new.blade.php`.
  - Fixed 500 Server Error occurring when accessing `/login` (`login.portal`) and `/register` (`register`).

### Added & Optimized
- **Adopted Taalimu Brand Identity & UI Color System v1.0 (2026-08-29)**:
  - Updated global design tokens in `resources/css/design-tokens.css` with official palette: Primary Green (`#168F7C`), Primary Dark (`#0D7465`), Primary Soft (`#E8F5F1`), Ink (`#102033`), Navy (`#0D1A2B`), Muted (`#65717F`), Cream (`#FBFAF6`), and Border (`#E5ECE9`).
  - Synchronized `.agents/DESIGN_SYSTEM.md`, `docs/design-system/DESIGN_TOKENS_REFERENCE.md`, and `docs/23_UI_GUIDE.md` to reflect Brand Identity v1.0 guidelines.
- **Replaced Landing Page with Dedicated Clean Arabic Design (2026-08-29)**:
  - Imported and integrated new landing page (`resources/views/landing.blade.php`, `resources/css/landing.css`, `resources/js/landing.js`).
  - Integrated dynamic route actions (`login.portal`, `register`, `home`, `privacy`, `terms`, `cookies`).
  - Removed outdated landing components in `resources/views/landing/` and updated `LandingController.php` and `vite.config.js`.

- **Complete Taalimu Landing Page Restructuring & Conversion Optimization (2026-08-29)**:
  - **Outcome-Driven Hero Section**: Rebuilt hero with headline `"ودّع الدفاتر وExcel ومتابعة أولياء الأمور يدويًا"`, subheadline, 30-day free trial primary CTA, demo modal trigger, and trust badges (`resources/views/landing/partials/hero.blade.php`).
  - **Single Student Lifecycle Story (Aha Moment)**: Elevated the core workflow into a prominent visual timeline (`Scan QR → Attendance -> Balance Update -> Parent WhatsApp`) (`qr-whatsapp-flow.blade.php`).
  - **Audience Choice Segmentation**: Added explicit dual audience switcher for Independent Tutors (مدرس مستقل) vs Educational Center Owners (صاحب مركز) with targeted registration parameters (`audience.blade.php`).
  - **Enhanced WhatsApp Value Positioning**: Re-framed WhatsApp notifications around `"ولي الأمر يعرف قبل أن يسألك"` featuring realistic alert triggers (`whatsapp-notifications.blade.php`).
  - **Condensed 6-Feature Bento Grid**: Streamlined features grid to 6 focused cards (`feature-bento.blade.php`).
  - **Landing Page CRO Fine-Tuning & 5 Strategic Fixes**:
    - **Standardized Trial Duration**: Standardized 30-day free trial across all sections (Hero, ROI Calculator, FAQ, Demo Modal, Pricing).
    - **Sharpened Hero Copy**: Refined subheadline: `"Taalimu تجمع طلابك وحضورك وأموالك وWhatsApp في نظام واحد. سجّل الطلاب بـ QR، سجّل الحضور في ثوانٍ، واطمئن أولياء الأمور تلقائيًا."`
    - **Refined Copy Claims**: Replaced absolute time claims (`15-20 min`) with realistic phrases (`تضييع وقت الحصة في تسجيل الحضور يدويًا`).
    - **Localized SaaS Terminology**: Replaced generic POS/Tenant Isolation terms with `"المالية والتحصيل"` and `"عزل كامل وحماية لبيانات كل مركز"`.
    - **Added Early Access Social Proof**: Added `🚀 برنامج الانضمام المبكر` banner in the proof bar to establish early-stage credibility.

- **Enhanced Landing Page UI/UX, Brand Identity & Interactive Conversions (2026-08-28)**:
  - Added interactive **Smart ROI & Time Savings Calculator** (`resources/views/landing/partials/roi-calculator.blade.php`) calculating operational hours and recovered leakages based on real-time student count slider.
  - Added interactive **Demo Walkthrough Video Modal** (`resources/views/layouts/landing-new.blade.php`) triggered seamlessly by the Hero secondary CTA.
  - Added **FAQ Schema JSON-LD Structured Data** for enhanced Google search rich results and SEO ranking.
  - Added **Sample Excel Template Download Action** in the Excel migration section.
  - Optimized typography & web fonts (clean `Cairo` & `Inter` imports) and aligned brand tokens (`#2E8B83`).
  - Synchronized trilingual translations (`ar`, `en`, `fr`) across all new landing page components.
- **Rebuilt Taalimu Ultimate Premium SaaS Landing Page (2026-08-24)**:
  - Re-architected landing page with Arabic-first, RTL-first modern SaaS aesthetics in `resources/views/landing/new.blade.php`.
  - Added dedicated **QR Code Registration Spotlight** section (`qr-registration.blade.php`) demonstrating instant student ID generation, smartphone scanner, and real-time profile lookup.
  - Added dedicated **Meta Cloud API WhatsApp Notifications Spotlight** section (`whatsapp-notifications.blade.php`) featuring attendance alerts, digital payment receipts, due debt reminders, and OTP verification messages.
  - Added interactive **Master Story Timeline: "من أول Scan... إلى أول Notification"** (`qr-whatsapp-flow.blade.php`) with a 6-step lifecycle workflow.
  - Added dedicated **4-Audience Portals Showcase** (`audience.blade.php`) for Teacher, Educational Center, Student, and Parent roles.
  - Added central **Ecosystem Architecture Diagram** (`platform-visual.blade.php`) showing connected stakeholders.
  - Added interactive **Product Showcase Tabs** (`product-showcase.blade.php`) displaying real UI screens for Center Dashboard, QR Scanner, WhatsApp Log, POS Invoicing, and Parent Portal.
  - Added modern **Bento Grid Feature Overview** (`feature-bento.blade.php`), 4-Step Onboarding (`how-it-works.blade.php`), Trust & Security Pillars (`trust.blade.php`), and accessible FAQ Accordion (`faq.blade.php`).
  - Synchronized full trilingual translations across Arabic (`resources/lang/ar/landing.php`), English (`resources/lang/en/landing.php`), and French (`resources/lang/fr/landing.php`).

- **Resolved HTTP 500 Server Error on Main Landing Page (`taalimu.com`) (2026-08-24)**:
  - Fixed invalid translation key references (`landing.problem.*` -> `landing.pain_points.*`) in landing page partials.
  - Replaced broken partial includes in `landing/new.blade.php` with verified, fully-translated partials (`pain-points`, `outcome`, `whatsapp-killer`, `payments-attendance`, `excel-migration`, `product-showcase`, `how-it-works`, `trust`, `pricing`, `faq`, `cta`).
  - Added null coalescence and array-safety checks across `product-showcase.blade.php`, `how-it-works.blade.php`, `trust.blade.php`, `faq.blade.php`, `footer.blade.php`, and `components/ui/input.blade.php` for seamless multi-language compatibility (`ar`, `en`, `fr`).

### Added
- **Taalimu Premium Design System Architecture (2026-08-22)**:
  - **Design Tokens (`resources/css/design-tokens.css`)**: Expanded with full semantic scales for brand, status (success, warning, error, info with hover/light/border/contrast), surface & layout colors, spacing scale (4px/8px grid), border radius scale, shadows scale (xs to xl), and comprehensive dark mode overrides.
  - **Base Component Library (`resources/views/components/ui/`)**:
    - `form-field.blade.php`: Unified wrapper with label, helper text, error messages, and required indicator.
    - `input.blade.php`: Styled text input supporting icons, sizes, focus rings, error states, and dark mode.
    - `select.blade.php`: Styled dropdown matching token scales.
    - `textarea.blade.php`: Resizable multi-line text input with token styling.
    - `alert.blade.php`: 4 semantic status banners with icons, dismiss transitions, and dark mode.
    - `toast.blade.php`: Lightweight, non-blocking Alpine.js toast notifications container.
    - `tabs.blade.php`: Accessible tab navigation with badges and Alpine.js state.
    - `tooltip.blade.php`: Micro-interaction tooltips for buttons and actions.
    - `progress.blade.php`: Progress bar with percentage calculation and status variants.
    - `drawer.blade.php`: Slide-over side panel with RTL animation support.
    - Enhanced `button.blade.php`, `card.blade.php`, `table.blade.php`, `empty-state.blade.php`, `modal.blade.php`, and `badge.blade.php`.
  - **CSS Layer Cleanup & Isolation**:
    - Created `resources/css/bootstrap-compat.css` to isolate legacy Bootstrap utility classes.
    - Cleaned `resources/css/global-components.css` by replacing hardcoded hex values with CSS variables and removing duplicate button rules.
    - Updated `tailwind.config.js` and `resources/css/tailwind.css` with standard typography utilities and focus-ring classes.
  - **Layout Accessibility**: Added WCAG skip-to-content link and Alpine toast listener in `layouts/app-next.blade.php`.
  - **Documentation**: Created `docs/design-system/DESIGN_TOKENS_REFERENCE.md` and `docs/design-system/COMPONENT_GUIDE.md`, and updated `.agents/DESIGN_SYSTEM.md` and `docs/23_UI_GUIDE.md`.

### Fixed
- **Launchpad Education System Setup Modal**: Replaced the Bootstrap nested modal inside [launchpad.blade.php](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/Modules/Center/resources/views/partials/launchpad.blade.php) with the standardized `<x-ui.modal>` Alpine.js component. This resolves a UI freeze where the Bootstrap backdrop rendered over the modal due to parent stacking context containment (`.motion-page` / `.launchpad-card`), restoring full interactivity to select academic templates and trigger generation.

### Added
- **Security Hardening (2026-08-17 audit round)**: 
  - Password reset tokens are now scoped per tenant (`password_reset_tokens.tenant_id`, migration `2026_08_17_000001`); `ForgotPasswordController`/`ResetPasswordController` resolve the owning tenant and never list reset links for foreign accounts.
  - `Gate::before` now restricts `super_admin` / `center_admin` / `center_owner` role bypasses to the user's own tenant context (or a global account) — a center admin from tenant A can no longer pass every authorization path inside tenant B.
  - Removed the mock `SaleController::checkoutSuccess` endpoint + checkout view that marked invoices fully paid without any gateway verification; real accounting is webhook-driven (Paymob HMAC).
  - `FinanceService::addPayment` rejects payments exceeding the remaining balance (`ValidationException`); `PayoutService` commission queries use `lockForUpdate()` inside the transaction to prevent double settling.
  - `SendWhatsAppNotification` / `SendTelegramNotification` now retry transient provider failures with backoff (tries=3) and alert ops when the channel stays down; sync mode (tests) degrades softly.
  - `composer`-style backup config: backup notification email & archive password now come from env (`BACKUP_NOTIFICATION_EMAIL`, `BACKUP_ARCHIVE_PASSWORD`); `verify_backup` enabled; `backup:monitor` scheduled at 02:00.
  - Most-active queue channels wired into workers: `docker-compose.yml` worker and the KVM supervisor stanza consume `high,whatsapp,notifications,gamification,default`.
  - DB port no longer published to the host in `docker-compose.yml` (mariadb is internal-only).

### Added
- **Premium Motion System**: New `resources/css/motion.css` defines the unified motion language — speed tokens (instant 75ms → slow 500ms) and easing tokens (`--ease-out/-in/-in-out/-spring`) in `design-tokens.css`, plus reusable classes: `.motion-reveal(-sm)`, `.motion-stagger` (40ms cascade, max 8), `.motion-page`, `.bell-swing` (spec pendulum curve), `.bell-active`, `.badge-pop`, `.check-pop`, `.flash-row`, `.status-change`, `.field-error-enter`, `.command-list > *`, `.alert-enter`. Full `prefers-reduced-motion: reduce` support zeroing all decorative animation while keeping state transitions. CSS-only, transform/opacity only — zero new dependencies, zero JS cost to page loads.
- **Notification Bell Upgrade**: Bell (in `layouts/app-next.blade.php` and `components/ui/navbar.blade.php`) now swings physically on `new-notification` (self-stopping via `animationend`), glows subtle teal while active, pops the unread badge, and stops on click. New `window.Taalimu.notify(message, type)` helper drives bell + toast + badge from any backend code.
- **Dashboard Entrance & Count-Up**: KPI grids on Admin/Instructor/Parent/Campus/Center dashboards + students page now use `.motion-stagger`; `x-ui.stats-card` gains a count-up animation (550ms ease-out cubic, preserves formatting/separators, skips non-numeric values, respects reduced-motion, always settles on the exact final number).
- **Page Transitions**: Main content in `layouts/app-next.blade.php` fades + rises 5px (300ms) on load; Inertia progress bar switched from indigo to brand teal `#2E8B83`.
- **Mobile Sidebar Polish**: `app-next` sidebar now slides via Alpine transitions, closes on `Escape`, locks body scroll while open, and the toggle exposes `aria-expanded`/labels; desktop uses a separate static render (no duplicated behavior regressions).
- **Table/Row Feedback**: `x-ui.table` row highlight pattern — new students flash brand-tint 1.6s after creation (`flash-row` + `highlight_student` session, added in `StudentController::store`). Payment status badges (reports/payments) cross-fade via `.status-change`.
- **Micro-Feedback Polish**: `.alert-enter` entrance + `.check-pop` success icon on all flash alerts; empty states enter subtly; command palette items cascade in (20ms steps); modal tuned to 250ms enter / 150ms exit per motion spec.
- **Design Tokens**: Motion tokens block added to `resources/css/design-tokens.css` (`--motion-*`, `--ease-*`); `.agents/DESIGN_SYSTEM.md` gained a full Motion System section documenting tokens, classes, and rules.
- **Desktop Sidebar Collapse**: `app-next` layout gains a desktop collapse toggle (navbar bars button, rotates 180°) — the sidebar shell transitions 16rem→5rem (300ms ease-in-out), nav labels and group headings fade/collapse CSS-only, brand wordmark hides while the logo stays centered, sub-menus remain accessible when re-expanded. Pure CSS in `motion.css`, no layout JS.
- **Fix — Bell 500 on Google login**: `tenant_route('center.notifications.read')` was called with a scalar notification id while the helper signature requires `array $parameters` → PHP `TypeError` → 500 on any dashboard render with unread notifications (surfaced via the Google sign-in path with an existing instructor account; unit tests never hit it because test users have no notifications). Now wrapped as `[$notification->id]` in both `app-next` and shared `navbar`.

## Responsive Master Sweep (2026-08-17)

- **Audit**: `docs/35_RESPONSIVE_AUDIT.md` — full repository audit (P0/P1/P2/P3 inventory, 4 parallel passes: layouts/CSS/breakpoints, auth+marketing, modules pages, shared components).
- **Design System (Phase 3)**: tailwind.config content globs now include `*.jsx` (StudentPortal/DemoDashboard were rendering unstyled); container fixed — proper screens map (max 1440px) + responsive padding; charts fluid (`clamp(240px, 30vw, 320px)` etc.) across Center analytics ×7; `dvh` units with `vh` fallback on auth shells + sidebar (`h-dvh`, `max-h-[calc(100dvh-8rem)]`).
- **Global layout (Phase 4)**: `x-ui.dropdown` width map gained `72/80/96` (notification panel was silently falling back to `w-48`) + viewport cap `max-w-[calc(100vw-2rem)]`; app-next drawer got `role="dialog"`/`aria-modal`/`aria-label` + auto-close on resize ≥1024px; landing mobile menu now locks body scroll, closes on Esc, has aria-expanded + scrollable max-h; cookie banner switched to logical properties (RTL) with touch-sized buttons; x-ui.modal gained dialog roles + body scroll lock + labeled close; x-ui.table gained role="region"/aria-label/tabindex.
- **Auth funnel (P1)**: subdomain inputs (register/step1/google) — responsive paddings + `https://` prefix hidden on mobile; plan-modal billing toggles wrap + 40px touch targets (both copies); billing cycle toggles min-h-[40px]; micro-typography floor raised 9-10px → 11px across 8 auth files; module logins — Admin `dir` now follows locale (was hardcoded rtl), min-h-dvh shells.
- **Landing (P1)**: pricing toggle wraps + compact ≤480px; hero pillars wrap with dividers hidden ≤768px; testimonials/payment-demo logical properties.
- **P0 fix**: `Admin/subscriptions/index` — removed `overflow:visible !important` hack; the custom dropdown (`toggleCustomDropdown`) was UNDEFINED in the app-next chain (actions menu never opened on the new UI) → replaced with native Bootstrap `data-bs-toggle` dropdown + `data-bs-boundary="viewport"`; horizontal scroll restored.
- **Hygiene**: removed malformed orphaned Blade block in `x-ui.navbar` (latent hard crash).

## Phase 5 — Shared Components (2026-08-17)

- **Card-mode tables (P0-2)**: shared system — `taalimu-global.js` §11 injects thead labels onto every cell (`data-label`), CSS in `global-components.css` turns rows into stacked cards ≤768px (flex label/value, RTL-safe, dark-mode aware, `td-actions`/`td-full` classes, dropdown menu width cap). Opt-in via `data-mobile-cards` — adopted on **68 direct sites + all 16 `x-ui.table` dashboards via the component itself** (analytics suite, assets, assignments, attendance, branches, classrooms, courses, instructors, leaderboard, quizzes, roles, sales, students tabs, tickets, users, admin roles/subscriptions/tenants/coupons, instructor students, campus, parent profile/finances/attendance, shared bug-reports/consent-report). Print pages (statements/receipts) and the JS-rendered import preview are excluded by design. No business logic touched.
- **Pagination unified**: `Paginator::defaultView('components.ui.pagination')` replaces `useBootstrapFive()` — all 26 `links()` sites now render the shared view (aria-labels, `page-prev/page-next/page-ellipsis` classes); ≤480px collapses to prev/current/next only.
- **Delete confirmations unified (P1-21)**: replaced 21 inline `onsubmit/onclick confirm()` across 19 views with the shared `data-confirm-delete` system (Swal + fallback `confirm()`); includes Admin (users, backups, subscriptions, roles, coupons, system-features, plans), Center (users, classrooms, assets, branches, launchpad, curriculum, quizzes, schedules, settings, online_classes, questions), Instructor (schedules, online_classes, settings). All buttons verified to reference existing form ids; duplicate-id bug in launchpad fixed; byte-safe conversion preserved UTF-8 Arabic.
- **⚠️ P0 — Double-encoded Arabic fixed in 18 views**: Arabic literals were stored as UTF-8-of-CP1252-mojibake (e.g. `ØªØ³Ø¬ÙŠÙ„…` bytes), so browsers displayed garbled text on critical pages: Admin (auth/login, index, backups, tenants, users), Center (students, sales, activity_logs), Instructor (groups, students), Parent (auth/login, index), shared auth (register, login-portal, payment-demo, complete-google-registration, _register-plan-modal, _register-step2). Two-pass byte-level de-encoding (Latin-1 continuation pass + CP1252 specials pass: quotes, hyphens, ✓ `…`, `ˆ`, `Š`, `€`, `™` …); verified zero mojibake sequences remain repo-wide (blade + php + lang json), all files still valid UTF-8, backups in `%TEMP%\opencode\mojibake-backup`. No middleware re-encodes, so the garbled text was definitely served to users.
- **Micro-type floor (P1-13 completion)**: replaced the remaining 78 `text-[9px]`/`text-[10px]` occurrences (wizard ×30, sidebars ×9, app-next, navbar, command-palette, stats-card, avatar, campus module, auth fragments…) → `text-[11px]`; bundle regenerated. Zero sub-11px type remains in Blade.
- **Hygiene**: removed redundant `.modal/.modal-backdrop` z-index !important hack in `students/index` (duplicated Bootstrap 5.3 defaults from hope-ui.css — zero behavioral change).
- **Landing polish (Phase 9)**: testimonial orbs now clamp on mobile (`max-w-[90vw]`/`max-h-[60vw]`, 400px orb 70vw/50vw) — desktop unchanged; `offline.css` gets `100dvh` fallback. Footer social `href="#"` left untouched — contact/social pages not created yet (user-deferred).
- **Real Notification Data in the Bell**: Bell dropdowns (`app-next` + shared `navbar`) now render real Laravel notifications — unread count badge (numeric, pops on arrival), latest 5 items with icon/title/message/time, unread highlight tint, unread dot, "Mark all as read" (guarded by `Route::has`), "View all" link on tenant-bound pages, proper empty state. One gentle bell swing per browser session when unread > 0 (`sessionStorage` gate); `aria-label` includes the count.
- **Master System Prompt**: Permanent quality/vision charter added as `.agents/MASTER_PROMPT.md` (roles, mission, quality, security, accessibility, performance standards) with the tech stack calibrated to the real project (Blade + Alpine + Inertia React, Tailwind 3.4 + Bootstrap 5.3); now a mandatory entry point in `.agents/AGENTS.md`.
- **Design System Token Alignment**: `resources/css/design-tokens.css` now fully matches the design system spec — added sidebar/header surfaces (#FFFFFF light, #121A24/#17202B dark), divider (#EEF2F6/#313D4C), text hierarchy (secondary #6B7280, muted #9CA3AF, disabled #D1D5DB, inverse), focus-ring, chart palette (`--chart-*`), dialog radius 20px; separated Blue #4F7DF3 / Soft Blue #69B7C8 as independent tokens (replacing the mislabeled `--color-accent-hover`). `tailwind.config.js` gained `brand.500`, `divider`, `blue`, `soft-blue`, `text-muted`, `text-disabled`. `resources/css/tailwind.css` HSL layer aligned to the spec hex values (light + dark) including `--divider`. `docs/23_UI_GUIDE.md` refreshed to reflect the real token architecture.
- **Image Upload WebP Auto-Compression**: `HandlesFileUploads` now transparently converts raster uploads (JPEG, PNG, WebP, non-animated GIF) to WebP at upload time via GD (`imagewebp`) or Imagick, preserving PNG alpha channels (palette images promoted to truecolor). Animated GIFs are detected (NETSCAPE2.0 block) and kept as-is; the original is kept whenever the WebP is not smaller (`keep_smaller_only`); every failure (missing converter, undecodable source, oversized bitmap > 8MB) falls back to the original bytes with a warning log. All five upload consumers (student/course/instructor/expense images, settings logos) benefit without changes. Configurable via new `config/uploads.php` (`UPLOAD_WEBP_ENABLED`, `UPLOAD_WEBP_QUALITY`). Covered by `tests/Unit/Traits/HandlesFileUploadsTest.php` (7 tests).
- **Role Permission Granularity UI (sub-roles)**: Tenant admins can now create granular sub-roles (e.g. junior accountant, reception secretary, cashier) from one-click preset templates (`RolePresetService`) mirroring the core `secretary` / `accountant` / `staff` permission sets. The permission matrix shown to tenants is now strictly center-scope (`PermissionService::getTenantGroupedPermissions` hides the `centers`/`tenants` system groups, so nothing is silently dropped on save), with a live permission search filter and selected-count badge on create/edit. Role list cache is invalidated on every role CRUD (`RoleRepository::clearCache`). System-role name protection and `safePermissions()` hardening now share a single source of truth (`PermissionService::SYSTEM_GROUPS`). Covered by `tests/Feature/RoleGranularityTest.php` (5 tests).
- **Enterprise Database Scaling Evaluation**: Live schema audit (51 tenant tables, ~56 FK tables, MariaDB 10.4 local / MariaDB prod). Verdict: partitioning by `tenant_id` rejected (unique-key rule, FK ban on partitioned tables pre-MariaDB 10.6, rebuild cost, partition-count ceiling). Recorded as ADR-004 with a phased plan in `docs/37_DATABASE_SCALING.md`.
- **Database monitoring**: New `db:monitor-sizes` command reports table sizes to Telegram weekly (Sunday 07:00) with growth thresholds (1M rows / 512 MB) to drive scaling decisions.
- **Full-Text Search (Laravel Scout + Meilisearch)**: `SearchService` performs tenant-isolated, ranked searches over `Student` and `Course` (both now `Searchable` with `tenant_id` in their indexed payloads). Wired into the async student picker (`center.students.search`) and the grid queries (`StudentQuery`/`CourseQuery`). Falls back to safe LIKE matching when the engine is unreachable; local dev uses the `database` Scout driver.
- **Redis Caching & Session Integration**: Cache, Session, and Queue drivers switched to Redis with dedicated databases (queue=0, cache=1, session=2) via `REDIS_DB`, `REDIS_CACHE_DB`, `REDIS_SESSION_DB`.
- **Graceful Redis Fallback**: `AppServiceProvider::configureResilientCaching()` pings Redis at boot and automatically falls back to database drivers when unreachable, logging a warning once per process.
- **`predis/predis` (^3.5)**: Installed as a pure-PHP Redis client for environments without the phpredis extension (local dev); production keeps `REDIS_CLIENT=phpredis`.
- **Security Hardening**: Overhauled packages to patched versions — `dompdf/dompdf` (3.1.6) and `guzzlehttp/guzzle` (7.15.x) — resolving CVE-2026-59941/2/3, CVE-2026-56722, and guzzle advisories; `composer audit` is now clean.
- Created [SESSION_START.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/SESSION_START.md) and [MASTER_CONTEXT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/MASTER_CONTEXT.md) aliases pointing to [00_AI_BOOT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/00_AI_BOOT.md) and [01_MASTER_CONTEXT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/01_MASTER_CONTEXT.md).
- Integrated Task Classification Matrix (UI, Feature, DB, Controller, Route, Auth, Permissions, API, Performance, Bug Fix) into [00_AI_BOOT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/00_AI_BOOT.md) and [README_AI.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/README_AI.md).
- Consolidated Clean Documentation System ([DOCUMENTATION_CLEANUP_REPORT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/DOCUMENTATION_CLEANUP_REPORT.md) & [DOCUMENTATION_INDEX.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/DOCUMENTATION_INDEX.md)).
- Complete AI-First Numbered Knowledge Operating System inside `docs/` (`00_AI_BOOT.md` through `35_KNOWN_LIMITATIONS.md`).
- Project root entry point manifests ([PROJECT_MANIFEST.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/PROJECT_MANIFEST.md) & [README_AI.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/README_AI.md)).
- Quality assurance and release guides ([29_CODE_REVIEW.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/29_CODE_REVIEW.md), [30_TESTING_GUIDE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/30_TESTING_GUIDE.md), [31_DEPLOYMENT_GUIDE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/31_DEPLOYMENT_GUIDE.md), [32_RELEASE_CHECKLIST.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/32_RELEASE_CHECKLIST.md)).

---

## [1.0.0] - 2026-07-28

### Added
- Initial production release of Taalimu.com SaaS Educational Platform.
- Multi-tenancy architecture with single database `tenant_id` partitioning.
- 6 Modular monolith domains: `Admin`, `Center`, `Instructor`, `Campus`, `Tenancy`, `Api`.
- Payment gateway integrations for PayPal, Paymob, and local cash billing.
- WhatsApp parent alert system and Telegram automated reporting bot.
- Realtime WebSockets server support using Laravel Reverb.
- Automated system exception triage system (`OperationIssue`).
