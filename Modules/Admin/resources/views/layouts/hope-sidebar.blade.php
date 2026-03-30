<aside class="sidebar sidebar-default navs-rounded-all sidebar-base">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
            <div class="mb-2 mx-auto bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 45px; height: 45px; font-size: 1.2rem;">
                {{ substr(\App\Models\SiteSetting::get('site_name', 'T'), 0, 1) }}
            </div>
            <h4 class="logo-title ms-2 text-truncate" style="max-width: 150px;">{{ \App\Models\SiteSetting::get('site_name', 'EduCentral') }}</h4>
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>
    
    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list" id="sidebar">
            <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                
                <!-- Category: Navigation -->
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon text-uppercase small fw-bold text-primary opacity-75">{{ __('admin::admin.sidebar.groups.navigation') }}</span>
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
                        <span class="default-icon text-uppercase small fw-bold text-primary opacity-75">{{ __('admin::admin.sidebar.groups.management') }}</span>
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
                        <span class="default-icon text-uppercase small fw-bold text-primary opacity-75">{{ __('admin::admin.sidebar.groups.monitoring') }}</span>
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

                <!-- Category: Resources -->
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon text-uppercase small fw-bold text-primary opacity-75">{{ __('admin::admin.sidebar.groups.resources') }}</span>
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
                        <span class="default-icon text-uppercase small fw-bold text-primary opacity-75">{{ __('admin::admin.sidebar.groups.administration') }}</span>
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

            </ul>
        </div>
    </div>
</aside>
