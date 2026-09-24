# Taalimu Design Tokens Reference (v1.0 — Brand Identity Sheet)

> **Central Source of Truth**: `resources/css/design-tokens.css`  
> All components across the Platform, Landing Pages, and Dashboards consume CSS variables defined in `design-tokens.css`.

---

## 1. Brand Palette Tokens (Taalimu Indigo)

| Token | HEX / Value | Purpose |
|---|---|---|
| `--color-primary` | `#5B5FEF` | Taalimu Indigo — Primary CTA, Buttons, Active States, Main Icons |
| `--color-primary-dark` | `#4548C7` | Hover state for primary buttons, dark brand accents |
| `--color-primary-soft` | `#EEF0FF` | Soft indigo background for badges, active navigation, highlights |
| `--color-primary-50` | `#F5F6FF` | Ultra-light background tint |
| `--color-primary-100` | `#EEF0FF` | Soft tint |
| `--color-primary-200` | `#D7D9FD` | Light borders & subtle highlights |
| `--color-primary-300` | `#B4B8FB` | Secondary accents |
| `--color-primary-400` | `#8185F7` | Charts & illustration accents |
| `--color-primary-500` | `#5B5FEF` | Primary Brand Color |
| `--color-primary-600` | `#4548C7` | Hover State Color |
| `--color-primary-700` | `#3638A0` | Dark Indigo Text |

---

## 2. Neutral Surface Tokens

| Token | HEX | Purpose |
|---|---|---|
| `--color-bg-main` | `#F8FAFC` | Taalimu Snow — Clean main page and dashboard background |
| `--color-white` | `#FFFFFF` | Cards, surfaces, inputs, sidebar background |
| `--color-text-main` | `#111827` | Headings, primary text, high-contrast metrics |
| `--color-text-secondary` | `#475569` | Secondary text, helper labels, descriptions |
| `--color-text-muted` | `#94A3B8` | Metadata, disabled elements, timestamps |
| `--color-border` | `#E2E8F0` | Soft borders & dividers |

---

## 3. Semantic Tokens

| Functional State | Primary Token | Soft Background Token | Text Token |
|---|---|---|---|
| **Success** | `--color-success` (`#16A34A`) | `--color-success-soft` (`#DCFCE7`) | `--color-success-text` (`#166534`) |
| **Warning** | `--color-warning` (`#D97706`) | `--color-warning-soft` (`#FEF3C7`) | `--color-warning-text` (`#92400E`) |
| **Error / Danger** | `--color-error` (`#DC2626`) | `--color-error-soft` (`#FEE2E2`) | `--color-error-text` (`#991B1B`) |
| **Info** | `--color-info` (`#0284C7`) | `--color-info-soft` (`#E0F2FE`) | `--color-info-text` (`#075985`) |

---

## 4. Radii & Elevation

### Radius Scale
- `xs`: `6px`
- `sm`: `8px`
- `md`: `10px` (Buttons & Inputs)
- `lg`: `12px`
- `xl`: `16px` (Cards)
- `2xl`: `20px` (Modals / Dialogs)
- `pill`: `9999px` (Badges & Pills)

### Component Aliases
- `--btn-radius`: `10px`
- `--input-radius`: `10px`
- `--card-radius`: `16px`
- `--modal-radius`: `20px`
- `--badge-radius`: `9999px`

### Elevation / Shadows
- `--shadow-xs`: `0 1px 2px rgba(15, 23, 42, 0.05)` (small elements)
- `--shadow-sm`: `0 2px 8px rgba(15, 23, 42, 0.06)` (cards)
- `--shadow-md`: `0 8px 24px rgba(15, 23, 42, 0.10)` (modals / dropdowns)
- `--shadow-lg`: `0 16px 40px rgba(15, 23, 42, 0.12)` (large panels)
- `--card-shadow`: `0 4px 16px rgba(15, 23, 42, 0.06)`
