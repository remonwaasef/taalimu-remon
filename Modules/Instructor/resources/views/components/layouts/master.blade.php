<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة تحكم المعلم') - {{ config('app.name', 'Taalimu') }}</title>
    
    <!-- Google Fonts (Cairo for Arabic) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    @if(app()->getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <style>
        :root {
            --primary-color: #3A0CA3;
            --primary-light: #4361EE;
            --primary-gradient: linear-gradient(135deg, #3A0CA3 0%, #4361EE 100%);
            --sidebar-width: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-text: #e2e8f0;
            --bg-light: #f8fafc;
            --radius-lg: 1.25rem;
            --radius-md: 0.75rem;
        }

        body { 
            font-family: 'Cairo', sans-serif; 
            background-color: var(--bg-light);
            color: #1e293b;
            min-height: 100 VH;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            top: 0;
            z-index: 1030;
            transition: all 0.3s ease;
            box-shadow: 4px 0 24px rgba(0,0,0,0.1);
        }

        [dir="rtl"] .sidebar { right: 0; }
        [dir="ltr"] .sidebar { left: 0; }

        .main-content {
            transition: all 0.3s ease;
            padding: 2rem;
        }

        @media (min-width: 992px) {
            [dir="rtl"] .main-content { margin-right: var(--sidebar-width); }
            [dir="ltr"] .main-content { margin-left: var(--sidebar-width); }
        }

        /* Simplified Nav Styles */
        .nav-link {
            color: var(--sidebar-text);
            opacity: 0.7;
            padding: 0.8rem 1.5rem;
            margin: 0.2rem 1rem;
            border-radius: var(--radius-md);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-link:hover, .nav-link.active {
            opacity: 1;
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .nav-link.active {
            background: var(--primary-color);
            box-shadow: 0 4px 12px rgba(58, 12, 163, 0.3);
        }

        .nav-link i { font-size: 1.1rem; width: 24px; text-align: center; }

        .card {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .card:hover { transform: translateY(-5px); }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="p-4 text-center border-bottom border-secondary border-opacity-25">
            <h4 class="text-white fw-bold mb-0">Taalimu Instructor</h4>
            <small class="text-white opacity-50">لوحة تحكم المعلم</small>
        </div>

        <nav class="mt-4">
            <a href="{{ route('instructor.dashboard') }}" class="nav-link {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> <span>الرئيسية</span>
            </a>
            <a href="{{ route('instructor.students.list') }}" class="nav-link {{ request()->routeIs('instructor.students.list') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i> <span>الطلاب</span>
            </a>
            <a href="{{ route('instructor.groups.list') }}" class="nav-link {{ request()->routeIs('instructor.groups.list') ? 'active' : '' }}">
                <i class="fas fa-users"></i> <span>المجموعات</span>
            </a>
            <a href="{{ route('center.schedules.index') }}" class="nav-link {{ request()->routeIs('center.schedules.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> <span>المواعيد والجداول</span>
            </a>
            <a href="{{ route('center.attendance.index') }}" class="nav-link {{ request()->routeIs('center.attendance.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-check"></i> <span>الحضور والغياب</span>
            </a>
            <a href="{{ route('instructor.billing') }}" class="nav-link {{ request()->routeIs('instructor.billing') ? 'active' : '' }}">
                <i class="fas fa-wallet"></i> <span>الحسابات</span>
            </a>
            <a href="#" class="nav-link" onclick="alert('سيكون متاحاً في المرحلة القادمة')">
                <i class="fab fa-whatsapp"></i> <span>الواتساب</span>
            </a>
            
            <div class="mt-5 p-3">
                <form action="{{ route('center.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">
                        <i class="fas fa-sign-out-alt me-2"></i> خروج
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold mb-0">@yield('page-title', 'أهلاً بك يا دكتور')</h3>
                <p class="text-muted small mb-0">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="dropdown">
                <button class="btn btn-white bg-white shadow-sm rounded-pill px-4 dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-2 text-primary"></i>
                    {{ auth()->user()->name ?? 'المعلم' }}
                </button>
            </div>
        </header>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
