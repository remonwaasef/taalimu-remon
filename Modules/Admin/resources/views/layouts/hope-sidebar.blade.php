<x-sidebar homeUrl="{{ route('admin.dashboard') }}">
    <x-slot name="logo">
        <div class="mb-0 bg-primary rounded-pill d-flex align-items-center justify-content-center text-white fw-bold" style="width: 38px; height: 38px; font-size: 1rem;">
            {{ substr(\App\Models\SiteSetting::get('site_name', 'T'), 0, 1) }}
        </div>
        <h5 class="logo-title ms-2 text-truncate text-white fw-bold mb-0" style="max-width: 140px; font-size: 1rem; letter-spacing: -0.5px;">{{ \App\Models\SiteSetting::get('site_name', 'EduCentral') }}</h5>
    </x-slot>

    <!-- Category: Navigation -->
    <li class="nav-item static-item">
        <a class="nav-link static-item disabled" href="#" tabindex="-1">
            <span class="default-icon">{{ __('admin::admin.sidebar.groups.navigation') }}</span>
            <span class="mini-icon">-</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="icon"><i class="bi bi-speedometer2"></i></i>
            <span class="item-name">{{ __('admin::admin.sidebar.dashboard') }}</span>
        </a>
    </li>
    
    <!-- Category: Management -->
    <li class="nav-item static-item">
        <a class="nav-link static-item disabled" href="#" tabindex="-1">
            <span class="default-icon">{{ __('admin::admin.sidebar.groups.management') }}</span>
            <span class="mini-icon">-</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.tenants.*') || request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}" href="{{ route('admin.tenants.index') }}">
            <i class="icon"><i class="bi bi-building"></i></i>
            <span class="item-name">{{ __('admin::admin.sidebar.tenants_subscriptions') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}" href="{{ route('admin.tickets.index') }}">
            <i class="icon"><i class="bi bi-headset"></i></i>
            <span class="item-name">{{ __('admin::admin.sidebar.support') }}</span>
        </a>
    </li>

    <!-- Category: Monitoring -->
    <li class="nav-item static-item">
        <a class="nav-link static-item disabled" href="#" tabindex="-1">
            <span class="default-icon">{{ __('admin::admin.sidebar.groups.monitoring') }}</span>
            <span class="mini-icon">-</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.operation-issues.*') ? 'active' : '' }}" href="{{ route('admin.operation-issues.index') }}">
            <i class="icon"><i class="bi bi-shield-exclamation"></i></i>
            <span class="item-name">{{ __('admin::admin.sidebar.operation_issues') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}" href="{{ route('admin.activity-logs.index') }}">
            <i class="icon"><i class="bi bi-journal-text"></i></i>
            <span class="item-name">{{ __('admin::admin.sidebar.activity_logs') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('consent.report') ? 'active' : '' }}" href="{{ route('consent.report') }}">
            <i class="icon"><i class="bi bi-fingerprint"></i></i>
            <span class="item-name">{{ __('admin::admin.sidebar.cookie_reports') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.bug_reports.index') ? 'active' : '' }}" href="{{ route('admin.bug_reports.index') }}">
            <i class="icon"><i class="bi bi-bug"></i></i>
            <span class="item-name">Beta Bugs</span>
        </a>
    </li>

    <!-- Category: Resources -->
    <li class="nav-item static-item">
        <a class="nav-link static-item disabled" href="#" tabindex="-1">
            <span class="default-icon">{{ __('admin::admin.sidebar.groups.resources') }}</span>
            <span class="mini-icon">-</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.settings.*') && !str_contains(request()->fullUrl(), 'tab=coupons') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
            <i class="icon"><i class="bi bi-gear"></i></i>
            <span class="item-name">{{ __('admin::admin.sidebar.settings') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ str_contains(request()->fullUrl(), 'tab=coupons') ? 'active' : '' }}" href="{{ route('admin.settings.index', ['tab' => 'coupons']) }}">
            <i class="icon"><i class="bi bi-ticket-perforated"></i></i>
            <span class="item-name">{{ __('admin::admin.coupons_discounts') }}</span>
        </a>
    </li>

    <!-- Category: Administration -->
    <li class="nav-item static-item">
        <a class="nav-link static-item disabled" href="#" tabindex="-1">
            <span class="default-icon">{{ __('admin::admin.sidebar.groups.administration') }}</span>
            <span class="mini-icon">-</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}" href="{{ route('admin.backups.index') }}">
            <i class="icon"><i class="bi bi-database"></i></i>
            <span class="item-name">{{ __('admin::admin.sidebar.backups') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
            <i class="icon"><i class="bi bi-shield-lock"></i></i>
            <span class="item-name">{{ __('admin::admin.sidebar.roles') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
            <i class="icon"><i class="bi bi-people"></i></i>
            <span class="item-name">{{ __('admin::admin.sidebar.admin_team') }}</span>
        </a>
    </li>
</x-sidebar>
