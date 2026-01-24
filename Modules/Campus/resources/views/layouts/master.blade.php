<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بوابة الطالب - {{ $tenant->name ?? 'EduCentral' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #6366f1;
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            --bg-color: #f8fafc;
        }

        body { 
            font-family: 'Cairo', sans-serif; 
            background-color: var(--bg-color);
            color: #1e293b;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .navbar-brand {
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 600;
            color: #64748b !important;
            transition: all 0.3s;
            padding: 0.5rem 1rem !important;
            border-radius: 12px;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
            background: rgba(99, 102, 241, 0.05);
        }

        .btn-logout {
            background: #fff1f2;
            color: #e11d48;
            border: none;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: #ffe4e6;
            transform: translateY(-1px);
        }

        .user-badge {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 600;
        }

        .rounded-ultra { border-radius: 1.25rem; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top mb-5 py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <span class="fs-4">🎓</span>
                {{ $tenant->name ?? 'EduCentral' }}
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('campus.index') ? 'active' : '' }}" href="{{ route('campus.index') }}">
                            <i class="bi bi-book me-1"></i> دوراتي
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('campus.schedule') ? 'active' : '' }}" href="{{ route('campus.schedule') }}">
                            <i class="bi bi-calendar-week me-1"></i> الجدول الدراسي
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('campus.attendance') ? 'active' : '' }}" href="{{ route('campus.attendance') }}">
                            <i class="bi bi-clock-history me-1"></i> سجل الحضور
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('campus.finances') ? 'active' : '' }}" href="{{ route('campus.finances') }}">
                            <i class="bi bi-cash-stack me-1"></i> ديوني ومدفوعاتي
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('campus.profile') }}" class="text-decoration-none transition">
                        <div class="user-badge d-none d-md-flex align-items-center gap-2">
                            <i class="bi bi-person-circle"></i>
                            <span>{{ auth()->user()->name ?? 'طالب' }}</span>
                        </div>
                    </a>
                    <form action="{{ route('center.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-logout rounded-pill px-4 fw-bold">
                            <i class="bi bi-box-arrow-right me-1"></i> خروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
