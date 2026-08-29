# Taalimu — Brand Identity & UI Color System

> Version: 1.0  
> Source of truth for Landing Page & Dashboard UI across Taalimu.com.
> CSS Tokens File: `resources/css/design-tokens.css`

---

## 1. Brand Direction & Personality

- **Personality**: Calm + Human + Trustworthy + Modern + Practical + Premium without ostentation.
- **Rule**: Technology operates in the background; education and human experience remain at the forefront.

---

## 2. Core Color System

### Primary Brand Colors

| Token | HEX | Usage |
|---|---|---|
| Primary 50 | `#F2FBF8` | Ultra-light backgrounds |
| Primary 100 / Soft | `#E8F5F1` | Soft background, badges, icons background, highlight |
| Primary 200 | `#D3EBE5` | Borders, subtle highlights, secondary button border |
| Primary 300 | `#A9D9D0` | Secondary accents |
| Primary 400 | `#62BDAE` | Charts, illustrations |
| Primary 500 / Primary | `#168F7C` | Main CTA, primary buttons, active states, brand accents |
| Primary 600 / Dark | `#0D7465` | Hover states, high-contrast green text |
| Primary 700 | `#0A5F54` | Dark green text |

### Neutral Colors

| Token | HEX | Usage |
|---|---|---|
| Ink | `#102033` | Headings, primary body text, navigation |
| Navy | `#0D1A2B` | Dark UI, footer, dark sections, dashboard sidebar |
| Muted | `#65717F` | Secondary text, metadata, labels, helper text |
| Cream | `#FBFAF6` | Main page background (warm, human alternative to pure white) |
| White | `#FFFFFF` | Cards, surfaces, inputs, content panels |
| Border | `#E5ECE9` | Subtle borders & dividers |

---

## 3. Semantic Colors

| Semantic | Token / Value | Soft Background | Usage |
|---|---|---|---|
| Success | `#168F7C` | `#E8F5F1` | Completed tasks, positive status |
| Warning | `#C88924` | `#FFF5E1` | Pending status, warnings |
| Error | `#C94A4A` | `#FDECEC` | Danger, errors, alerts |
| Info | `#3B82A0` | `#EAF5F9` | Informational messages, metadata |

*Rule*: Red, Yellow, and Blue are functional semantic colors only; never use them as primary brand colors.

---

## 4. Typography

- **Arabic**: `Cairo`, sans-serif (400 Regular, 500 Medium, 600 SemiBold, 700 Bold, 800 ExtraBold)
- **Latin / English**: `Inter`, sans-serif (400 Regular, 500 Medium, 600 SemiBold, 700 Bold, 800 ExtraBold)

### Hierarchy
- **H1**: 800 ExtraBold
- **H2**: 700–800 Bold/ExtraBold
- **H3**: 700 Bold
- **Body**: 400–500 Regular/Medium
- **Buttons**: 700 Bold
- **Labels**: 600–700 SemiBold/Bold

---

## 5. Shape Language & Shadows

### Border Radius
- **Small (`--radius-sm`)**: `8px`
- **Medium (`--radius-md`)**: `12px`
- **Cards (`--radius-lg`)**: `18px`
- **Large Sections (`--radius-xl`)**: `24px`
- **Hero / Extra Large (`--radius-2xl`)**: `30px`
- **Pill**: `999px`

### Shadows
- **Small (`--shadow-sm`)**: `0 4px 15px rgba(16,32,51,.05)`
- **Medium (`--shadow-md`)**: `0 10px 30px rgba(16,32,51,.07)`
- **Large (`--shadow-lg`)**: `0 16px 45px rgba(16,32,51,.08)`

---

## 6. Landing Page & Dashboard Composition

### Landing Page Palette Balance
- **55% White** (`#FFFFFF`) for main content cards and surfaces
- **25% Cream** (`#FBFAF6`) for primary page background
- **15% Soft Green** (`#E8F5F1` / `#F2FBF8`) for feature sections
- **5% Navy / Dark** (`#0D1A2B`) for dark CTAs & footer

### Dashboard Structure
- **Main Background**: `#F7FAF8`
- **Sidebar**: `#0D1A2B` (Dark) or `#FFFFFF` (Light)
- **Active Sidebar Item**: Bg `#E8F5F1`, Text `#0D7465`, Icon `#168F7C`
- **Inactive Sidebar Item**: Text `#65717F`, Hover Bg `#F2FBF8`, Hover Text `#168F7C`
- **Main Cards**: Bg `#FFFFFF`, Border `1px solid #E5ECE9`, Radius `18px`

---

## 7. CSS Variables (`:root`)

```css
:root {
    --color-primary: #168F7C;
    --color-primary-dark: #0D7465;
    --color-primary-soft: #E8F5F1;

    --color-primary-50: #F2FBF8;
    --color-primary-100: #E8F5F1;
    --color-primary-200: #D3EBE5;
    --color-primary-300: #A9D9D0;
    --color-primary-400: #62BDAE;
    --color-primary-500: #168F7C;
    --color-primary-600: #0D7465;
    --color-primary-700: #0A5F54;

    --color-ink: #102033;
    --color-navy: #0D1A2B;
    --color-muted: #65717F;

    --color-cream: #FBFAF6;
    --color-white: #FFFFFF;
    --color-border: #E5ECE9;

    --color-warning: #C88924;
    --color-warning-soft: #FFF5E1;

    --color-error: #C94A4A;
    --color-error-soft: #FDECEC;

    --color-info: #3B82A0;
    --color-info-soft: #EAF5F9;

    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 18px;
    --radius-xl: 24px;
    --radius-2xl: 30px;

    --shadow-sm: 0 4px 15px rgba(16,32,51,.05);
    --shadow-md: 0 10px 30px rgba(16,32,51,.07);
    --shadow-lg: 0 16px 45px rgba(16,32,51,.08);
}
```
