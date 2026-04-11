<aside class="sidebar sidebar-default navs-rounded-all sidebar-base">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="{{ route('instructor.dashboard') }}" class="navbar-brand">
            <img src="{{ asset('images/brand/logo-full.png') }}" class="rounded-3 shadow-sm p-1" style="max-height: 45px; max-width: 100%;">
            <h4 class="logo-title ms-2 text-truncate" style="max-width: 150px;">Taalimu</h4>
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
                    <a class="nav-link {{ request()->routeIs('instructor.settings') || request()->routeIs('instructor.whatsapp.*') ? 'active' : '' }}" href="{{ route('instructor.settings') }}">
                        <i class="icon"><i class="fas fa-cog"></i></i>
                        <span class="item-name">{{ __('instructor::sidebar.settings') }}</span>
                    </a>
                </li>

                <li class="nav-item mt-5">
                    <form action="{{ route('center.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                            <i class="icon"><i class="fas fa-sign-out-alt"></i></i>
                            <span class="item-name">{{ __('instructor::sidebar.logout') }}</span>
                        </button>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</aside>
