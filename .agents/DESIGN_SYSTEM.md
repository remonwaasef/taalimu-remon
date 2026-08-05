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

## Implementation Files

| File | Purpose |
|---|---|
| `resources/css/design-tokens.css` | CSS custom properties (source of truth) |
| `resources/css/global-components.css` | Global component styles |
| `resources/css/tailwind.css` | Tailwind base + HSL variables |
| `tailwind.config.js` | Tailwind brand palette |
| `public/assets/hope-ui/css/taalimu-unified.css` | Hope UI theme overrides |

---

*Last updated: 2026-08-05*
