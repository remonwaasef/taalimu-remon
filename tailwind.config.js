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
                },
                "soft-blue": {
                    DEFAULT: '#69B7C8',
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
                // Custom brand colors
                "dark-purple": "hsl(var(--dark-purple) / <alpha-value>)",
                "primary-purple": "hsl(var(--primary-purple) / <alpha-value>)",
                "light-purple": "hsl(var(--light-purple) / <alpha-value>)",
                cyan: "hsl(var(--cyan) / <alpha-value>)",
                "success-green": "hsl(var(--success-green) / <alpha-value>)",
                "light-bg": "hsl(var(--light-bg) / <alpha-value>)",
                "gray-text": "hsl(var(--gray-text) / <alpha-value>)",
                "dark-text": "hsl(var(--dark-text) / <alpha-value>)",
                "analytical-blue": "hsl(var(--analytical-blue) / <alpha-value>)",
                "outline-green": "hsl(var(--outline-green) / <alpha-value>)",
                "outline-purple": "hsl(var(--outline-purple) / <alpha-value>)",
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
                lg: "var(--radius)",
                md: "calc(var(--radius) - 2px)",
                sm: "calc(var(--radius) - 4px)",
                xl: "calc(var(--radius) + 4px)",
                "2xl": "calc(var(--radius) + 8px)",
                "3xl": "calc(var(--radius) + 16px)",
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
                    from: { opacity: "0", transform: "translateY(20px)" },
                    to: { opacity: "1", transform: "translateY(0)" },
                },
                "fade-in-left": {
                    from: { opacity: "0", transform: "translateX(-20px)" },
                    to: { opacity: "1", transform: "translateX(0)" },
                },
                "fade-in-right": {
                    from: { opacity: "0", transform: "translateX(20px)" },
                    to: { opacity: "1", transform: "translateX(0)" },
                },
                "scale-in": {
                    from: { opacity: "0", transform: "scale(0.95)" },
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
                "fade-in": "fade-in 0.6s ease-out forwards",
                "fade-in-left": "fade-in-left 0.6s ease-out forwards",
                "fade-in-right": "fade-in-right 0.6s ease-out forwards",
                "scale-in": "scale-in 0.4s ease-out forwards",
                "slide-up": "slide-up 0.5s ease-out forwards",
                shimmer: "shimmer 3s ease-in-out infinite",
                pulse: "pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite",
            },
            backgroundImage: {
                "gradient-radial": "radial-gradient(var(--tw-gradient-stops))",
                "hero-pattern": "url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")",
            },
        },
    },
    plugins: [],
};
