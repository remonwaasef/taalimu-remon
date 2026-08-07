<x-sidebar homeUrl="{{ route('instructor.dashboard') }}">
    <x-slot name="logo">
        <img src="{{ asset('images/brand/logo-full.png') }}" class="rounded-3 shadow-sm p-1" style="max-height: 45px; max-width: 100%;">
        <h4 class="logo-title ms-2 text-truncate" style="max-width: 150px;">Taalimu</h4>
    </x-slot>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}" href="{{ route('instructor.dashboard') }}">
            <i class="icon"><i class="fas fa-home"></i></i>
            <span class="item-name">{{ __('instructor::sidebar.dashboard') }}</span>
        </a>
    </li>
    
    <li><hr class="hr-horizontal"></li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('instructor.students.list') ? 'active' : '' }}" href="{{ route('instructor.students.list') }}">
            <i class="icon"><i class="fas fa-user-graduate"></i></i>
            <span class="item-name">{{ __('instructor::sidebar.students') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('instructor.groups.list') ? 'active' : '' }}" href="{{ route('instructor.groups.list') }}">
            <i class="icon"><i class="fas fa-users"></i></i>
            <span class="item-name">{{ __('instructor::sidebar.groups') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('instructor.schedules.*') ? 'active' : '' }}" href="{{ route('instructor.schedules.index') }}">
            <i class="icon"><i class="fas fa-calendar-alt"></i></i>
            <span class="item-name">{{ __('instructor::sidebar.schedules') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('instructor.online_classes.*') ? 'active' : '' }}" href="{{ route('instructor.online_classes.index') }}">
            <i class="icon"><i class="fas fa-video"></i></i>
            <span class="item-name">{{ __('instructor::sidebar.online_classes') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('instructor.attendance.*') ? 'active' : '' }}" href="{{ route('instructor.attendance.index') }}">
            <i class="icon"><i class="fas fa-clipboard-check"></i></i>
            <span class="item-name">{{ __('instructor::sidebar.attendance') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('instructor.billing') ? 'active' : '' }}" href="{{ route('instructor.billing') }}">
            <i class="icon"><i class="fas fa-wallet"></i></i>
            <span class="item-name">{{ __('instructor::sidebar.billing') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('instructor.reports.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#sidebar-reports" role="button" aria-expanded="{{ request()->routeIs('instructor.reports.*') ? 'true' : 'false' }}" aria-controls="sidebar-reports">
            <i class="icon"><i class="fas fa-chart-line"></i></i>
            <span class="item-name">{{ __('instructor::sidebar.reports') }}</span>
            <i class="right-icon"><i class="fas fa-chevron-right"></i></i>
        </a>
        <ul class="sub-nav collapse {{ request()->routeIs('instructor.reports.*') ? 'show' : '' }}" id="sidebar-reports" data-bs-parent="#sidebar-menu">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('instructor.reports.students') ? 'active' : '' }}" href="{{ route('instructor.reports.students') }}">
                    <i class="icon"><i class="fas fa-user-graduate"></i></i>
                    <span class="item-name">{{ __('instructor::reports.student_reports') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('instructor.reports.payments') ? 'active' : '' }}" href="{{ route('instructor.reports.payments') }}">
                    <i class="icon"><i class="fas fa-wallet"></i></i>
                    <span class="item-name">{{ __('instructor::reports.payment_reports') }}</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('instructor.settings') || request()->routeIs('instructor.whatsapp.*') ? 'active' : '' }}" href="{{ route('instructor.settings') }}">
            <i class="icon"><i class="fas fa-cog"></i></i>
            <span class="item-name">{{ __('instructor::sidebar.settings') }}</span>
        </a>
    </li>

    <!-- Premium Upgrade Widget Card -->
    <div class="px-3 my-3">
        <div class="p-3 rounded-4 bg-gradient-to-br" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.08) 0%, rgba(139, 92, 246, 0.12) 100%); border: 1px solid rgba(139, 92, 246, 0.2);">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-crown text-indigo"></i>
                <span class="fw-bold text-xs text-indigo">Upgrade to Premium</span>
            </div>
            <p class="text-muted text-xs mb-2 font-arabic" style="font-size: 0.72rem; line-height: 1.3;">Unlock all features and grow your institution.</p>
            <a href="{{ route('instructor.billing') }}" class="btn btn-indigo btn-sm rounded-pill w-100 font-bold text-xs py-1.5 shadow-sm">
                Upgrade Now
            </a>
        </div>
    </div>

    <li class="nav-item mt-2">
        <form action="{{ tenant_route('center.logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                <i class="icon"><i class="fas fa-sign-out-alt"></i></i>
                <span class="item-name">{{ __('instructor::sidebar.logout') }}</span>
            </button>
        </form>
    </li>
</x-sidebar>
