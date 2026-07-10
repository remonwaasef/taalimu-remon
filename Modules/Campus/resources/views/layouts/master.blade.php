<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بوابة الطالب - {{ $tenant->name ?? 'EduCentral' }}</title>
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') . '?v=' . (file_exists(public_path('manifest.json')) ? filemtime(public_path('manifest.json')) : '1') }}">
    <meta name="theme-color" content="#4f46e5">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') . '?v=' . (file_exists(public_path('images/icons/icon-192x192.png')) ? filemtime(public_path('images/icons/icon-192x192.png')) : '1') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <link rel="stylesheet" href="{{ asset('assets/css/campus-custom.css') . '?v=' . filemtime(public_path('assets/css/campus-custom.css')) }}">
    <!-- Network Resilience Monitor -->
    <link rel="stylesheet" href="{{ asset('css/network-monitor.css') . '?v=' . (file_exists(public_path('css/network-monitor.css')) ? filemtime(public_path('css/network-monitor.css')) : '1') }}">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('campus.index') }}" class="sidebar-brand">
            <span class="fs-4 me-2">🎓</span>
            <span class="logo-text">{{ $tenant->name ?? 'EduCentral' }}</span>
        </a>

        <div class="sidebar-nav">
            <div class="nav-label">الرئيسية</div>
            <a href="{{ route('campus.index') }}" class="sidebar-link {{ request()->routeIs('campus.index') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span>لوحة التحكم</span>
            </a>

            <div class="nav-label">أدوات التعلم</div>
            <a href="{{ route('campus.courses.index') }}" class="sidebar-link {{ request()->routeIs('campus.courses.index') ? 'active' : '' }}">
                <i class="bi bi-book-fill"></i>
                <span>تصفح الدورات</span>
            </a>
            <a href="{{ route('campus.schedule') }}" class="sidebar-link {{ request()->routeIs('campus.schedule') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i>
                <span>الجدول الدراسي</span>
            </a>

            <div class="nav-label">متابعة الطالب</div>
            <a href="{{ route('campus.attendance') }}" class="sidebar-link {{ request()->routeIs('campus.attendance') ? 'active' : '' }}">
                <i class="bi bi-check2-circle"></i>
                <span>سجل الحضور</span>
            </a>
            <a href="{{ route('campus.finances') }}" class="sidebar-link {{ request()->routeIs('campus.finances') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i>
                <span>المالية والديون</span>
            </a>

            <div class="nav-label">الإعدادات</div>
            <a href="{{ route('campus.profile') }}" class="sidebar-link {{ request()->routeIs('campus.profile') ? 'active' : '' }}">
                <i class="bi bi-person-fill-gear"></i>
                <span>الملف الشخصي</span>
            </a>
        </div>
        
        <div class="p-3 mt-auto">
            <form action="{{ route('center.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-light w-100 rounded-4 py-2 text-danger fw-bold border-0">
                    <i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>

    <!-- Topbar -->
    <header class="topbar">
        <div class="mobile-toggle" id="sidebar-toggle">
            <i class="bi bi-list"></i>
        </div>

        <div class="ms-auto d-flex align-items-center gap-3">
            <div class="d-none d-md-block text-end">
                <div class="fw-bold text-dark small">{{ auth()->user()->name }}</div>
                <div class="text-muted extra-small" style="font-size: 0.7rem;">طالب معتمد</div>
            </div>
            <div class="avatar-circle shadow-sm">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-wrapper">
        <div class="content-container">
            @yield('content')
        </div>

        <footer>
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <span class="text-muted small">&copy; {{ date('Y') }} {{ $tenant->name ?? 'EduCentral' }}. جميع الحقوق محفوظة.</span>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                        <span class="text-muted small">مدعوّ لبيئة تعليمية ذكية 🚀</span>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggler for Mobile
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebar-toggle');
        
        if(toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 992 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
    <!-- Network Resilience Monitor -->
    <script src="{{ asset('js/network-monitor.js') }}"></script>
    <x-cookie-consent />
</body>
</html>
