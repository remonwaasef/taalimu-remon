# 23_UI_GUIDE - UI Design Tokens & Component Library

- **Design System Tokens (`resources/css/design-tokens.css`)**: Central source of truth — defines brand (`--color-primary` #168F7C, hover #0D7465, soft #E8F5F1), neutrals (`--color-ink` #102033, `--color-navy` #0D1A2B, `--color-muted` #65717F, `--color-cream` #FBFAF6, `--color-border` #E5ECE9), semantic status tokens (success, warning, danger, info), layout (bg/surface/sidebar/header/border/divider), typography scale (`--font-size-*`), shadows (`--shadow-*`), radius (`--radius-*`), and dark-mode overrides in `.dark`.
- **HSL Theme Layer (`resources/css/tailwind.css`)**: shadcn-compatible HSL variables (`--border`, `--background`, `--foreground`, `--card`, `--sidebar-*`, `--divider`…) aligned with the design tokens. Includes typography utility classes (`.text-display`, `.text-heading-*`, `.text-body-*`, `.text-caption`, `.text-label`) and `.focus-ring`.
- **Tailwind Palette (`tailwind.config.js`)**: `brand.*` scale, semantic colors (`success`, `warning`, `info`, `destructive`), container scales, and micro-animations.
- **Isolated Bootstrap Layer (`resources/css/bootstrap-compat.css`)**: Encapsulates legacy Bootstrap classes to maintain 100% backward compatibility while migrating views.
- **Unified Blade UI Library (`resources/views/components/ui/`)**:
  - Buttons (`button.blade.php`), Form Fields (`form-field.blade.php`), Inputs (`input.blade.php`), Selects (`select.blade.php`), Textareas (`textarea.blade.php`)
  - Alerts (`alert.blade.php`), Toasts (`toast.blade.php`), Tabs (`tabs.blade.php`), Tooltips (`tooltip.blade.php`), Progress (`progress.blade.php`), Drawers (`drawer.blade.php`)
  - Modals (`modal.blade.php`), Cards (`card.blade.php`), Tables (`table.blade.php`), Empty States (`empty-state.blade.php`), Badges (`badge.blade.php`), Avatars (`avatar.blade.php`), Page Headers (`page-header.blade.php`), Skeletons (`skeleton-loader.blade.php`).
- **Full Documentation**:
  - `docs/design-system/DESIGN_TOKENS_REFERENCE.md` — Tokens reference
  - `docs/design-system/COMPONENT_GUIDE.md` — Component catalog with examples
  - `.agents/DESIGN_SYSTEM.md` — Permanent design reference
