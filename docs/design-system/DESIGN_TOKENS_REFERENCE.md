# Taalimu Design Tokens Reference (v1.0)

> **Central Source of Truth**: `resources/css/design-tokens.css`  
> All components across the Landing Page and Dashboards consume CSS variables defined in `design-tokens.css`.

---

## 1. Brand Palette Tokens

| Token | HEX / Value | Purpose |
|---|---|---|
| `--color-primary` | `#168F7C` | Taalimu Green — Primary CTA, Buttons, Active States, Main Icons |
| `--color-primary-dark` | `#0D7465` | Hover state for buttons, high-contrast green text |
| `--color-primary-soft` | `#E8F5F1` | Soft green background for badges, icon containers, highlights |
| `--color-primary-50` | `#F2FBF8` | Ultra-light background tint |
| `--color-primary-100` | `#E8F5F1` | Soft tint |
| `--color-primary-200` | `#D3EBE5` | Light borders & subtle highlights |
| `--color-primary-300` | `#A9D9D0` | Secondary accents |
| `--color-primary-400` | `#62BDAE` | Charts & illustration accents |
| `--color-primary-500` | `#168F7C` | Primary Brand Color |
| `--color-primary-600` | `#0D7465` | Hover State Color |
| `--color-primary-700` | `#0A5F54` | Dark Green Text |

---

## 2. Neutral Surface Tokens

| Token | HEX | Purpose |
|---|---|---|
| `--color-ink` | `#102033` | Headings, primary text |
| `--color-navy` | `#0D1A2B` | Dark UI, footer, dark sections, dashboard sidebar |
| `--color-muted` | `#65717F` | Secondary text, helper labels, metadata |
| `--color-cream` | `#FBFAF6` | Warm, human main page background |
| `--color-white` | `#FFFFFF` | Cards, surfaces, inputs |
| `--color-border` | `#E5ECE9` | Soft borders & dividers |

---

## 3. Semantic Tokens

| Functional State | Primary Token | Soft Background Token |
|---|---|---|
| **Success** | `--color-success` (`#168F7C`) | `--color-success-soft` (`#E8F5F1`) |
| **Warning** | `--color-warning` (`#C88924`) | `--color-warning-soft` (`#FFF5E1`) |
| **Error / Danger** | `--color-error` (`#C94A4A`) | `--color-error-soft` (`#FDECEC`) |
| **Info** | `--color-info` (`#3B82A0`) | `--color-info-soft` (`#EAF5F9`) |

---

## 4. Radii & Elevation

### Radius Scale
- `sm`: `8px`
- `md`: `12px`
- `lg`: `18px`
- `xl`: `24px`
- `2xl`: `30px`
- `full`: `999px`

### Elevation / Shadows
- `sm`: `0 4px 15px rgba(16, 32, 51, .05)`
- `md`: `0 10px 30px rgba(16, 32, 51, .07)`
- `lg`: `0 16px 45px rgba(16, 32, 51, .08)`
