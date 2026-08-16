# 23_UI_GUIDE - UI Design Tokens & Component Library

- **Design System Tokens (`resources/css/design-tokens.css`)**: Central source of truth — defines brand (`--color-primary` #2E8B83, hover #25746D, active #1E5E58, light #E6F4F3), secondary (`--color-blue` #4F7DF3, `--color-soft-blue` #69B7C8), status, layout (bg/surface/sidebar/header/border/divider), text hierarchy (main/secondary/muted/disabled/inverse), chart colors (`--chart-*`), button/card/input/badge/dialog tokens, plus dark-mode overrides in `.dark`.
- **HSL Theme Layer (`resources/css/tailwind.css`)**: shadcn-compatible HSL variables (`--border`, `--background`, `--foreground`, `--card`, `--sidebar-*`, `--divider`…) aligned with the same hex values for light + dark.
- **Tailwind Palette (`tailwind.config.js`)**: `brand.*` scale (500 = #2E8B83), `divider`, `blue`, `soft-blue`, `text-muted`, `text-disabled`.
- **Reusable Blade Components**: Modal overlay (`components/ui/modal.blade.php`), Dropdown menu (`components/ui/dropdown.blade.php`), Badge, Avatar, Stats Card (count-up + stagger), Empty State, Command Palette, Breadcrumb, Navbar (bell swing), Sidebar.
- **Premium Motion System**: `resources/css/motion.css` — motion tokens (`--motion-*`, `--ease-*`) in `design-tokens.css`; classes `.motion-reveal(-sm)`, `.motion-stagger`, `.motion-page`, `.bell-swing`/`.bell-active`/`.badge-pop`, `.check-pop`, `.flash-row`, `.status-change`, `.field-error-enter`, `.command-list`, `.alert-enter`. Full `prefers-reduced-motion` support. Trigger: `window.Taalimu.notify(message, type)`.
- **PWA Offline Screen**: Offline fallback view (`resources/views/offline.blade.php`).
- **مرجع التصميم الكامل**: `.agents/DESIGN_SYSTEM.md` — يقرأ قبل أي تعديل على الواجهة.
