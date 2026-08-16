# Taalimu Design System Reference

> This document is the permanent design reference for the Taalimu platform.
> All colors, typography, spacing, and component standards are defined here.

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

| Status | Value |
|---|---|
| Success | `#22C55E` |
| Warning | `#F59E0B` |
| Error | `#EF4444` |
| Info | `#3B82F6` |

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

## Spacing (8px Grid)

`4 | 8 | 12 | 16 | 24 | 32 | 40 | 48 | 64 | 96 | 128`

---

## Border Radius

| Element | Value |
|---|---|
| Buttons | `12px` |
| Cards | `16px` |
| Inputs | `12px` |
| Dialogs | `20px` |
| Badges | `999px` |

---

## Shadows

- Small: `shadow-sm`
- Medium: `shadow-md`
- Large: `shadow-lg`

Never use heavy shadows.

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

## Motion System

Reference: `resources/css/motion.css` + tokens in `resources/css/design-tokens.css`.

### Speed Tokens

| Token | Value | Use |
|---|---|---|
| `--motion-instant` | 75ms | button press, micro feedback |
| `--motion-fast` | 125ms | hover, icon changes |
| `--motion-standard` | 200ms | dropdowns, tooltips, popovers |
| `--motion-medium` | 300ms | modals, page entrance, cards, sidebar |
| `--motion-slow` | 500ms | notifications, success states |

### Easing Tokens

| Token | Value | Use |
|---|---|---|
| `--ease-out` | `cubic-bezier(0.16,1,0.3,1)` | entrances |
| `--ease-in` | `cubic-bezier(0.4,0,1,1)` | exits |
| `--ease-in-out` | `cubic-bezier(0.4,0,0.2,1)` | reversible interactions |
| `--ease-spring` | `cubic-bezier(0.34,1.3,0.64,1)` | physical effects (bell) |

### Reusable Classes

| Class | Purpose |
|---|---|
| `.motion-reveal` / `.motion-reveal-sm` | entrance (8px / 5px rise) |
| `.motion-stagger` | children cascade 40ms (max 8) |
| `.motion-page` | full-page-load transition |
| `.bell-swing` / `.bell-active` / `.badge-pop` | notification bell + badge |
| `.check-pop` | success icon settle |
| `.flash-row` | table row change highlight |
| `.status-change` | badge color cross-fade |
| `.field-error-enter` | validation message entrance |
| `.command-list > *` | palette item cascade |
| `.alert-enter` | flash alert entrance |

### Rules

- Animate only `transform` + `opacity` — never width/height/margin.
- Every animation must communicate state, feedback or hierarchy.
- Never animate hundreds of table rows — only changed rows.
- `prefers-reduced-motion: reduce` is fully supported (motion removed, states stay instant).
- Trigger pattern: `window.Taalimu.notify(message, type)` plays bell + toast + badge.

---

## Implementation Files

| File | Purpose |
|---|---|
| `resources/css/design-tokens.css` | CSS custom properties (source of truth) |
| `resources/css/global-components.css` | Global component styles |
| `resources/css/tailwind.css` | Tailwind base + HSL variables |
| `tailwind.config.js` | Tailwind brand palette |
| `public/assets/hope-ui/css/taalimu-unified.css` | Hope UI theme overrides |

---

*Last updated: 2026-08-16*
