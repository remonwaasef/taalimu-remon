# Taalimu Design Tokens Reference

> **Central Source of Truth**: `resources/css/design-tokens.css`

---

## 1. Brand Palette

| Token | CSS Variable | Hex / Value | Usage |
|---|---|---|---|
| **Primary 500** | `--color-primary` | `#2E8B83` | Main brand color (buttons, active states, key accents) |
| **Primary 600** | `--color-primary-hover` | `#25746D` | Hover state for primary interactive elements |
| **Primary 700** | `--color-primary-active` | `#1E5E58` | Active/pressed state |
| **Primary 50** | `--color-primary-light` | `#E6F4F3` | Subtle backgrounds and tint badges |
| **Primary 200** | `--color-primary-border` | `#B2DDD9` | Subtle borders for brand elements |
| **Secondary 900** | `--color-secondary` | `#0F172A` | Slate dark secondary color |
| **Secondary 800** | `--color-secondary-hover` | `#1E293B` | Hover state for secondary elements |

---

## 2. Semantic & Status Colors

| State | Base (`--color-{name}`) | Hover (`-hover`) | Light Background (`-light`) | Border (`-border`) |
|---|---|---|---|---|
| **Success** | `#22C55E` | `#16A34A` | `#F0FDF4` | `#BBF7D0` |
| **Warning** | `#F59E0B` | `#D97706` | `#FFFBEB` | `#FDE68A` |
| **Danger / Error** | `#EF4444` | `#DC2626` | `#FEF2F2` | `#FECACA` |
| **Info** | `#3B82F6` | `#2563EB` | `#EFF6FF` | `#BFDBFE` |

---

## 3. Surface & Layout Colors

### Light Mode
- **Background**: `#F6F8FA` (`--color-bg-main`)
- **Surface / Cards**: `#FFFFFF` (`--color-surface`)
- **Border**: `#E7ECEF` (`--color-border`)
- **Border Strong**: `#CBD5E1` (`--color-border-strong`)
- **Text Main**: `#1F2937` (`--color-text-main`)
- **Text Secondary**: `#6B7280` (`--color-text-secondary`)
- **Text Muted**: `#9CA3AF` (`--color-text-muted`)

### Dark Mode (`.dark`)
- **Background**: `#0F1720`
- **Surface / Cards**: `#17202B` / `#1D2935`
- **Border**: `#2B3644`
- **Text Main**: `#F9FAFB`
- **Text Secondary**: `#CBD5E1`
- **Text Muted**: `#94A3B8`

---

## 4. Spacing Scale (4px / 8px Grid)

```css
--space-1:   0.25rem;  /* 4px */
--space-2:   0.5rem;   /* 8px */
--space-3:   0.75rem;  /* 12px */
--space-4:   1rem;     /* 16px */
--space-5:   1.25rem;  /* 20px */
--space-6:   1.5rem;   /* 24px */
--space-8:   2rem;     /* 32px */
--space-10:  2.5rem;   /* 40px */
--space-12:  3rem;     /* 48px */
--space-16:  4rem;     /* 64px */
```

---

## 5. Border Radius Scale

```css
--radius-xs:   0.25rem;   /* 4px */
--radius-sm:   0.375rem;  /* 6px */
--radius-md:   0.5rem;    /* 8px */
--radius-lg:   0.75rem;   /* 12px - Buttons & Inputs */
--radius-xl:   1rem;      /* 16px - Cards & Tables */
--radius-2xl:  1.25rem;   /* 20px - Dialogs & Modals */
--radius-full: 9999px;    /* Badges & Avatars */
```

---

## 6. Shadows Scale

```css
--shadow-xs: 0 1px 2px 0 rgba(15, 23, 42, 0.04);
--shadow-sm: 0 1px 3px 0 rgba(15, 23, 42, 0.06), 0 1px 2px -1px rgba(15, 23, 42, 0.04);
--shadow-md: 0 4px 6px -1px rgba(15, 23, 42, 0.07), 0 2px 4px -2px rgba(15, 23, 42, 0.05);
--shadow-lg: 0 10px 15px -3px rgba(15, 23, 42, 0.08), 0 4px 6px -4px rgba(15, 23, 42, 0.04);
--shadow-xl: 0 20px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
```
