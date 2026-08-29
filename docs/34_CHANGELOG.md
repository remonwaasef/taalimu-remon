# 34_CHANGELOG - Application Release History

All notable changes to the Taalimu.com platform will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Added & Optimized
- **Complete Taalimu Landing Page Restructuring & Conversion Optimization (2026-08-29)**:
  - **Outcome-Driven Hero Section**: Rebuilt hero with headline `"ودّع الدفاتر وExcel ومتابعة أولياء الأمور يدويًا"`, subheadline, 30-day free trial primary CTA, demo modal trigger, and trust badges (`resources/views/landing/partials/hero.blade.php`).
  - **Single Student Lifecycle Story (Aha Moment)**: Elevated the core workflow into a prominent visual timeline (`Scan QR → Attendance -> Balance Update -> Parent WhatsApp`) (`qr-whatsapp-flow.blade.php`).
  - **Audience Choice Segmentation**: Added explicit dual audience switcher for Independent Tutors (مدرس مستقل) vs Educational Center Owners (صاحب مركز) with targeted registration parameters (`audience.blade.php`).
  - **Enhanced WhatsApp Value Positioning**: Re-framed WhatsApp notifications around `"ولي الأمر يعرف قبل أن يسألك"` featuring realistic alert triggers (`whatsapp-notifications.blade.php`).
  - **Condensed 6-Feature Bento Grid**: Streamlined features grid to 6 focused cards (`feature-bento.blade.php`).
  - **Removed Product Showcase Tabbed Section**: Removed `@include('landing.partials.showcase')` and `#product` navbar references per explicit request to further streamline the page length and focus.

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
