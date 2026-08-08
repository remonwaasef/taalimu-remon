<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
  <div class="container-fluid navbar-inner">
    <a href="{{ route('instructor.dashboard') }}" class="navbar-brand">
       <img src="{{ asset('images/brand/logo-full.png') }}" class="rounded-3 shadow-sm p-1" style="max-height: 45px; max-width: 100%;">
       <h4 class="logo-title ms-2 text-truncate" style="max-width: 150px;">Taalimu</h4>
    </a>
    <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
      <i class="icon">
        <svg width="20px" height="20px" viewBox="0 0 24 24">
          <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
      </svg>
      </i>
    </div>
    
    <div class="input-group d-none d-md-flex mx-4" style="max-width: 380px;">
        <span class="input-group-text bg-white border-end-0 rounded-start-pill text-muted ps-3">
            <i class="fas fa-search"></i>
        </span>
        <input type="text" class="form-control border-start-0 rounded-end-pill bg-white shadow-none font-arabic text-sm" placeholder="Search students, groups, classes...">
    </div>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto navbar-list mb-2 mb-lg-0 align-items-center gap-2">
        <!-- Quick Action Add Button -->
        <li class="nav-item">
            <div class="dropdown">
                <button class="btn btn-primary rounded-pill px-3 py-1.5 font-bold text-xs d-flex align-items-center gap-1.5 shadow-sm" data-bs-toggle="dropdown">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add New</span>
                    <i class="fas fa-chevron-down ms-1 fs-8 opacity-75"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3">
                    <li><a class="dropdown-item py-2 text-xs font-bold" href="{{ route('instructor.students.create') }}"><i class="fas fa-user-plus me-2 text-indigo"></i> {{ __('instructor::dashboard.add_new_student') }}</a></li>
                    <li><a class="dropdown-item py-2 text-xs font-bold" href="{{ route('instructor.groups.create') }}"><i class="fas fa-folder-plus me-2 text-emerald"></i> {{ __('instructor::dashboard.create_new_group') }}</a></li>
                    <li><a class="dropdown-item py-2 text-xs font-bold" href="{{ route('instructor.schedules.index') }}"><i class="fas fa-calendar-plus me-2 text-amber"></i> {{ __('instructor::sidebar.schedules') }}</a></li>
                </ul>
            </div>
        </li>

        <!-- Urgent Payments / Debts -->
        <li class="nav-item">
          <a href="{{ route('instructor.billing') }}?status=unpaid" class="nav-link position-relative p-1" title="Urgent Payments">
             <div class="bg-slate-100 text-slate-700 rounded-circle d-flex align-items-center justify-content-center border" style="width: 36px; height: 36px;">
                 <i class="fas fa-bell text-xs"></i>
                 <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                    <span class="visually-hidden">New alerts</span>
                 </span>
             </div>
          </a>
        </li>

        <!-- Language Switcher -->
        <li class="nav-item dropdown">
          <a href="#" class="nav-link" id="langDropdown" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
             <i class="fas fa-language me-2 text-primary"></i>
             {{ strtoupper(app()->getLocale()) }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="langDropdown">
              <li><a class="dropdown-item" href="{{ route('instructor.set-locale', 'ar') }}">العربية</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link py-0 d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
              <div class="avatar avatar-50 avatar-rounded bg-primary d-flex align-items-center justify-content-center text-white fw-bold">
                  {{ substr(auth()->user()->name ?? 'I', 0, 1) }}
              </div>
              <div class="caption mx-3 d-none d-md-block ">
                <h6 class="mb-0 caption-title">{{ auth()->user()->name ?? __('instructor::sidebar.instructor') }}</h6>
                <p class="mb-0 caption-sub-title text-capitalize">{{ __('instructor::sidebar.instructor') }}</p>
              </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end py-2" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{ route('instructor.settings') }}">
                <i class="fas fa-cog me-2"></i> {{ __('instructor::sidebar.settings') }}
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ tenant_route('center.logout') }}">
                @csrf
                <a href="javascript:void(0)" class="dropdown-item text-danger" onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> {{ __('instructor::sidebar.logout') }}
                </a>
              </form>
            </li>
          </ul>
        </li>

      </ul>
    </div>
  </div>
</nav>
