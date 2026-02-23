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
    <!-- Font Awesome (Required for icons in index) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- PWA Support -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Taalimu">

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

        .user-badge-premium {
            background: white;
            padding: 0.4rem 0.6rem;
            border-radius: 50px;
            transition: 0.3s;
        }
        .user-badge-premium:hover {
            background: #f1f5f9;
        }
        .avatar-sm { width: 32px; height: 32px; font-size: 0.9rem; }
        .shadow-premium { box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1); }
        .rounded-4 { border-radius: 1rem !important; }
        .rounded-5 { border-radius: 1.5rem !important; }
        .rounded-ultra { border-radius: 2rem !important; }
        
        .bg-glass {
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top mb-4 py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('campus.index') }}">
                <span class="fs-4">🎓</span>
                <span class="fw-bold">{{ $tenant->name ?? 'EduCentral' }}</span>
            </a>
            
            <div class="ms-auto d-flex align-items-center gap-2">
                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn user-badge-premium d-flex align-items-center gap-2 border-0 shadow-sm transition" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="d-none d-md-inline fw-semibold text-dark">{{ auth()->user()->name ?? 'طالب' }}</span>
                        <i class="bi bi-chevron-down small opacity-50"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2 mt-2 animate__animated animate__fadeIn">
                        <li>
                            <a class="dropdown-item rounded-3 py-2 mb-1 {{ request()->routeIs('campus.profile') ? 'active bg-primary bg-opacity-10 text-primary' : '' }}" href="{{ route('campus.profile') }}">
                                <i class="bi bi-person me-2"></i> ملفي الشخصي
                            </a>
                        </li>
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li>
                            <form action="{{ route('center.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 py-2 text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then((reg) => console.log('SW registered:', reg.scope))
                    .catch((err) => console.log('SW failed:', err));
            });
        }
    </script>
</body>
</html>
