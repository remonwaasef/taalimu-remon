@php
    $steps = [
        'education_system' => ['icon' => 'fa-map-signs', 'color' => 'primary', 'route' => 'center.settings.index'],
        'instructor' => ['icon' => 'fa-user-tie', 'color' => 'info', 'route' => 'center.instructors.create'],
        'course' => ['icon' => 'fa-book-open', 'color' => 'warning', 'route' => 'center.courses.create'],
        'student' => ['icon' => 'fa-user-graduate', 'color' => 'success', 'route' => 'center.students.create'],
        'attendance' => ['icon' => 'fa-clipboard-check', 'color' => 'danger', 'route' => 'center.attendance.index'],
    ];

    // Find first incomplete step to highlight it
    $highlightStep = null;
    foreach($launchpadSteps as $key => $isDone) {
        if(!$isDone) {
            $highlightStep = $key;
            break;
        }
    }
@endphp

<div class="card glass-card border-0 rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(248,249,250,0.8));">
    <!-- Decorative background elements -->
    <div class="position-absolute top-0 end-0 p-3 opacity-10">
        <i class="fas fa-rocket fa-7x transform-rotate-15"></i>
    </div>

    <div class="card-body p-4 position-relative">
        <div class="row align-items-center mb-4">
            <div class="col-lg-7">
                <h3 class="fw-bold mb-2 text-dark">🚀 {{ __('center::dashboard.launchpad.title', ['name' => auth()->user()->name]) }}</h3>
                <p class="text-muted lead mb-0">{{ __('center::dashboard.launchpad.subtitle') }}</p>
            </div>
            <div class="col-lg-5">
                <div class="mt-3 mt-lg-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-primary">{{ __('center::dashboard.launchpad.progress') }}</span>
                        <span class="badge bg-primary rounded-pill px-3">{{ $launchpadProgress }}%</span>
                    </div>
                    <div class="progress shadow-sm" style="height: 12px; border-radius: 10px; background-color: rgba(0,0,0,0.05);">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                             role="progressbar" 
                             style="width: {{ $launchpadProgress }}%; border-radius: 10px;" 
                             aria-valuenow="{{ $launchpadProgress }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($steps as $key => $data)
                @php
                    $isCompleted = $launchpadSteps[$key] ?? false;
                    $isCurrent = ($key === $highlightStep);
                @endphp
                <div class="col-md-6 col-xl">
                    <div class="card h-100 border-0 shadow-sm rounded-4 transition-all hover-translate-y-n3 {{ $isCurrent ? 'border-primary border-2' : '' }} {{ $isCompleted ? 'bg-success bg-opacity-10' : 'bg-white' }}"
                         style="{{ $isCurrent ? 'box-shadow: 0 10px 25px rgba(13, 110, 253, 0.15) !important;' : '' }}">
                        <div class="card-body p-4 d-flex flex-column text-center">
                            <!-- Icon and Status Circle -->
                            <div class="position-relative mb-3 mx-auto">
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-{{ $isCompleted ? 'success' : ($isCurrent ? $data['color'] : 'light') }} text-{{ $isCompleted || $isCurrent ? 'white' : 'muted' }}" 
                                     style="width: 65px; height: 65px; font-size: 1.5rem; transition: all 0.3s ease;">
                                    <i class="fas {{ $isCompleted ? 'fa-check' : $data['icon'] }}"></i>
                                </div>
                                @if($isCurrent)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary border border-white">
                                        {{ __('center::dashboard.launchpad.action') }}
                                        <span class="visually-hidden">current step</span>
                                    </span>
                                @endif
                            </div>

                            <!-- Content -->
                            <h5 class="fw-bold mb-2 {{ $isCompleted ? 'text-success' : 'text-dark' }}">
                                {{ __('center::dashboard.launchpad.steps.'.$key.'.title') }}
                            </h5>
                            <p class="text-muted small mb-4 flex-grow-1">
                                {{ __('center::dashboard.launchpad.steps.'.$key.'.desc') }}
                            </p>

                            <!-- Button -->
                            @if($isCompleted)
                                <div class="text-success fw-bold small">
                                    <i class="fas fa-check-circle me-1"></i> تم الانجاز
                                </div>
                            @else
                                <a href="{{ route($data['route']) }}" 
                                   class="btn {{ $isCurrent ? 'btn-'.$data['color'] : 'btn-outline-light text-muted' }} rounded-pill btn-sm fw-bold px-4 py-2 mt-auto">
                                   {{ __('center::dashboard.launchpad.action') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .hover-translate-y-n3:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }
    .transform-rotate-15 {
        transform: rotate(-15deg);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>

