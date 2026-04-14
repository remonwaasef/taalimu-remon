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
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto  navbar-list mb-2 mb-lg-0">
        
        <!-- Language Switcher -->
        <li class="nav-item dropdown">
          <a href="#" class="nav-link" id="langDropdown" data-bs-toggle="dropdown" aria-expanded="false">
             <i class="fas fa-language me-2 text-primary"></i>
             {{ strtoupper(app()->getLocale()) }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="langDropdown">
              <li><a class="dropdown-item" href="{{ route('instructor.set-locale', 'ar') }}">العربية</a></li>
              <li><a class="dropdown-item" href="{{ route('instructor.set-locale', 'en') }}">English</a></li>
              <li><a class="dropdown-item" href="{{ route('instructor.set-locale', 'fr') }}">Français</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown d-flex align-items-center">
          <div class="d-flex align-items-center">
            <a class="nav-link py-0 d-flex align-items-center pe-0" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar avatar-45 avatar-rounded bg-primary d-flex align-items-center justify-content-center text-white fw-bold shadow-sm">
                    {{ substr(auth()->user()->name ?? 'I', 0, 1) }}
                </div>
                <div class="caption mx-2 d-none d-md-block">
                  <h6 class="mb-0 caption-title fw-bold text-dark">{{ auth()->user()->name ?? __('instructor::sidebar.instructor') }}</h6>
                  <p class="mb-0 caption-sub-title text-muted extra-small text-capitalize">{{ __('instructor::sidebar.instructor') }}</p>
                </div>
            </a>
            
            <form method="POST" action="{{ route('center.logout') }}" class="ms-1">
              @csrf
              <button type="submit" class="btn btn-sm btn-icon btn-soft-danger rounded-circle border-0 shadow-none hover-lift" title="{{ __('instructor::sidebar.logout') }}" style="width: 32px; height: 32px;">
                  <i class="fas fa-power-off fa-xs"></i>
              </button>
            </form>
          </div>
          <ul class="dropdown-menu dropdown-menu-end py-2" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{ route('instructor.settings') }}">
                <i class="fas fa-cog me-2"></i> {{ __('instructor::sidebar.settings') }}
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('center.logout') }}">
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
