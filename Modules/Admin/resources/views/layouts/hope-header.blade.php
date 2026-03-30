<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
  <div class="container-fluid navbar-inner">
    <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
       <div class="mb-2 mx-auto bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 45px; height: 45px; font-size: 1.2rem; background: linear-gradient(135deg, #2A4DFF 0%, #4361EE 100%) !important;">
            {{ substr(\App\Models\SiteSetting::get('site_name', 'T'), 0, 1) }}
        </div>
      <h4 class="logo-title ms-2 text-truncate" style="max-width: 150px;">{{ \App\Models\SiteSetting::get('site_name', 'EduCentral') }}</h4>
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
          <a href="#" class="nav-link" id="langDropdown" aria-expanded="false" onclick="toggleCustomDropdown(event, this)">
             @php
                $currentLocale = app()->getLocale();
                $locales = [
                    'ar' => ['name' => 'العربية', 'flag' => '🇸🇦'],
                    'en' => ['name' => 'English', 'flag' => '🇺🇸'],
                    'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
                ];
            @endphp
            <span class="me-1">{{ $locales[$currentLocale]['flag'] }}</span>
            <span class="d-none d-md-inline">{{ $locales[$currentLocale]['name'] }}</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="langDropdown">
              @foreach($locales as $code => $lang)
                  <li>
                      <a class="dropdown-item {{ $currentLocale == $code ? 'active' : '' }}" href="{{ route('lang.switch', $code) }}">
                          <span class="me-2">{{ $lang['flag'] }}</span>
                          {{ $lang['name'] }}
                      </a>
                  </li>
              @endforeach
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link py-0 d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="avatar avatar-50 avatar-rounded bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="background: linear-gradient(135deg, #3A0CA3 0%, #2A4DFF 100%) !important;">
                  {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
              </div>
              <div class="caption ms-3 d-none d-md-block ">
                <h6 class="mb-0 caption-title">{{ auth()->user()->name }}</h6>
                <p class="mb-0 caption-sub-title text-capitalize">{{ __('admin::admin.sidebar.admin') }}</p>
              </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end py-2" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{ route('admin.users.edit', auth()->id()) }}">
                <i class="fas fa-user-circle me-2"></i> {{ __('admin::admin.sidebar.profile') }}
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <a href="javascript:void(0)" class="dropdown-item text-danger" onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> {{ __('admin.sidebar.logout') }}
                </a>
              </form>
            </li>
          </ul>
        </li>

      </ul>
    </div>
  </div>
</nav>

@if(session()->has('impersonator_id'))
    <div class="alert alert-warning mb-0 rounded-0 border-0 p-2 d-flex justify-content-between align-items-center" style="z-index: 1050; position: relative;">
        <div>
            <i class="fas fa-user-secret me-2"></i> {!! __('admin::admin.impersonation.alert', ['name' => '<strong>' . auth()->user()->name . '</strong>']) !!}
        </div>
        <a href="{{ route('admin.impersonate.stop') }}" class="btn btn-dark btn-sm rounded-pill px-4 fw-bold">
            <i class="fas fa-sign-out-alt me-1"></i> {{ __('admin::admin.impersonation.stop') }}
        </a>
    </div>
@endif
