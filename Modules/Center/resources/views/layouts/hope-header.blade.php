<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
  <div class="container-fluid navbar-inner">
    <a href="{{ route('center.dashboard', ['tenant' => $tenant->domain ?? 'center']) }}" class="navbar-brand">
       @if($tenant->logo)
            <img src="{{ asset('storage/' . $tenant->logo) }}" class="rounded-3 shadow-sm p-1" style="max-height: 45px; max-width: 100%;">
        @else
            <div class="mb-2 mx-auto bg-white rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 45px; height: 45px; font-size: 1.2rem;">
                {{ substr($tenant->name ?? 'T', 0, 1) }}
            </div>
        @endif
      <h4 class="logo-title ms-2 text-truncate" style="max-width: 150px;">{{ $tenant->name ?? __('sidebar.center_name') }}</h4>
    </a>
    <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
      <i class="icon">
        <svg width="20px" height="20px" viewBox="0 0 24 24">
          <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
      </svg>
      </i>
    </div>


    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      <span class="navbar-toggler-icon">
        <span class="navbar-toggler-bar bar1 mt-2"></span>
        <span class="navbar-toggler-bar bar2"></span>
        <span class="navbar-toggler-bar bar3"></span>
      </span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Global Search removed as per user request -->

      <ul class="navbar-nav ms-auto  navbar-list mb-2 mb-lg-0 align-items-center">

        <!-- Quick Actions (Power UX) -->
        <li class="nav-item dropdown me-3">
            <a href="#" class="nav-link btn btn-primary text-white rounded-circle d-flex align-items-center justify-content-center p-0 shadow-sm transition-all" style="width: 38px; height: 38px; background: var(--bs-primary);" id="quick-actions-drop" data-bs-toggle="dropdown" aria-expanded="false" title="إجراءات سريعة">
                <i class="fas fa-plus"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2 mt-2" aria-labelledby="quick-actions-drop" style="min-width: 220px;">
                <li><h6 class="dropdown-header text-muted fw-bold small text-uppercase">إجراء سريع</h6></li>
                @can('add students')
                <li><a class="dropdown-item rounded-3 py-2 mb-1 transition-all" href="{{ route('center.students.create', ['tenant' => $tenant->domain ?? 'center']) }}"><i class="fas fa-user-plus me-3 text-primary opacity-75"></i> طالب جديد</a></li>
                @endcan
                @can('add sales')
                <li><a class="dropdown-item rounded-3 py-2 mb-1 transition-all" href="{{ route('center.sales.create', ['tenant' => $tenant->domain ?? 'center']) }}"><i class="fas fa-file-invoice-dollar me-3 text-success opacity-75"></i> فاتورة/سداد</a></li>
                @endcan
                @can('add attendance')
                <li><a class="dropdown-item rounded-3 py-2 mb-1 transition-all" href="{{ route('center.attendance.index', ['tenant' => $tenant->domain ?? 'center']) }}"><i class="fas fa-calendar-check me-3 text-warning opacity-75"></i> تسجيل حضور</a></li>
                @endcan
                @can('add messages')
                <li><a class="dropdown-item rounded-3 py-2 mb-1 transition-all" href="{{ route('center.students.index', ['tenant' => $tenant->domain ?? 'center']) }}"><i class="fab fa-whatsapp me-3 text-success opacity-75"></i> إرسال رسالة</a></li>
                @endcan
            </ul>
        </li>

        @auth
          @can('view sales')
            @php
                $overdueCount = $tenant->getOverdueStudentsCount();
            @endphp
            <li class="nav-item me-3">
              <a href="{{ route('center.sales.overdue', ['tenant' => $tenant->domain ?? 'center']) }}" class="btn btn-outline-danger d-flex align-items-center" aria-current="page" style="top: 4px;" title="{{ __('center::dashboard.header.overdue_invoices') }}">
                  <i class="fas fa-wallet me-2"></i>
                  @if($overdueCount > 0)
                      <span class="badge rounded-pill bg-danger ms-1 animate__animated animate__pulse animate__infinite">
                          {{ $overdueCount }}
                      </span>
                  @endif
              </a>
            </li>
          @endcan

          <li class="nav-item dropdown">
            <a href="#"  class="nav-link" id="notification-drop" data-bs-toggle="dropdown" data-bs-boundary="viewport">
              <svg width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19.7695 11.6453C19.039 10.7923 18.7071 10.0531 18.7071 8.79716V8.37013C18.7071 6.73354 18.3304 5.67907 17.5115 4.62459C16.2493 2.98699 14.1244 2 12.0442 2H11.9558C9.91935 2 7.86106 2.94167 6.577 4.5128C5.71333 5.58842 5.29293 6.68822 5.29293 8.37013V8.79716C5.29293 10.0531 4.98284 10.7923 4.23049 11.6453C3.67691 12.2738 3.5 13.0815 3.5 13.9557C3.5 14.8309 3.78723 15.6598 4.36367 16.3336C5.11602 17.1413 6.17846 17.6569 7.26375 17.7466C8.83505 17.9258 10.4063 17.9933 12.0005 17.9933C13.5937 17.9933 15.165 17.8805 16.7372 17.7466C17.8215 17.6569 18.884 17.1413 19.6363 16.3336C20.2118 15.6598 20.5 14.8309 20.5 13.9557C20.5 13.0815 20.3231 12.2738 19.7695 11.6453Z" fill="currentColor"></path>
                <path opacity="0.4" d="M14.0088 19.2283C13.5088 19.1215 10.4627 19.1215 9.96275 19.2283C9.53539 19.327 9.07324 19.5566 9.07324 20.0602C9.09809 20.5406 9.37935 20.9646 9.76895 21.2335L9.76795 21.2345C10.2718 21.6273 10.8632 21.877 11.4824 21.9667C11.8123 22.012 12.1482 22.01 12.4901 21.9667C13.1083 21.877 13.6997 21.6273 14.2036 21.2345L14.2026 21.2335C14.5922 20.9646 14.8734 20.5406 14.8983 20.0602C14.8983 19.5566 14.4361 19.327 14.0088 19.2283Z" fill="currentColor"></path>
              </svg>
              @if(auth()->user()->unreadNotifications->count() > 0)
                  <span class="dots" style="background: #ef4444 !important; outline: 2px solid white;"></span>
              @endif
            </a>
            <div class="sub-drop dropdown-menu dropdown-menu-end p-0" aria-labelledby="notification-drop">
              <div class="card shadow-none m-0">
                <div class="card-header d-flex justify-content-between py-3" style="background: var(--bs-primary) !important;">
                  <div class="header-title">
                    <h5 class="mb-0 text-white">{{ __('center::sidebar.notifications') }}</h5>
                  </div>
                  @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('center.notifications.readAll') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link btn-sm text-white p-0" style="font-size: 0.8rem;">
                            {{ __('center::sidebar.mark_all_read') }}
                        </button>
                    </form>
                  @endif
                </div>
                <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                  <a href="{{ route('center.notifications.index') }}" class="iq-sub-card text-center text-primary fw-bold">
                    {{ __('center::sidebar.view_all') }}
                  </a>
                  @forelse(auth()->user()->notifications->take(5) as $notification)
                      @php
                          $titleKey = $notification->data['title'];
                          $iconColor = 'primary';
                          $iconClass = $notification->data['icon'] ?? 'fas fa-bell';
                          
                          if (str_contains($titleKey, 'registered') || str_contains($titleKey, 'created')) {
                              $iconColor = 'success';
                          } elseif (str_contains($titleKey, 'updated') || str_contains($titleKey, 'edited')) {
                              $iconColor = 'info';
                          } elseif (str_contains($titleKey, 'deleted') || str_contains($titleKey, 'removed')) {
                              $iconColor = 'danger';
                          }
                          
                          $translatedTitle = __('center::sidebar.' . $titleKey);
                          if ($translatedTitle === 'center::sidebar.' . $titleKey) {
                              $translatedTitle = $titleKey;
                          }
                      @endphp
                      <a href="{{ route('center.notifications.read', $notification->id) }}" class="iq-sub-card {{ $notification->read_at ? '' : 'bg-light' }}">
                        <div class="d-flex align-items-center">
                          <div class="rounded-circle bg-soft-{{ $iconColor }} d-flex align-items-center justify-content-center p-2" style="width: 40px; height: 40px;">
                              <i class="{{ $iconClass }} text-{{ $iconColor }}"></i>
                          </div>
                          <div class="ms-3 w-100">
                            <h6 class="mb-0 ">{{ $translatedTitle }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                              <p class="mb-0 text-truncate" style="max-width: 150px; font-size:12px;">{{ $notification->data['message'] ?? '' }}</p>
                              <small class="float-right font-size-12">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                          </div>
                        </div>
                      </a>
                  @empty
                      <div class="p-4 text-center text-muted">
                          <i class="fas fa-bell-slash fa-2x mb-2 text-secondary"></i>
                          <p class="mb-0 small">{{ __('center::sidebar.no_notifications') }}</p>
                      </div>
                  @endforelse
                </div>
              </div>
            </div>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link py-0 d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
              @if(auth()->user()->image)
                  <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded">
              @else
                  <div class="avatar avatar-50 avatar-rounded d-flex align-items-center justify-content-center text-white fw-bold" style="background: var(--bs-primary) !important;">
                      {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                  </div>
              @endif
              <div class="caption ms-3 d-none d-md-block {{ app()->isLocale('ar') ? 'me-3 ms-0' : '' }}">
                <h6 class="mb-0 caption-title">{{ auth()->user()->name }}</h6>
                <p class="mb-0 caption-sub-title text-capitalize">{{ auth()->user()->role ?? __('center::sidebar.admin') }}</p>
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="{{ route('center.profile', ['tenant' => $tenant->domain ?? 'center']) }}">{{ __('center::sidebar.profile') }}</a></li>
              <li><a class="dropdown-item" href="{{ route('center.settings.index', ['tenant' => $tenant->domain ?? 'center']) }}">{{ __('center::sidebar.settings') }}</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('center.logout', ['tenant' => $tenant->domain ?? 'center']) }}">
                  @csrf
                  <a href="javascript:void(0)" class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">
                      {{ __('center::sidebar.logout') }}
                  </a>
                </form>
              </li>
            </ul>
          </li>
        @endauth

      </ul>
    </div>
  </div>
</nav>

@if(session()->has('impersonator_id'))
    <div class="alert alert-warning mb-0 rounded-0 border-0 p-2 d-flex justify-content-between align-items-center" style="z-index: 1050; position: relative;">
        <div>
            <i class="fas fa-user-secret me-2"></i>{{ __('center::dashboard.header.browsing_as') }}<strong>{{ auth()->user()->name }}</strong>
        </div>
        <a href="{{ route('admin.impersonate.stop') }}" class="btn btn-dark btn-sm rounded-pill px-3">
            <i class="fas fa-sign-out-alt me-1"></i>{{ __('center::dashboard.header.logout') }}
        </a>
    </div>
@endif
