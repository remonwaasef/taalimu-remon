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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary-color: #6366f1;
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            --sidebar-width: 280px;
            --topbar-height: 70px;
            --bg-body: #f1f5f9;
        }

        body { 
            font-family: 'Cairo', sans-serif; 
            background-color: var(--bg-body);
            color: #334155;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            right: 0;
            background: white;
            border-left: 1px solid rgba(0,0,0,0.05);
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 25px;
            border-bottom: 1px solid rgba(0,0,0,0.02);
            text-decoration: none;
        }

        .sidebar-brand span.logo-text {
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.4rem;
        }

        .sidebar-nav {
            padding: 20px 15px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 15px;
            margin-top: 25px;
            padding-right: 15px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 18px;
            border-radius: 15px;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 5px;
            transition: all 0.2s;
        }

        .sidebar-link i {
            font-size: 1.25rem;
            margin-left: 15px;
            width: 24px;
            display: flex;
            justify-content: center;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(99, 102, 241, 0.08);
            color: var(--primary-color);
        }

        .sidebar-link.active {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 8px 20px -5px rgba(99, 102, 241, 0.4);
        }

        /* Topbar Styles */
        .topbar {
            height: var(--topbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: var(--sidebar-width);
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            z-index: 999;
            display: flex;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        /* Main Content area */
        .main-wrapper {
            margin-right: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .content-container {
            flex-grow: 1;
            padding: 30px;
        }

        /* Footer */
        footer {
            background: white;
            padding: 25px 30px;
            border-top: 1px solid rgba(0,0,0,0.05);
            margin-top: auto;
        }

        /* Mobile specific */
        .mobile-toggle {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #64748b;
        }

        @media (max-width: 991px) {
            .sidebar {
                right: -100%;
            }
            .sidebar.show {
                right: 0;
            }
            .topbar {
                right: 0;
            }
            .main-wrapper {
                margin-right: 0;
            }
            .mobile-toggle {
                display: block;
            }
            .topbar {
                justify-content: space-between;
            }
        }

        /* User UI helper classes */
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-weight: bold;
        }
        
        .shadow-sm-soft { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05); }
        .rounded-4 { border-radius: 1rem !important; }
    </style>
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
</body>
</html>
