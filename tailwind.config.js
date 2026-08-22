import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: ['class'],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
        './resources/js/**/*.jsx',
        './resources/js/**/*.ts',
        './resources/js/**/*.tsx',
        './Modules/**/resources/views/**/*.blade.php',
        './Modules/**/resources/views/**/*.php',
        './Modules/**/resources/assets/**/*.{js,ts,vue,scss,css}',
    ],
    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: '1rem',
                sm: '1.5rem',
                lg: '2rem',
            },
            screens: {
                sm: '640px',
                md: '768px',
                lg: '1024px',
                xl: '1280px',
                '2xl': '1440px',
            },
        },
        extend: {
            fontFamily: {
                sans: ['Inter', 'Cairo', ...defaultTheme.fontFamily.sans],
                cairo: ['Cairo', 'sans-serif'],
                inter: ['Inter', 'sans-serif'],
                arabic: ['Cairo', 'sans-serif'],
            },
            colors: {
                brand: {
                    50: '#E6F4F3',
                    100: '#CCE9E7',
                    200: '#B2DDD9',
                    300: '#7FC7C0',
                    400: '#4DB1A7',
                    primary: '#2E8B83',
                    500: '#2E8B83',
                    600: '#25746D',
                    700: '#1E5E58',
                    800: '#174843',
                    900: '#10322E',
                    bg: '#F6F8FA',
                    border: '#E7ECEF',
                    card: '#FFFFFF',
                },
                divider: "hsl(var(--divider))",
                "text-muted": "#9CA3AF",
                "text-disabled": "#D1D5DB",
                blue: {
                    DEFAULT: '#4F7DF3',
                    50: '#EFF6FF',
                    100: '#DBEAFE',
                    500: '#4F7DF3',
                    600: '#2563EB',
                },
                "soft-blue": {
                    DEFAULT: '#69B7C8',
                    50: '#F0F9FB',
                    100: '#E0F2F7',
                    500: '#69B7C8',
                },
                border: "hsl(var(--border))",
                input: "hsl(var(--input))",
                ring: "hsl(var(--ring))",
                background: "hsl(var(--background))",
                foreground: "hsl(var(--foreground))",
                primary: {
                    DEFAULT: "hsl(var(--primary))",
                    foreground: "hsl(var(--primary-foreground))",
                },
                secondary: {
                    DEFAULT: "hsl(var(--secondary))",
                    foreground: "hsl(var(--secondary-foreground))",
                },
                destructive: {
                    DEFAULT: "hsl(var(--destructive))",
                    foreground: "hsl(var(--destructive-foreground))",
                },
                success: {
                    DEFAULT: "hsl(var(--success, 142 71% 45%))",
                    foreground: "#FFFFFF",
                },
                warning: {
                    DEFAULT: "hsl(var(--warning, 38 92% 50%))",
                    foreground: "#FFFFFF",
                },
                info: {
                    DEFAULT: "hsl(var(--info, 217 91% 60%))",
                    foreground: "#FFFFFF",
                },
                muted: {
                    DEFAULT: "hsl(var(--muted))",
                    foreground: "hsl(var(--muted-foreground))",
                },
                accent: {
                    DEFAULT: "hsl(var(--accent))",
                    foreground: "hsl(var(--accent-foreground))",
                },
                popover: {
                    DEFAULT: "hsl(var(--popover))",
                    foreground: "hsl(var(--popover-foreground))",
                },
                card: {
                    DEFAULT: "hsl(var(--card))",
                    foreground: "hsl(var(--card-foreground))",
                },
                sidebar: {
                    DEFAULT: "hsl(var(--sidebar-background))",
                    foreground: "hsl(var(--sidebar-foreground))",
                    primary: "hsl(var(--sidebar-primary))",
                    "primary-foreground": "hsl(var(--sidebar-primary-foreground))",
                    accent: "hsl(var(--sidebar-accent))",
                    "accent-foreground": "hsl(var(--sidebar-accent-foreground))",
                    border: "hsl(var(--sidebar-border))",
                    ring: "hsl(var(--sidebar-ring))",
                },
            },
            borderRadius: {
                xs: "0.25rem",
                sm: "0.375rem",
                md: "0.5rem",
                lg: "var(--radius, 0.75rem)",
                xl: "1rem",
                "2xl": "1.25rem",
                "3xl": "1.5rem",
            },
            boxShadow: {
                xs: '0 1px 2px 0 rgba(15, 23, 42, 0.04)',
                sm: '0 1px 3px 0 rgba(15, 23, 42, 0.06), 0 1px 2px -1px rgba(15, 23, 42, 0.04)',
                md: '0 4px 6px -1px rgba(15, 23, 42, 0.07), 0 2px 4px -2px rgba(15, 23, 42, 0.05)',
                lg: '0 10px 15px -3px rgba(15, 23, 42, 0.08), 0 4px 6px -4px rgba(15, 23, 42, 0.04)',
                xl: '0 20px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.04)',
            },
            keyframes: {
                "accordion-down": {
                    from: { height: "0" },
                    to: { height: "var(--radix-accordion-content-height)" },
                },
                "accordion-up": {
                    from: { height: "var(--radix-accordion-content-height)" },
                    to: { height: "0" },
                },
                "fade-in": {
                    from: { opacity: "0", transform: "translateY(12px)" },
                    to: { opacity: "1", transform: "translateY(0)" },
                },
                "fade-in-left": {
                    from: { opacity: "0", transform: "translateX(-16px)" },
                    to: { opacity: "1", transform: "translateX(0)" },
                },
                "fade-in-right": {
                    from: { opacity: "0", transform: "translateX(16px)" },
                    to: { opacity: "1", transform: "translateX(0)" },
                },
                "scale-in": {
                    from: { opacity: "0", transform: "scale(0.96)" },
                    to: { opacity: "1", transform: "scale(1)" },
                },
                "slide-up": {
                    from: { transform: "translateY(100%)" },
                    to: { transform: "translateY(0)" },
                },
                shimmer: {
                    from: { backgroundPosition: "200% 0" },
                    to: { backgroundPosition: "-200% 0" },
                },
                pulse: {
                    "0%, 100%": { opacity: "1" },
                    "50%": { opacity: "0.5" },
                },
            },
            animation: {
                "accordion-down": "accordion-down 0.2s ease-out",
                "accordion-up": "accordion-up 0.2s ease-out",
                "fade-in": "fade-in 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards",
                "fade-in-left": "fade-in-left 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards",
                "fade-in-right": "fade-in-right 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards",
                "scale-in": "scale-in 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards",
                "slide-up": "slide-up 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards",
                shimmer: "shimmer 2.5s ease-in-out infinite",
                pulse: "pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite",
            },
        },
    },
    plugins: [],
};
