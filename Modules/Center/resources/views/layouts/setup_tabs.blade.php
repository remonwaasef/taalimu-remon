<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="card-header bg-white border-bottom-0 p-0">
        <ul class="nav nav-tabs nav-fill" id="schoolManagementTabs" role="tablist">
            {{-- 1. Academic Setup --}}
            <li class="nav-item">
                <a href="{{ route('center.settings.index', ['tenant' => app('tenant')->domain ?? 'center', 'tab' => 'academic']) }}" 
                   class="nav-link py-3 fw-bold {{ request()->routeIs('center.settings.index') && request('tab') == 'academic' ? 'active' : '' }}">
                   <i class="fas fa-graduation-cap me-2 text-primary"></i> {{ __('center::sidebar.academic_setup') }}
                </a>
            </li>

            {{-- 2. Infrastructure (Classrooms) --}}
            <li class="nav-item">
                <a href="{{ route('center.classrooms.index', ['tenant' => app('tenant')->domain ?? 'center']) }}" 
                   class="nav-link py-3 fw-bold {{ request()->routeIs('center.classrooms.*') ? 'active' : '' }}">
                   <i class="fas fa-building me-2 text-success"></i> {{ __('center::sidebar.classrooms') }}
                </a>
            </li>

            {{-- 3. Staff (Instructors) --}}
            <li class="nav-item">
                <a href="{{ route('center.instructors.index', ['tenant' => app('tenant')->domain ?? 'center']) }}" 
                   class="nav-link py-3 fw-bold {{ request()->routeIs('center.instructors.*') ? 'active' : '' }}">
                   <i class="fas fa-chalkboard-teacher me-2 text-info"></i> {{ __('center::sidebar.instructors') }}
                </a>
            </li>

            {{-- 4. Courses --}}
            <li class="nav-item">
                <a href="{{ route('center.courses.index', ['tenant' => app('tenant')->domain ?? 'center']) }}" 
                   class="nav-link py-3 fw-bold {{ request()->routeIs('center.courses.*') ? 'active' : '' }}">
                   <i class="fas fa-book-open me-2 text-warning"></i> {{ __('center::sidebar.courses') }}
                </a>
            </li>

            {{-- 5. Schedules --}}
            <li class="nav-item">
                <a href="{{ route('center.schedules.index', ['tenant' => app('tenant')->domain ?? 'center']) }}" 
                   class="nav-link py-3 fw-bold {{ request()->routeIs('center.schedules.*') ? 'active' : '' }}">
                   <i class="fas fa-calendar-alt me-2 text-danger"></i> {{ __('center::sidebar.schedules') }}
                </a>
            </li>
        </ul>
    </div>
</div>
