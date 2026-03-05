@extends('center::layouts.master')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">{{ __('center::messages.blade_0536') }}</h2>
            <p class="text-muted mb-0">{{ __('center::messages.blade_0537') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('center.questions.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-database-fill me-2"></i>{{ __('center::messages.blade_0538') }}</a>
            <a href="{{ route('center.courses.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm" title="{{ __('center::messages.blade_0551') }}">
                <i class="bi bi-plus-circle-fill me-2"></i>{{ __('center::messages.blade_0539') }}</a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-journal-text text-primary fs-3"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">{{ __('center::messages.blade_0540') }}</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalQuizzesCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-people-fill text-success fs-3"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">{{ __('center::messages.blade_0541') }}</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalAttemptsCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-trophy-fill text-warning fs-3"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">{{ __('center::messages.blade_0542') }}</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($avgPassingRate, 1) }}%</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quizzes List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="bi bi-journal-check me-2 text-primary"></i>{{ __('center::messages.blade_0543') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light bg-opacity-50">
                                <tr>
                                    <th class="border-0 rounded-start text-muted fw-semibold">{{ __('center::messages.blade_1078') }}</th>
                                    <th class="border-0 text-muted fw-semibold">{{ __('center::messages.blade_0544') }}</th>
                                    <th class="border-0 text-muted fw-semibold">{{ __('center::messages.blade_0545') }}</th>
                                    <th class="border-0 text-center text-muted fw-semibold">{{ __('center::messages.blade_0546') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quizzes as $quiz)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $quiz->title }}</div>
                                            <small class="text-muted d-block mt-1">
                                                <i class="bi bi-book me-1"></i>{{ $quiz->lesson->section->course->title }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                                {{ $quiz->passing_score }}%
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted fw-medium">
                                                <i class="bi bi-clock me-1 text-primary"></i>
                                                {{ $quiz->duration_minutes ?? __('center::messages.blade_0554') }} {{ __('center::messages.blade_1079') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('center.quizzes.edit', $quiz) }}" class="btn btn-sm btn-outline-primary border-0 rounded-circle" title="{{ __('center::messages.blade_0552') }}">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>
                                                <a href="{{ route('center.quizzes.show', $quiz) }}" class="btn btn-sm btn-outline-secondary border-0 rounded-circle" title="{{ __('center::messages.blade_0553') }}">
                                                    <i class="bi bi-eye fs-5"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="bi bi-journal-x fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                                <p class="text-muted mb-0">{{ __('center::messages.blade_0547') }}</p>
                                                <a href="{{ route('center.courses.index') }}" class="btn btn-link text-primary mt-2">{{ __('center::messages.blade_0548') }}</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Attempts -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i>{{ __('center::messages.blade_0549') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        @forelse($recentAttempts as $attempt)
                            <div class="list-group-item px-0 border-0 mb-3 bg-transparent">
                                <div class="d-flex align-items-center p-2 rounded-3 hover-bg-light transition-all">
                                    <div class="flex-shrink-0">
                                        <div class="bg-{{ $attempt->passed ? 'success' : 'danger' }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                            <i class="bi bi-{{ $attempt->passed ? 'person-check' : 'person-x' }} fs-5 text-{{ $attempt->passed ? 'success' : 'danger' }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="fw-bold text-dark small mb-0">{{ $attempt->user->name }}</div>
                                        <div class="text-muted text-truncate" style="font-size: 0.75rem; max-width: 150px;">{{ $attempt->quiz->title }}</div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="badge bg-{{ $attempt->passed ? 'success' : 'danger' }} bg-opacity-10 text-{{ $attempt->passed ? 'success' : 'danger' }} p-0 px-2" style="font-size: 0.65rem;">
                                                {{ $attempt->passed ? __('center::messages.blade_0555') : __('center::messages.blade_0556') }}
                                            </span>
                                            <small class="text-muted" style="font-size: 0.65rem;"><i class="bi bi-clock-history me-1"></i>{{ $attempt->completed_at ? $attempt->completed_at->diffForHumans() : __('center::messages.blade_0557') }}</small>
                                        </div>
                                    </div>
                                    <div class="ms-auto text-end">
                                        <div class="fw-bold text-{{ $attempt->passed ? 'success' : 'danger' }} fs-5">{{ number_format($attempt->score, 0) }}%</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-graph-up fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                <p class="text-muted small mb-0">{{ __('center::messages.blade_0550') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-bg-light:hover { background-color: rgba(0,0,0, 0.02); }
        .transition-all { transition: all 0.2s ease; }
    </style>
@endsection
