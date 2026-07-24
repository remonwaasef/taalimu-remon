<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="h-full" x-data="{ theme: localStorage.getItem('theme') || 'system' }" :class="{ 'dark': theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches) }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Taalimu'))</title>

    <!-- Google Fonts: Inter & Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- ApexCharts Script -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vite Assets (TailwindCSS + App) -->
    @vite(['resources/css/tailwind.css', 'resources/css/app.scss', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="h-full bg-brand-bg dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-inter antialiased selection:bg-brand-primary selection:text-white transition-colors duration-200">

    <div class="min-h-screen flex">
        <!-- Sidebar Navigation -->
        @yield('sidebar')

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Navbar -->
            <x-ui.navbar />

            <!-- Main Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Global Command Palette Modal (Ctrl+K) -->
    <x-ui.command-palette />

    @stack('scripts')
</body>
</html>
