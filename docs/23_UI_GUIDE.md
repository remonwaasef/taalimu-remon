# 23_UI_GUIDE - UI Design Tokens & Component Library

- **Design System Tokens (`resources/css/design-tokens.css`)**: Central source of truth (Brand Identity Sheet v1.0) — defines brand (`--color-primary` #5B5FEF Taalimu Indigo, hover #4548C7, soft #EEF0FF), neutrals (`--color-bg-main` #F8FAFC Taalimu Snow, `--color-white` #FFFFFF, `--color-text-main` #111827, `--color-text-secondary` #475569, `--color-text-muted` #94A3B8, `--color-border` #E2E8F0), semantic status tokens (success #16A34A, warning #D97706, error #DC2626, info #0284C7), layout (bg/surface/sidebar/header/border/divider), typography scale (`--font-size-*`), shadows (`--shadow-*`), radius (`--radius-md: 10px`, `--radius-xl: 16px`, `--radius-2xl: 20px`), and dark-mode overrides in `.dark`.
- **HSL Theme Layer (`resources/css/tailwind.css`)**: shadcn-compatible HSL variables (`--border`, `--background`, `--foreground`, `--card`, `--sidebar-*`, `--divider`…) aligned with the design tokens. Includes typography utility classes (`.text-display`, `.text-heading-*`, `.text-body-*`, `.text-caption`, `.text-label`) and `.focus-ring`.
- **Tailwind Palette (`tailwind.config.js`)**: `brand.*` scale, semantic colors (`success`, `warning`, `info`, `destructive`), container scales, and micro-animations.
- **Isolated Bootstrap Layer (`resources/css/bootstrap-compat.css`)**: Encapsulates legacy Bootstrap classes to maintain 100% backward compatibility while migrating views.
- **Unified Blade UI Library (`resources/views/components/ui/`)**:
  - Buttons (`button.blade.php`), Form Fields (`form-field.blade.php`), Inputs (`input.blade.php`), Selects (`select.blade.php`), Textareas (`textarea.blade.php`)
  - Alerts (`alert.blade.php`), Toasts (`toast.blade.php`), Tabs (`tabs.blade.php`), Tooltips (`tooltip.blade.php`), Progress (`progress.blade.php`), Drawers (`drawer.blade.php`)
  - Modals (`modal.blade.php`), Cards (`card.blade.php`), Tables (`table.blade.php`), Empty States (`empty-state.blade.php`), Badges (`badge.blade.php`), Avatars (`avatar.blade.php`), Page Headers (`page-header.blade.php`), Skeletons (`skeleton-loader.blade.php`), Stats Cards (`stats-card.blade.php`).
- **Typography & Font Enforcement**:
  - Arabic interfaces MUST resolve to `Cairo` (`font-cairo` or default sans) as specified in `.agents/DESIGN_SYSTEM.md`. Never hardcode Latin fonts (`font-inter`) on layout body or wrapper divs in Arabic views.
  - Directional indicators and BiDi numbers must be enclosed with `<bdi>` or `dir="ltr"` where appropriate to prevent sign inversions.
- **Full Documentation**:
  - `docs/design-system/DESIGN_TOKENS_REFERENCE.md` — Tokens reference
  - `docs/design-system/COMPONENT_GUIDE.md` — Component catalog with examples
  - `.agents/DESIGN_SYSTEM.md` — Permanent design reference
