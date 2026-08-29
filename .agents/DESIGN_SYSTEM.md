# Taalimu Design System 1.0

## Brand Identity & UI Color System

### Primary Brand Colors

| Token | Value | Usage |
|-------|-------|-------|
| `--color-primary` / `--color-primary-500` | `#168F7C` | Primary CTA, buttons, links, active states, brand accents |
| `--color-primary-dark` / `--color-primary-600` | `#0D7465` | Hover states, high-contrast green text, dark CTA |
| `--color-primary-soft` / `--color-primary-100` | `#E8F5F1` | Badge backgrounds, icon backgrounds, subtle highlights, success backgrounds |
| `--color-primary-50` | `#F2FBF8` | Ultra-light backgrounds |
| `--color-primary-200` | `#D3EBE5` | Borders, subtle highlights |
| `--color-primary-300` | `#A9D9D0` | Secondary accents |
| `--color-primary-400` | `#62BDAE` | Charts, illustrations |
| `--color-primary-700` | `#0A5F54` | Dark green text |

### Neutral Colors

| Token | Value | Usage |
|-------|-------|-------|
| `--color-ink` | `#102033` | Main text, headings, navigation |
| `--color-navy` | `#0D1A2B` | Dark UI sections, footer, sidebar |
| `--color-muted` | `#65717F` | Secondary text, metadata, labels, helper text |
| `--color-cream` | `#FBFAF6` | Main page background (landing) |
| `--color-white` | `#FFFFFF` | Cards, inputs, surfaces |
| `--color-border` | `#E5ECE9` | Borders, dividers |

### Semantic Colors (Functional Only - Not Brand)

| Token | Value | Usage |
|-------|-------|-------|
| `--color-success` | `#168F7C` | Success states (uses primary green) |
| `--color-success-soft` | `#E8F5F1` | Success backgrounds |
| `--color-warning` | `#C88924` | Warning states |
| `--color-warning-soft` | `#FFF5E1` | Warning backgrounds |
| `--color-error` / `--color-danger` | `#C94A4A` | Error/danger states |
| `--color-error-soft` | `#FDECEC` | Error backgrounds |
| `--color-info` | `#3B82A0` | Info states |
| `--color-info-soft` | `#EAF5F9` | Info backgrounds |

### Surface & Background Tokens

| Token | Light | Dark | Usage |
|-------|-------|------|-------|
| `--color-bg-main` | `#FBFAF6` | `#0D1A2B` | Main page background |
| `--color-dashboard-bg` | `#F7FAF8` | `#091320` | Dashboard background |
| `--color-surface` | `#FFFFFF` | `#102033` | Card surfaces |
| `--color-surface-elevated` | `#FFFFFF` | `#16283D` | Elevated surfaces |
| `--color-surface-hover` | `#F2FBF8` | `#192D45` | Hover surfaces |
| `--color-bg-sidebar` | `#0D1A2B` | `#091320` | Sidebar background |
| `--color-bg-header` | `#FFFFFF` | `#102033` | Header background |
| `--color-divider` | `#E5ECE9` | `#1F3248` | Dividers |

### Text Hierarchy

| Token | Light | Dark | Usage |
|-------|-------|------|-------|
| `--color-text-main` | `#102033` | `#FBFAF6` | Primary text |
| `--color-text-secondary` | `#65717F` | `#94A3B8` | Secondary text |
| `--color-text-muted` | `#65717F` | `#65717F` | Muted text |
| `--color-text-disabled` | `#A9D9D0` | `#334155` | Disabled text |
| `--color-text-inverse` | `#FFFFFF` | `#FFFFFF` | Inverse text |

### Focus Ring

| Token | Value |
|-------|-------|
| `--color-focus-ring` | `#168F7C` |
| `--focus-ring-alpha` | `rgba(22, 143, 124, 0.2)` |

---

## Typography

### Font Families

| Token | Value |
|-------|-------|
| `--font-family-ar` | `'Cairo', system-ui, -apple-system, sans-serif` |
| `--font-family-en` | `'Inter', system-ui, -apple-system, sans-serif` |

**Default**: Cairo (Arabic-first)

### Font Sizes

| Token | Value | Rem |
|-------|-------|-----|
| `--font-size-display-1` | `3rem` | 48px |
| `--font-size-display-2` | `2.25rem` | 36px |
| `--font-size-h1` | `1.75rem` | 28px |
| `--font-size-h2` | `1.375rem` | 22px |
| `--font-size-h3` | `1.125rem` | 18px |
| `--font-size-body-lg` | `1rem` | 16px |
| `--font-size-body-md` | `0.875rem` | 14px |
| `--font-size-body-sm` | `0.75rem` | 12px |

### Hierarchy Weights

| Element | Weight |
|---------|--------|
| H1 | 800 |
| H2 | 700-800 |
| H3 | 700 |
| Body | 400-500 |
| Buttons | 700 |
| Labels | 600-700 |

---

## Border Radius Scale

| Token | Value | Usage |
|-------|-------|-------|
| `--radius-sm` | `8px` | Small elements |
| `--radius-md` | `12px` | Medium elements, buttons |
| `--radius-lg` | `18px` | Cards |
| `--radius-xl` | `24px` | Large sections |
| `--radius-2xl` | `30px` | Extra large sections |
| `--radius-full` | `999px` | Pills, badges |

### Component Radius Aliases

| Token | Value |
|-------|-------|
| `--btn-radius` | `var(--radius-md)` |
| `--card-radius` | `var(--radius-lg)` |
| `--input-radius` | `10px` |
| `--dialog-radius` | `var(--radius-xl)` |
| `--badge-radius` | `var(--radius-full)` |

---

## Shadows / Elevation

| Token | Value |
|-------|-------|
| `--shadow-xs` | `0 2px 8px rgba(16, 32, 51, .04)` |
| `--shadow-sm` | `0 4px 15px rgba(16, 32, 51, .05)` |
| `--shadow-md` | `0 10px 30px rgba(16, 32, 51, .07)` |
| `--shadow-lg` | `0 16px 45px rgba(16, 32, 51, .08)` |
| `--shadow-xl` | `0 20px 50px rgba(16, 32, 51, .10)` |

**Rule**: Light shadows only. No heavy shadows, glow effects, or neon.

---

## Spacing System

Base unit: **8px**

| Token | Value |
|-------|-------|
| `--spacing-1` | `8px` |
| `--spacing-2` | `16px` |
| `--spacing-3` | `24px` |
| `--spacing-4` | `32px` |
| `--spacing-5` | `40px` |
| `--spacing-6` | `48px` |
| `--spacing-8` | `64px` |
| `--spacing-10` | `80px` |
| `--spacing-12` | `96px` |
| `--spacing-16` | `128px` |

---

## Component Tokens

### Buttons

| Token | Value |
|-------|-------|
| `--btn-primary-bg` | `#168F7C` |
| `--btn-primary-hover-bg` | `#0D7465` |
| `--btn-primary-text` | `#FFFFFF` |
| `--btn-secondary-bg` | `#FFFFFF` |
| `--btn-secondary-text` | `#168F7C` |
| `--btn-secondary-border` | `#D3EBE5` |
| `--btn-dark-bg` | `#0D1A2B` |
| `--btn-dark-text` | `#FFFFFF` |
| `--btn-font-weight` | `700` |
| `--btn-transition` | `all 0.2s cubic-bezier(0.16, 1, 0.3, 1)` |

### Inputs

| Token | Value |
|-------|-------|
| `--input-bg` | `#FFFFFF` |
| `--input-border` | `#DDE7E4` |
| `--input-focus-border` | `#168F7C` |
| `--input-focus-ring` | `#E8F5F1` |
| `--input-text` | `#102033` |

### Cards

| Token | Light | Dark |
|-------|-------|------|
| `--card-bg` | `#FFFFFF` | `#102033` |
| `--card-border` | `#E5ECE9` | `#1F3248` |
| `--card-shadow` | `var(--shadow-sm)` | `var(--shadow-md)` |

### Badges

| Variant | Background | Text | Border |
|---------|------------|------|--------|
| Primary | `#E8F5F1` | `#0D7465` | `#D3EBE5` |
| Success | `#E8F5F1` | `#0D7465` | `#D3EBE5` |
| Warning | `#FFF5E1` | `#8A5B12` | - |
| Danger | `#FDECEC` | `#C94A4A` | - |
| Neutral | `#F2F5F4` | `#65717F` | - |

---

## Dark Mode

All design tokens automatically adapt via `.dark` class on `<html>`. 

**Never** write hardcoded colors - always use CSS variables.

```css
/* ✅ Correct */
.card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    color: var(--color-text-main);
}

/* ❌ Wrong */
.card {
    background: #ffffff;
    border: 1px solid #e7ecef;
    color: #1f2937;
}
```

---

## RTL/LTR Support

- Use logical properties: `margin-inline-start`, `margin-inline-end`, `padding-inline-start`, `padding-inline-end`
- Use `text-start` / `text-end` instead of `text-left` / `text-right`
- Use `float-start` / `float-end` instead of `float-left` / `float-right`
- Arrow icons: `fa-arrow-start` / `fa-arrow-end` or CSS `flip`

---

## Iconography

- **Library**: Font Awesome 6
- **Style**: Outline only (`far` or `fas`)
- **Default size**: `20px`
- **No mixed icon libraries**

---

## Animation

- **Duration**: `150ms–300ms` for micro-interactions
- **Page reveal**: `500ms–700ms`
- **Easing**: `cubic-bezier(0.16, 1, 0.3, 1)` (ease-out-expo)
- **No**: bouncing, excessive parallax, infinite floating, flashy transitions

---

## Golden Rules

1. **Taalimu Green (`#168F7C`) is the primary brand color**
2. **Cream (`#FBFAF6`) adds warmth and humanity**
3. **Navy (`#0D1A2B`) conveys trust and dark sections**
4. **White is a surface, not the entire identity**
5. **Don't overuse cards**
6. **Every color must have a function**
7. **Human imagery must be real, natural, documentary-style**
8. **The product feels like a solution, not decoration**
9. **Dashboard and Landing Page must feel like one product**
10. **UI must be calm, not cluttered**
11. **Design must feel like Taalimu understands the user's work**

---

## File References

- **CSS Variables**: `resources/css/design-tokens.css`
- **Global Components**: `resources/css/global-components.css`
- **Landing V2**: `resources/css/landing-v2.css`
- **SCSS Variables**: `resources/css/_colors.scss`

---

## Brand Formula

```
Taalimu = Human + Calm + Trust + Simple Technology + Education
```

**Goal**: A technical platform that *feels* human, not a technical platform *trying to look* human.