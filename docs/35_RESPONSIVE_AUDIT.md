# Taalimu Responsive Audit — Phase 1+2 Report

Date: 2026-08-17 · Scope: full repository (Blade + Alpine + Tailwind 3.4 + Bootstrap 5 legacy). No code changed during audit.

## Fix Status (updated 2026-08-17)

| # | Status | Note |
|---|---|---|
| P0-1 | ✅ FIXED | subscriptions/index: removed `overflow:visible !important`, replaced dead `toggleCustomDropdown` (undefined in app-next chain → menu never opened) with real Bootstrap `data-bs-toggle` dropdown + `data-bs-boundary="viewport"` |
| P0-2 | ✅ FIXED (opt-in) | Card-mode tables: JS+CSS shared system (`[data-mobile-cards]`) — labels injected from thead, rows → stacked cards ≤768px; adopted on **68 direct sites + all 16 `x-ui.table` dashboards via component** — every list/detail table with a `thead` in Center/Admin/Instructor/Campus/Parent + shared admin pages |
| P0-3 | ✅ FIXED | tailwind.config: added `*.jsx` glob |
| P1-4 | ✅ DONE (by design) | T1/T3/T5 (Bootstrap, custom, x-ui.table) → card-mode system; T2 legacy + T6 print (statement/receipt/info-table) stay print-oriented (correct behavior); T7 import preview excluded (JS re-renders rows); single remaining no-thead table documented (`operation_issues/show`) |
| P1-6 | ⏳ PHASE 5 | Bootstrap modal scroll regions + z-index hacks — deferred (Bootstrap already scrolls dialogs; risky to add scroll containers over Select2/datepickers) |
| P1-7–19 | ✅ FIXED | (see Phase 3-4 rows above) |
| P1-20 ⭐ | ✅ FIXED | Pagination unified: `Paginator::defaultView('components.ui.pagination')` (26 sites → 1 view), prev/next/ellipsis classes, mobile collapse to prev/current/next ≤480px |
| P1-21 | ✅ FIXED | Delete confirmations unified: 21 inline `confirm()` → shared `data-confirm-delete` (Swal) system across 19 views (Admin/Center/Instructor) |
| P0-ENC ⚠️ | ✅ FIXED | Double-encoded UTF-8 Arabic (CP1252 mojibake) in 18 views — garbled text was served to users on Admin auth/login+index, Center students/sales/activity_logs, Instructor groups/students, Parent auth/index, auth funnel (register, login-portal, payment-demo, complete-google-registration, plan-modal, step2). Byte-level 2-pass fix; repo-wide scan now zero. See CHANGELOG |
| P1-13 | ✅ FULLY DONE | All remaining `text-[9px]`/`text-[10px]` (78 occurrences) → `text-[11px]` across Modules + shared components; bundle rebuilt |
| P1-5 | ✅ FIXED | Charts: clamp() fluid heights (analytics/index ×4, students ×2, attendance ×1) |
| P1-6 | ⏳ PHASE 5 | Bootstrap modal scroll regions + z-index hacks — deferred (Bootstrap already scrolls dialogs; risky to add scroll containers over Select2/datepickers) |
| P1-7 | ✅ FIXED | Subdomain inputs ×3: responsive paddings + `https://` prefix hidden on mobile |
| P1-8 | ✅ FIXED | Plan-modal billing toggle: flex-wrap + min-h-40 + responsive padding (both partial & google copy) |
| P1-9 | ✅ FIXED | Landing pricing toggle: flex-wrap, max-width, compact at ≤480px |
| P1-10 | ✅ FIXED | Hero pillars: flex-wrap + dividers hidden ≤768px |
| P1-11 | ✅ FIXED | aria-labels: hamburger (+aria-expanded/aria-controls), plan-modal closes ×2 |
| P1-12 | ✅ FIXED | Billing toggles touch height: min-h-[40px] (step2 + google) |
| P1-13 | ✅ FIXED | Micro-type: text-[9px]/[10px] → 11px (register, step1/2, plan-modal, google-reg, registration-success, module logins ×3) |
| P1-14 | ✅ FIXED | Cookie-consent: inset-inline-start/end (RTL), min-h buttons, flex-wrap |
| P1-15 | ✅ FIXED | navbar.blade.php malformed Blade block removed (latent crash) |
| P1-16 | ✅ FIXED | dropdown.blade.php: width map + `80`/`96`, viewport cap `max-w-[calc(100vw-2rem)]` |
| P1-17 | ✅ FIXED | Container: full screens map (max 1440px) + responsive padding |
| P1-18 | ✅ FIXED | dvh: sidebar h-dvh + max-h calc(100dvh-8rem), module login shells (Admin dir fix too), auth pages min-h-dvh w/ vh fallback |
| P1-19 | ✅ FIXED | Landing mobile menu: body scroll lock (x-effect), Esc, aria, max-h scroll |
| P2 grids | ⏳ DEFERRED | Unprefixed grid-cols-2/3 — audited as functional at 320; revisit in QA sweep |
| P2 auth | ✅ | payment-demo + testimonials logical properties (border-s/ms) |
| P2 drawer | ✅ | app-next drawer: role=dialog/aria-modal/aria-label + close on resize ≥1024px |
| P2 modal/table | ✅ | x-ui.modal: role=dialog/aria-modal/aria-label/body-scroll-lock/close aria; x-ui.table: role=region/aria-label/tabindex |

## Stack Reality

- **97% Blade + Alpine + Tailwind** — React/Inertia powers only 2 side-channel routes (`/inertia-demo`, `/s/{identifier}` signed student portal). Primary UI is Blade.
- Modules: Center (139 views), Admin (40), Instructor (32), Campus (8), Parent (8). Shared `resources/views` (80).
- Layouts: `app-next` (active shell, ~100+ pages), `landing-new` (marketing + auth), `auth-minimal` (4 pages), `hope-master` (DEAD chain — no page extends it).

## Global Counts

- `<table>` tags: 77 → 7 distinct implementations; `table-responsive` wrappers: 69; mobile card/list alternative: **0**
- Bootstrap modals: 169 raw divs (52 `modal-dialog`) vs shared `x-ui.modal`: **1** usage; internal scroll regions: **0**
- Pagination: 3 implementations / 30 sites (26 default Bootstrap-5 via `Paginator::useBootstrapFive()`, 4 named view, 1 unused component)
- Charts: 8 canvases, ALL fixed-height containers
- `100vh`/`h-screen`: zero `dvh` anywhere in repo
- `text-[9px]`/`text-[10px]`: ~40 occurrences (auth/register funnel)

## P0 — Blocking

| # | Location | Problem |
|---|---|---|
| 1 | `Modules/Admin/resources/views/subscriptions/index.blade.php:114,278-280` | `.table-responsive { overflow: visible !important }` kills mobile horizontal scroll; 5 dense columns crash at 375px |
| 2 | Systemic (~66 tables) | No mobile card/stacked alternative — all tables rely solely on horizontal scroll; 6-7 column pages (sales, students, online_classes, tenants) are effectively unusable at 375px |
| 3 | `tailwind.config.js:11` | Content globs miss `*.jsx` → StudentPortal (LIVE signed-URL route) and DemoDashboard render unstyled |

## P1 — High

| # | Location | Problem |
|---|---|---|
| 4 | 7 table implementations | T1 Bootstrap ×57, T2 legacy ×6, T3 custom ×2, T4 Tailwind ×3, T5 `x-ui.table` ×16, T6 print ×3, T7 import ×1 — consolidate on `x-ui.table` |
| 5 | `Center/analytics/index:139,153,175,189`, `analytics/students:93,107`, `analytics/attendance:22`, `Admin/tenants/show:429` | Fixed-height chart containers (320/250/300px) — pancaked on mobile |
| 6 | Modals | 169 raw Bootstrap vs 1 shared; no `max-height` scroll region anywhere; z-index hacks (`students/index:553`) |
| 7 | `auth/complete-google-registration:498-513`, `register:154-170`, `_register-step1:18-33` | Subdomain input: 180-195px fixed horizontal padding → ~45-68px usable at 320px; `.taalimu.com` suffix overlaps text |
| 8 | `auth/_register-plan-modal:40-58`, `complete-google-registration:663-682` | Billing toggle inline-flex ~260px inside 224px modal content at 320px → overflow; can't wrap |
| 9 | `landing/pricing.blade.php:76-95` | Pricing toggle ~300px > 288px available at 320px → page-level horizontal scroll (no body guard) |
| 10 | `landing/hero.blade.php:166-185` | Pillars row `display:flex; gap:2rem` no `flex-wrap` → overflows <340px |
| 11 | `landing/header.blade.php:74`, `_register-plan-modal:35`, `complete-google-registration:659` | Icon-only buttons without aria-label (hamburger, modal closes) |
| 12 | `_register-step2:189-205`, `complete-google-registration:429-445` | Billing toggles ~27px tall (py-1.5) — below 40px touch target |
| 13 | ~40 spots (register funnel, google reg, registration-success) | `text-[9px]`/`text-[10px]` micro-typography — unreadable, worse in Arabic |
| 14 | `components/cookie-consent.blade.php` | `position:fixed; left:1.5rem`, no right edge, no media query, physical left on RTL; buttons 32px |
| 15 | `components/ui/navbar.blade.php:155-169` | Malformed Blade (orphaned `</x-slot>`/`</x-ui.dropdown>` with no opener) — latent hard crash; dead duplicate of inline navbar |
| 16 | `app-next:203`, `navbar:85`, `dropdown.blade.php:14-20` | Notification panel fixed `w-80` clips left edge on ≤360px; width map `48/56/64/auto` — `width="80"` falls through to `w-48` (squished) |
| 17 | `tailwind.config.js:17-23` | Container only capped at `2xl:1280px` → full-bleed 1024-1535px; flat `2rem` padding at all sizes |
| 18 | `components/ui/sidebar.blade.php:9,29` + ~6 more | `h-screen`/`calc(100vh-8rem)` — iOS chrome clips sidebar nav bottom |
| 19 | `landing/header.blade.php:82-101` | Mobile menu opens without body scroll lock; no aria-expanded |

## P2 — Medium

- Unprefixed small grids (cramped 320-374px): `Parent/index:66`, `Instructor/index:127`, `courses/{create,edit}:132`, `Admin/index:117`, `complete-google-registration:579`, `_register-step2:254`, `onboarding/wizard:66,337`
- App drawer a11y: no `role="dialog"`, no focus trap, no close-on-resize
- `dark-mode-global.css` 587-line `!important` war vs design-tokens
- Delete-confirm inconsistency: ~40 files mixing native `confirm()` / Swal / `data-confirm-delete` (shared helper already in `taalimu-global.js` — underused)
- Tab styles: 3 divergent systems
- Stat cards: Bootstrap `col-md-3` hand-rolled ×4 pages vs `x-ui.stats-card` ×29
- Pagination: 3 parallel implementations
- Inline `<style>` per page: 30+ files
- Sidebar width duplicated (`w-64` class + `16rem` motion.css + hope-ui `--sidebar-width`)
- `min-h-screen` on all auth shells → should be `min-h-dvh`
- `@yield('sidebar')` rendered twice in app-next (harmless, duplicated Alpine roots)
- Admin/Center/Parent module login pages: labels without `for`, show/hide `text-[9px]`, Admin hardcodes `dir="rtl"` (breaks en locale)

## P3 — Polish

- Dead `hope-master` chain + orphaned legacy assets loaded by app-next (hope-ui.css 439KB etc. — KEEP while modules still use Bootstrap classes)
- `x-ui.chart-card` (0 uses), `x-ui.skeleton-loader` (0 uses), `x-ui.search`/`x-ui.filter`/`x-ui.pagination` Bootstrap-class components
- `onboarding/wizard` 660-line one-off
- Landing `w-[500px]`/`w-[400px]` decorative orbs (contained)
- `offline.css` body 100vh
- Footer social links `href="#"`

## Recommended Execution Order

1. Phase 3 (Design System): tailwind content+container, dvh, chart heights, modal scroll CSS, touch-targets, micro-type floor
2. Phase 4 (Global layout): dropdown width/a11y, bell panel width, drawer a11y, landing header scroll lock, cookie-consent, sidebar dvh
3. Phase 5 (Shared): `x-ui.table` responsive upgrade + adoption seed; pagination unification; confirm-delete standardization
4. Auth funnel P1s (subdomain, plan modal, toggles, type scale)
5. Landing P1s (pricing toggle, hero pillars, a11y labels)
6. P0 subscriptions fix; charts fluid; tests + build; QA sweep at 320/375/768/1024/1440/1920
