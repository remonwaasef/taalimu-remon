<div class="card glass-card border-0 rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h4 class="fw-bold mb-2">🚀 {{ __('center::dashboard.launchpad.title', ['name' => auth()->user()->name]) }}</h4>
                <p class="text-muted mb-4">{{ __('center::dashboard.launchpad.subtitle') }}</p>

                <div class="progress mb-3" style="height: 10px; border-radius: 10px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $launchpadProgress }}%" aria-valuenow="{{ $launchpadProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between text-muted small fw-bold mb-4">
                    <span>{{ __('center::dashboard.launchpad.progress') }}</span>
                    <span>{{ $launchpadProgress }}%</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-3 {{ $launchpadSteps['profile'] ? 'bg-success bg-opacity-10 text-success' : 'bg-light text-muted' }}">
                            <div class="flex-shrink-0">
                                <i class="fas {{ $launchpadSteps['profile'] ? 'fa-check-circle' : 'fa-circle' }} fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold">{{ __('center::dashboard.launchpad.steps.profile') }}</h6>
                            </div>
                            @if(!$launchpadSteps['profile'])
                                <a href="{{ route('center.settings.index') }}" class="btn btn-sm btn-white shadow-sm rounded-pill fw-bold">{{ __('center::dashboard.launchpad.action') }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-3 {{ $launchpadSteps['instructor'] ? 'bg-success bg-opacity-10 text-success' : 'bg-light text-muted' }}">
                            <div class="flex-shrink-0">
                                <i class="fas {{ $launchpadSteps['instructor'] ? 'fa-check-circle' : 'fa-circle' }} fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold">{{ __('center::dashboard.launchpad.steps.instructor') }}</h6>
                            </div>
                            @if(!$launchpadSteps['instructor'])
                                <a href="{{ route('center.instructors.create') }}" class="btn btn-sm btn-white shadow-sm rounded-pill fw-bold">{{ __('center::dashboard.launchpad.action') }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-3 {{ $launchpadSteps['course'] ? 'bg-success bg-opacity-10 text-success' : 'bg-light text-muted' }}">
                            <div class="flex-shrink-0">
                                <i class="fas {{ $launchpadSteps['course'] ? 'fa-check-circle' : 'fa-circle' }} fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold">{{ __('center::dashboard.launchpad.steps.course') }}</h6>
                            </div>
                            @if(!$launchpadSteps['course'])
                                <a href="{{ route('center.courses.create') }}" class="btn btn-sm btn-white shadow-sm rounded-pill fw-bold">{{ __('center::dashboard.launchpad.action') }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-3 {{ $launchpadSteps['student'] ? 'bg-success bg-opacity-10 text-success' : 'bg-light text-muted' }}">
                            <div class="flex-shrink-0">
                                <i class="fas {{ $launchpadSteps['student'] ? 'fa-check-circle' : 'fa-circle' }} fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold">{{ __('center::dashboard.launchpad.steps.student') }}</h6>
                            </div>
                            @if(!$launchpadSteps['student'])
                                <a href="{{ route('center.students.create') }}" class="btn btn-sm btn-white shadow-sm rounded-pill fw-bold">{{ __('center::dashboard.launchpad.action') }}</a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-4 d-none d-lg-block text-center">
                <i class="fas fa-rocket text-primary opacity-25" style="font-size: 10rem;"></i>
            </div>
        </div>
    </div>
</div>
