<aside class="sidebar sidebar-default navs-rounded-all sidebar-base">
    <div class="sidebar-header d-flex align-items-center justify-content-between px-4 py-3">
        <a href="{{ route('center.dashboard', ['tenant' => $tenant->domain ?? 'center']) }}" class="navbar-brand d-flex align-items-center m-0">
            @if($tenant->logo)
                <div class="brand-logo-container bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center p-1" style="width: 38px; height: 38px; border: 1px solid rgba(0,0,0,0.05);">
                    <img src="{{ asset('storage/' . $tenant->logo) }}" style="max-height: 28px; width: auto; object-fit: contain;">
                </div>
            @else
                <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 38px; height: 38px; font-size: 1.1rem; background: var(--primary-gradient) !important;">
                    {{ substr($tenant->name ?? 'T', 0, 1) }}
                </div>
            @endif
            <div class="ms-3 line-height">
                <h4 class="logo-title fw-bold mb-0" style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; letter-spacing: -0.5px;">
                    {{ $tenant->name ?? __('sidebar.center_name') }}
                </h4>
            </div>
        </a>
        <div class="sidebar-toggle border-0" data-toggle="sidebar" data-active="true">
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
                
                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('center.dashboard') ? 'active' : '' }}" aria-current="page" href="{{ route('center.dashboard', ['tenant' => $tenant->domain ?? 'center']) }}">
                        <i class="icon"><i class="fas fa-home"></i></i>
                        <span class="item-name">{{ __('center::sidebar.dashboard') }}</span>
                    </a>
                </li>
                
                <li><hr class="hr-horizontal"></li>
                
                {{-- SCHOOL MANAGEMENT --}}
                @php 
                    $canInstructors = ($tenant->getFeatureValue('max_instructors') != '0' && $tenant->getFeatureValue('max_instructors') !== false) && auth()->user()->can('view instructors');
                    $canCourses = ($tenant->getFeatureValue('max_courses') != '0' && $tenant->getFeatureValue('max_courses') !== false) && auth()->user()->can('view courses');
                    $canClassrooms = ($tenant->getFeatureValue('max_classrooms') != '0' && $tenant->getFeatureValue('max_classrooms') !== false) && auth()->user()->can('view schedule');
                    $canSchedules = $tenant->getFeatureValue('daily_schedules') === true && auth()->user()->can('view schedule');
                    $canOnlineClasses = auth()->user()->can('view schedule');

                    $isSchoolMgmtActive = request()->routeIs('center.classrooms.*') || 
                                          request()->routeIs('center.instructors.*') || 
                                          request()->routeIs('center.courses.*') || 
                                          request()->routeIs('center.online_classes.*') || 
                                          request()->routeIs('center.schedules.*');
                    
                    $showSchoolMgmt = ($canInstructors || $canCourses || $canClassrooms || $canSchedules || $canOnlineClasses) && ($tenant->type !== 'instructor');
                @endphp
                
                @if($showSchoolMgmt)
                    <li class="nav-item static-item">
                        <a class="nav-link static-item disabled" href="#" tabindex="-1">
                            <span class="default-icon">{{ __('center::sidebar.school_management') }}</span>
                            <span class="mini-icon">-</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isSchoolMgmtActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#schoolMgmtCollapse" role="button" aria-expanded="{{ $isSchoolMgmtActive ? 'true' : 'false' }}" aria-controls="schoolMgmtCollapse">
                            <i class="icon"><i class="fas fa-university text-warning"></i></i>
                            <span class="item-name">{{ __('center::sidebar.school_management') }}</span>
                            <i class="right-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </i>
                        </a>
                        <ul class="sub-nav collapse {{ $isSchoolMgmtActive ? 'show' : '' }}" id="schoolMgmtCollapse" data-bs-parent="#sidebar-menu">
                            @if($canInstructors)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('center.instructors.*') ? 'active' : '' }}" href="{{ route('center.instructors.index', ['tenant' => $tenant->domain ?? 'center']) }}">
                                    <i class="sidenav-mini-icon">I</i><span class="item-name">{{ __('center::sidebar.instructors') }}</span>
                                </a>
                            </li>
                            @endif
                            @if($canCourses)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('center.courses.*') ? 'active' : '' }}" href="{{ route('center.courses.index', ['tenant' => $tenant->domain ?? 'center']) }}">
                                    <i class="sidenav-mini-icon">C</i><span class="item-name">{{ __('center::sidebar.courses') }}</span>
                                </a>
                            </li>
                            @endif
                            @if($canClassrooms)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('center.classrooms.*') ? 'active' : '' }}" href="{{ route('center.classrooms.index', ['tenant' => $tenant->domain ?? 'center']) }}">
                                    <i class="sidenav-mini-icon">R</i><span class="item-name">{{ __('center::sidebar.classrooms') }}</span>
                                </a>
                            </li>
                            @endif
                            @if($canSchedules)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('center.schedules.*') ? 'active' : '' }}" href="{{ route('center.schedules.index', ['tenant' => $tenant->domain ?? 'center']) }}">
                                    <i class="sidenav-mini-icon">S</i><span class="item-name">{{ __('center::sidebar.schedules') }}</span>
                                </a>
                            </li>
                            @endif
                            @if($canOnlineClasses)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('center.online_classes.*') ? 'active' : '' }}" href="{{ route('center.online_classes.index', ['tenant' => $tenant->domain ?? 'center']) }}">
                                    <i class="sidenav-mini-icon">V</i><span class="item-name text-success fw-bold">{{ __('center::sidebar.online_classes') }}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif
                
                {{-- STUDENTS --}}
                @php 
                    $canStudents = ($tenant->getFeatureValue('max_students') != '0' && $tenant->getFeatureValue('max_students') !== false) && auth()->user()->can('view students');
                    $canAttendance = $tenant->getFeatureValue('attendance_tracking') && auth()->user()->can('view attendance');
                    $isStudentsActive = request()->routeIs('center.students.*') || request()->routeIs('center.attendance.*'); 
                    $showStudents = $canStudents || $canAttendance;
                @endphp
                @if($showStudents)
                    <li class="nav-item">
                        <a class="nav-link {{ $isStudentsActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#studentsCollapse" role="button" aria-expanded="{{ $isStudentsActive ? 'true' : 'false' }}" aria-controls="studentsCollapse">
                            <i class="icon"><i class="fas fa-user-graduate text-info"></i></i>
                            <span class="item-name">{{ __('center::sidebar.students') }}</span>
                            <i class="right-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </i>
                        </a>
                        <ul class="sub-nav collapse {{ $isStudentsActive ? 'show' : '' }}" id="studentsCollapse" data-bs-parent="#sidebar-menu">
                            @if($canStudents)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('center.students.*') ? 'active' : '' }}" href="{{ route('center.students.index', ['tenant' => $tenant->domain ?? 'center']) }}">
                                    <i class="sidenav-mini-icon">S</i><span class="item-name">{{ __('center::sidebar.list') }}</span>
                                </a>
                            </li>
                            @endif
                            @if($canAttendance)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('center.attendance.*') ? 'active' : '' }}" href="{{ route('center.attendance.index', ['tenant' => $tenant->domain ?? 'center']) }}">
                                    <i class="sidenav-mini-icon">A</i><span class="item-name">{{ __('center::sidebar.attendance') }}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif
                
                

                {{-- FINANCE --}}
                @php 
                    $hasFinancialReports = $tenant->getFeatureValue('financial_reports') && auth()->user()->canAny(['view sales', 'view expenses']);
                    $hasAdvancedReports = $tenant->getFeatureValue('advanced_reports') && auth()->user()->can('view reports');
                    $isFinanceActive = request()->routeIs('center.sales.*') || request()->routeIs('center.expenses.*') || request()->routeIs('center.analytics.*'); 
                @endphp
                @if($hasFinancialReports || $hasAdvancedReports)
                    <li class="nav-item">
                        <a class="nav-link {{ $isFinanceActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#financeCollapse" role="button" aria-expanded="{{ $isFinanceActive ? 'true' : 'false' }}" aria-controls="financeCollapse">
                            <i class="icon"><i class="fas fa-chart-line text-success"></i></i>
                            <span class="item-name">{{ __('center::sidebar.financial') }}</span>
                            <i class="right-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </i>
                        </a>
                        <ul class="sub-nav collapse {{ $isFinanceActive ? 'show' : '' }}" id="financeCollapse" data-bs-parent="#sidebar-menu">
                            @can('view sales')
                            {{-- <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.sales.index') ? 'active' : '' }}" href="{{ route('center.sales.index', ['tenant' => $tenant->domain ?? 'center']) }}"><i class="sidenav-mini-icon">S</i><span class="item-name">{{ __('center::sidebar.sales') }}</span></a></li> --}}
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.sales.account') ? 'active' : '' }}" href="{{ route('center.sales.account', ['tenant' => $tenant->domain ?? 'center']) }}"><i class="sidenav-mini-icon">A</i><span class="item-name">{{ __('center::sidebar.student_accounts') }}</span></a></li>
                            @endcan
                            @can('view expenses')
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.expenses.*') ? 'active' : '' }}" href="{{ route('center.expenses.index', ['tenant' => $tenant->domain ?? 'center']) }}"><i class="sidenav-mini-icon">E</i><span class="item-name">{{ __('center::sidebar.expenses') }}</span></a></li>
                            @endcan
                            @canany(['view reports', 'view analytics'])
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.analytics.finance') ? 'active' : '' }}" href="{{ route('center.analytics.finance') }}"><i class="sidenav-mini-icon">F</i><span class="item-name">{{ __('center::sidebar.financial_analytics') }}</span></a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.analytics.commissions') ? 'active' : '' }}" href="{{ route('center.analytics.commissions') }}"><i class="sidenav-mini-icon">C</i><span class="item-name">{{ __('center::sidebar.financial_commissions') }}</span></a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.analytics.taxes') ? 'active' : '' }}" href="{{ route('center.analytics.taxes') }}"><i class="sidenav-mini-icon">T</i><span class="item-name">{{ __('center::sidebar.financial_taxes') }}</span></a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.analytics.discounts') ? 'active' : '' }}" href="{{ route('center.analytics.discounts') }}"><i class="sidenav-mini-icon">D</i><span class="item-name">{{ __('center::sidebar.financial_discounts') }}</span></a></li>
                            @endcanany

                            @if($hasAdvancedReports)
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.analytics.index') ? 'active' : '' }}" href="{{ route('center.analytics.index') }}"><i class="sidenav-mini-icon">G</i><span class="item-name">{{ __('center::analytics.general') }}</span></a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                
                {{-- SETTINGS --}}
                @canany(['manage users', 'manage settings', 'manage billing'])
                @php 
                    $isSettingsActive = request()->routeIs('center.assets.*') || 
                                        request()->routeIs('center.settings.*') || 
                                        request()->routeIs('center.users.*') || 
                                        request()->routeIs('center.roles.*') || 
                                        request()->routeIs('center.branches.*') ||
                                        request()->routeIs('center.tickets.*') ||
                                        request()->routeIs('center.subscription.*'); 
                @endphp
                    <li class="nav-item static-item">
                        <a class="nav-link static-item disabled" href="#" tabindex="-1">
                            <span class="default-icon">{{ __('center::sidebar.settings') }}</span>
                            <span class="mini-icon">-</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isSettingsActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#settingsCollapse" role="button" aria-expanded="{{ $isSettingsActive ? 'true' : 'false' }}" aria-controls="settingsCollapse">
                            <i class="icon"><i class="fas fa-cogs text-secondary"></i></i>
                            <span class="item-name">{{ __('center::sidebar.settings') }}</span>
                            <i class="right-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </i>
                        </a>
                        <ul class="sub-nav collapse {{ $isSettingsActive ? 'show' : '' }}" id="settingsCollapse" data-bs-parent="#sidebar-menu">
                            {{-- 1. Subscription --}}
                            @can('manage billing')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('center.subscription.*') ? 'active' : '' }}" href="{{ route('center.subscription.index', ['tenant' => $tenant->domain ?? 'center']) }}">
                                    <i class="sidenav-mini-icon">S</i><span class="item-name">{{ __('center::sidebar.subscription') }}</span>
                                    @php
                                        $subEndsAt = app('tenant')->subscriptions?->last()?->ends_at;
                                        $daysLeft  = $subEndsAt ? max(0, now()->diffInDays($subEndsAt, false)) : null;
                                    @endphp
                                    @if($daysLeft !== null && $daysLeft <= 7)
                                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size:0.65rem; padding: 2px 6px;">{{ $daysLeft }}d</span>
                                    @endif
                                </a>
                            </li>
                            @endcan
                            
                            {{-- 2. General Settings --}}
                            @can('manage settings')
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.settings.index') && (request('tab') == 'general' || !request('tab')) ? 'active' : '' }}" href="{{ route('center.settings.index', ['tenant' => $tenant->domain ?? 'center', 'tab' => 'general']) }}"><i class="sidenav-mini-icon">G</i><span class="item-name">{{ __('center::settings.tabs.general') }}</span></a></li>
                            @endcan
                            
                            {{-- 3. Users --}}
                            @can('manage users')
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.users.*') ? 'active' : '' }}" href="{{ route('center.users.index', ['tenant' => $tenant->domain ?? 'center']) }}"><i class="sidenav-mini-icon">U</i><span class="item-name">{{ __('center::sidebar.users') }}</span></a></li>
                            
                            @if($tenant->getFeatureValue('advanced_roles'))
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.roles.*') ? 'active' : '' }}" href="{{ route('center.roles.index', ['tenant' => $tenant->domain ?? 'center']) }}"><i class="sidenav-mini-icon">R</i><span class="item-name">{{ __('center::sidebar.permissions') }}</span></a></li>
                            @endif
                            @endcan

                            @can('manage settings')
                            @if($tenant->getFeatureValue('multi_branch'))
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.branches.*') ? 'active' : '' }}" href="{{ route('center.branches.index', ['tenant' => $tenant->domain ?? 'center']) }}"><i class="sidenav-mini-icon">B</i><span class="item-name">{{ __('center::sidebar.branches') }}</span></a></li>
                            @endif
                            @endcan

                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('center.tickets.*') ? 'active' : '' }}" href="{{ route('center.tickets.index', ['tenant' => $tenant->domain ?? 'center']) }}"><i class="sidenav-mini-icon">T</i><span class="item-name">{{ __('center::sidebar.support') }}</span></a></li>
                        </ul>
                    </li>
                @endcanany

            </ul>
        </div>
    </div>
    <div class="sidebar-footer"></div>
</aside>
