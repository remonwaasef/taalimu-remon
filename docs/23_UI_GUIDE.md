# 23_UI_GUIDE - UI Design Tokens & Component Library

## Design System Tokens

- **Token File**: `resources/css/design-tokens.css`
- **Primary Color**: `--primary-500` (#3b82f6)
- **Glass Background**: `--glass-bg`, `--glass-border`, `--glass-blur`
- **Brand Gradient**: `linear-gradient(135deg, #0f172a 0%, #1e293b 100%)` (footer dark surfaces)
- **Accent Green**: `#10b981` (emerald-500 — status indicators, link hovers)
- **Accent Indigo**: `#818cf8` (indigo-400 — version badges)

---

## Reusable Blade Components

- **Modal Overlay**: `components/ui/modal.blade.php`
- **Dropdown Menu**: `components/ui/dropdown.blade.php`
- **Avatar**: `components/ui/avatar.blade.php`
- **Command Palette**: `components/ui/command-palette.blade.php`
- **Flash Messages**: `components/flash-messages.blade.php`
- **PWA Offline Screen**: `resources/views/offline.blade.php`

---

## Footer Components (4 Locations)

All project footers follow a unified professional dark design language:

| Layout | File | Framework | Features |
|--------|------|-----------|----------|
| **Landing** | `resources/views/landing/partials/footer.blade.php` | TailwindCSS | Dark gradient bg, animated social icons, column links (Product/Resources/Legal), system status badge, copyright |
| **Hope-Master Dashboard** | `resources/views/layouts/hope-master.blade.php` | Bootstrap + Inline | Dark gradient bar, Privacy link, operational badge, version tag, `@yield('footer_left_extra')` |
| **App-Next Instructor** | `resources/views/layouts/app-next.blade.php` | TailwindCSS | Glassmorphism bar, dark mode aware, copyright, Privacy link, animated status dot, version badge |
| **Campus Module** | `Modules/Campus/resources/views/layouts/master.blade.php` | Bootstrap + Inline | Dark gradient bar, tenant name, operational badge, "Powered by Taalimu" gradient text, version tag |

### Shared Design Language
- **Background**: `linear-gradient(135deg, #0f172a, #1e293b)` dark slate gradient
- **Status Indicator**: Animated green pulse dot + "Operational" / "Online" label
- **Version Badge**: Indigo code-branch icon + `config('app.version', '1.0')`
- **Copyright**: `©{year} {app.name}` dynamic
- **Hover Effects**: Emerald-500 color transitions on links
