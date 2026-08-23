@extends('campus::layouts.master')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary-color);">{{ __('campus::classes.title') }}</h4>
            <p class="text-muted small mb-0">{{ __('campus::classes.subtitle') }}</p>
        </div>
    </div>

    {{-- Live Now Section --}}
    @if($liveClasses->count())
    <div class="mb-4">
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <span class="badge bg-danger rounded-pill px-3 animate__animated animate__pulse">
                <i class="fas fa-circle me-1"></i> {{ __('campus::classes.live_now') }}
            </span>
        </h5>
        <div class="row g-3">
            @foreach($liveClasses as $class)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-danger border-2">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="fas fa-video fs-5"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $class->title }}</h6>
                                <div class="text-muted small mb-2">{{ $class->course->title }}</div>
                                <div class="d-flex gap-2 small text-muted">
                                    <span><i class="fas fa-clock me-1"></i> {{ $class->start_time->format('H:i') }}</span>
                                    <span><i class="fas fa-user-tie me-1"></i> {{ $class->instructor->name ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 d-grid">
                            <a href="{{ route('campus.classes.join', $class) }}" class="btn btn-danger rounded-pill px-4 fw-bold">
                                <i class="fas fa-play-circle me-2"></i> {{ __('campus::classes.join_now') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Upcoming Classes --}}
    @if($upcoming->count())
    <div class="mb-4">
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="fas fa-calendar-alt text-warning me-2"></i> {{ __('campus::classes.upcoming') }}
        </h5>
        <div class="row g-3">
            @foreach($upcoming as $class)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="fas fa-clock fs-5"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $class->title }}</h6>
                                <div class="text-muted small mb-2">{{ $class->course->title }}</div>
                                <div class="d-flex gap-2 small text-muted">
                                    <span><i class="fas fa-calendar me-1"></i> {{ $class->start_time->format('Y-m-d H:i') }}</span>
                                    <span><i class="fas fa-clock me-1"></i> {{ $class->duration_minutes }} دق</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recorded Lessons --}}
    <div>
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                <i class="fas fa-clapperboard text-primary me-2"></i> {{ __('campus::classes.recorded_lessons') }}
            </h5>
        </div>

        @if($recordings->count())
        <div class="row g-3">
            @foreach($recordings as $rec)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="fw-bold mb-2" style="color: var(--primary-color);">{{ $rec->onlineClass->title }}</div>
                        <div class="text-muted small mb-3">{{ $rec->onlineClass->course->title }}</div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">{{ __('campus::classes.progress') }}</span>
                                <span class="fw-bold">{{ $rec->user_progress ? $rec->user_progress->completion_percentage : 0 }}%</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                @php $pct = $rec->user_progress ? $rec->user_progress->completion_percentage : 0; @endphp
                                <div class="progress-bar bg-{{ $pct >= 100 ? 'success' : 'primary' }}" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between small text-muted mb-2">
                            <span>{{ number_format(($rec->duration_seconds ?? 0) / 60, 1) }} {{ __('campus::classes.minutes') }}</span>
                            <span>{{ $rec->available_at->format('Y-m-d') }}</span>
                        </div>

                        <div class="d-grid">
                            @if($rec->user_progress && $rec->user_progress->completion_percentage >= 95)
                                <span class="btn btn-success rounded-pill px-4 disabled">
                                    <i class="fas fa-check-circle me-2"></i> {{ __('campus::classes.completed') }}
                                </span>
                            @elseif($rec->user_progress && $rec->user_progress->last_position_seconds > 0)
                                <a href="{{ route('campus.recordings.watch', $rec) }}" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-redo me-2"></i> {{ __('campus::classes.continue') }}
                                </a>
                            @else
                                <a href="{{ route('campus.recordings.watch', $rec) }}" class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="fas fa-play me-2"></i> {{ __('campus::classes.start_watching') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-clapperboard fa-3x text-muted mb-3"></i>
                <h6 class="text-muted fw-bold">{{ __('campus::classes.no_recordings') }}</h6>
                <p class="text-muted small">{{ __('campus::classes.no_recordings_desc') }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection