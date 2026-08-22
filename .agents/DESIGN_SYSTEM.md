# Taalimu Design System Reference

> This document is the permanent design reference for the Taalimu platform.
> All colors, typography, spacing, and component standards are defined here.
> Detailed documentation: `docs/design-system/DESIGN_TOKENS_REFERENCE.md` & `docs/design-system/COMPONENT_GUIDE.md`.

---

## Brand Colors

| Token | Value | Usage |
|---|---|---|
| Primary 500 | `#2E8B83` | Main brand color |
| Primary 600 | `#25746D` | Hover states |
| Primary 700 | `#1E5E58` | Active/pressed states |
| Primary Light | `#E6F4F3` | Light backgrounds |
| Primary Border | `#B2DDD9` | Light borders |

### Secondary Colors

| Token | Value |
|---|---|
| Blue | `#4F7DF3` |
| Soft Blue | `#69B7C8` |

---

## Light Theme

| Token | Value |
|---|---|
| Background | `#F6F8FA` |
| Surface | `#FFFFFF` |
| Card | `#FFFFFF` |
| Sidebar | `#FFFFFF` |
| Header | `#FFFFFF` |
| Border | `#E7ECEF` |
| Divider | `#EEF2F6` |

### Typography (Light)

| Token | Value |
|---|---|
| Primary Text | `#1F2937` |
| Secondary Text | `#6B7280` |
| Muted | `#9CA3AF` |
| Disabled | `#D1D5DB` |
| Inverse | `#FFFFFF` |

---

## Dark Theme

| Token | Value |
|---|---|
| Background | `#0F1720` |
| Surface | `#17202B` |
| Card | `#1D2935` |
| Sidebar | `#121A24` |
| Header | `#17202B` |
| Border | `#2B3644` |
| Divider | `#313D4C` |

### Typography (Dark)

| Token | Value |
|---|---|
| Primary Text | `#F9FAFB` |
| Secondary Text | `#CBD5E1` |
| Muted | `#94A3B8` |

---

## Status Colors

| Status | Value | Light Tint | Border |
|---|---|---|---|
| Success | `#22C55E` | `#F0FDF4` | `#BBF7D0` |
| Warning | `#F59E0B` | `#FFFBEB` | `#FDE68A` |
| Error / Danger | `#EF4444` | `#FEF2F2` | `#FECACA` |
| Info | `#3B82F6` | `#EFF6FF` | `#BFDBFE` |

---

## Chart Colors

| Token | Value |
|---|---|
| Primary | `#2E8B83` |
| Secondary | `#4F7DF3` |
| Neutral | `#CBD5E1` |
| Accent | `#69B7C8` |
| Danger | `#EF4444` |
| Warning | `#F59E0B` |

---

## Typography

- **Arabic** → Cairo (Google Fonts)
- **English** → Inter (Google Fonts)
- Large spacing, excellent readability

---

## Spacing (4px / 8px Grid)

`4 | 8 | 12 | 16 | 20 | 24 | 32 | 40 | 48 | 64 | 96 | 128`

---

## Border Radius

| Element | Value |
|---|---|
| Buttons | `12px` (`--radius-lg`) |
| Cards | `16px` (`--radius-xl`) |
| Inputs | `12px` (`--radius-lg`) |
| Dialogs | `20px` (`--radius-2xl`) |
| Badges | `999px` (`--radius-full`) |

---

## Shadows

- X-Small: `shadow-xs` (`0 1px 2px rgba(15, 23, 42, 0.04)`)
- Small: `shadow-sm`
- Medium: `shadow-md`
- Large: `shadow-lg`
- X-Large: `shadow-xl`

Never use heavy shadows without purpose.

---

## Icons

- Library: `Font Awesome 6` (current) / `lucide-react` (future React pages)
- Style: Outline only
- Default Size: `20px`

---

## Color Philosophy

- **90% Neutral** colors
- **8% Brand** colors
- **2% Status** colors

Whitespace is part of the design. Avoid colorful interfaces.

---

## UI Component Library (`resources/views/components/ui/`)

| Component | Tag | Description |
|---|---|---|
| **Button** | `<x-ui.button>` | 9 variants, 6 sizes, loading state, link mode |
| **Form Field** | `<x-ui.form-field>` | Unified wrapper with label, helper, errors |
| **Input** | `<x-ui.input>` | Text input with icons, sizes, error states |
| **Select** | `<x-ui.select>` | Custom select dropdown matching input tokens |
| **Textarea** | `<x-ui.textarea>` | Multi-line textarea |
| **Alert** | `<x-ui.alert>` | Status banners (success, error, warning, info) |
| **Toast** | `<x-ui.toast>` | Non-blocking notifications container |
| **Tabs** | `<x-ui.tabs>` | Interactive tabs with badges |
| **Tooltip** | `<x-ui.tooltip>` | Micro-tooltips for actions |
| **Progress** | `<x-ui.progress>` | Progress bars with variants |
| **Drawer** | `<x-ui.drawer>` | Slide-over side panel |
| **Card** | `<x-ui.card>` | Collapsible, loading states, variants |
| **Table** | `<x-ui.table>` | Responsive card-mode, stickyHeader, empty states |
| **Empty State** | `<x-ui.empty-state>` | Multi-size empty placeholder |
| **Modal** | `<x-ui.modal>` | Standard and destructive dialogs |
| **Badge** | `<x-ui.badge>` | Status pills, removable tags, dot indicators |
| **Avatar** | `<x-ui.avatar>` | Initials/photo avatars with status dots |

---

## Implementation Files

| File | Purpose |
|---|---|
| `resources/css/design-tokens.css` | CSS custom properties (source of truth) |
| `resources/css/bootstrap-compat.css` | Isolated legacy Bootstrap layer |
| `resources/css/global-components.css` | Global component styles |
| `resources/css/tailwind.css` | Tailwind base + HSL variables + Typography |
| `tailwind.config.js` | Tailwind brand palette & theme extensions |
| `docs/design-system/DESIGN_TOKENS_REFERENCE.md` | Detailed token documentation |
| `docs/design-system/COMPONENT_GUIDE.md` | Blade component guide |

---

*Last updated: 2026-08-22*
