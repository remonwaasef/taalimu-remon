@extends('center::layouts.hope-master')

@section('page-title', __('center::messages.blade_0536'))
@section('page-subtitle', __('center::messages.blade_0537'))

@section('page-actions')
    <a href="{{ route('center.questions.index') }}" class="btn btn-light shadow-sm">
        <i class="fas fa-database me-2"></i>{{ __('center::messages.blade_0538') }}
    </a>
    <a href="{{ route('center.courses.index') }}" class="btn btn-primary shadow-sm" title="{{ __('center::messages.blade_0551') }}">
        <i class="fas fa-plus-circle me-2"></i>{{ __('center::messages.blade_0539') }}
    </a>
@endsection

@section('content')

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 0.5px;">{{ __('center::messages.blade_0540') }}</p>
                            <h2 class="fw-bolder text-dark mb-0">{{ number_format($totalQuizzesCount) }}</h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; background: #ecfdf5;">
                            <i class="fas fa-file-alt fa-lg" style="color: #059669;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 0.5px;">{{ __('center::messages.blade_0541') }}</p>
                            <h2 class="fw-bolder text-dark mb-0">{{ number_format($totalAttemptsCount) }}</h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; background: #f0f9ff;">
                            <i class="fas fa-users fa-lg" style="color: #0284c7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 0.5px;">{{ __('center::messages.blade_0542') }}</p>
                            <h2 class="fw-bolder text-dark mb-0">{{ number_format($avgPassingRate, 1) }}%</h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; background: #fffbeb;">
                            <i class="fas fa-trophy fa-lg" style="color: #d97706;"></i>
                        </div>
                    </div>
                    <!-- Mini progress bar -->
                    <div class="mt-3">
                        <div class="progress" style="height: 6px; border-radius: 6px; background: #f1f5f9;">
                            <div class="progress-bar" role="progressbar" style="width: {{ min($avgPassingRate, 100) }}%; background: linear-gradient(90deg, #10b981, #34d399); border-radius: 6px;" aria-valuenow="{{ $avgPassingRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quizzes List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-header bg-white border-0 p-4 pb-3 d-flex justify-content-between align-items-center" style="border-radius: 1.25rem 1.25rem 0 0;">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-clipboard-list me-2" style="color: #10b981;"></i>{{ __('center::messages.blade_0543') }}</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('center::messages.blade_1078') }}</th>
                                    <th>{{ __('center::messages.blade_0544') }}</th>
                                    <th>{{ __('center::messages.blade_0545') }}</th>
                                    <th class="text-center">{{ __('center::messages.blade_0546') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quizzes as $quiz)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background: #f0fdf4;">
                                                    <i class="fas fa-file-alt" style="color: #10b981;"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $quiz->title }}</div>
                                                    <small class="text-muted"><i class="fas fa-book me-1"></i>{{ $quiz->lesson->section->course->title }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill px-3 py-2" style="background: #ecfdf5; color: #059669;">
                                                {{ $quiz->passing_score }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted fw-medium small">
                                                <i class="fas fa-clock me-1" style="color: #10b981;"></i>
                                                {{ $quiz->duration_minutes ?? __('center::messages.blade_0554') }} {{ __('center::messages.blade_1079') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('center.quizzes.edit', $quiz) }}" class="btn btn-sm btn-light" title="{{ __('center::messages.blade_0552') }}">
                                                    <i class="fas fa-pen" style="color: #10b981;"></i>
                                                </a>
                                                <a href="{{ route('center.quizzes.show', $quiz) }}" class="btn btn-sm btn-light" title="{{ __('center::messages.blade_0553') }}">
                                                    <i class="fas fa-eye" style="color: #64748b;"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="py-4">
                                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: #f1f5f9;">
                                                    <i class="fas fa-inbox fa-2x" style="color: #cbd5e1;"></i>
                                                </div>
                                                <p class="text-muted fw-medium mb-2">{{ __('center::messages.blade_0547') }}</p>
                                                <a href="{{ route('center.courses.index') }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus me-1"></i>{{ __('center::messages.blade_0548') }}
                                                </a>
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
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-header bg-white border-0 p-4 pb-3" style="border-radius: 1.25rem 1.25rem 0 0;">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-bolt me-2" style="color: #f59e0b;"></i>{{ __('center::messages.blade_0549') }}</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    @forelse($recentAttempts as $attempt)
                        <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded-3" style="background: #f8fafc; transition: all 0.2s ease;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: {{ $attempt->passed ? '#ecfdf5' : '#fef2f2' }};">
                                <i class="fas fa-{{ $attempt->passed ? 'check' : 'times' }}" style="color: {{ $attempt->passed ? '#059669' : '#dc2626' }};"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-bold text-dark small mb-0">{{ $attempt->user->name }}</div>
                                <div class="text-muted text-truncate" style="font-size: 0.75rem; max-width: 140px;">{{ $attempt->quiz->title }}</div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="badge rounded-pill px-2" style="font-size: 0.65rem; background: {{ $attempt->passed ? '#ecfdf5' : '#fef2f2' }}; color: {{ $attempt->passed ? '#059669' : '#dc2626' }};">
                                        {{ $attempt->passed ? __('center::messages.blade_0555') : __('center::messages.blade_0556') }}
                                    </span>
                                    <small class="text-muted" style="font-size: 0.65rem;"><i class="fas fa-clock me-1"></i>{{ $attempt->completed_at ? $attempt->completed_at->diffForHumans() : __('center::messages.blade_0557') }}</small>
                                </div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <div class="fw-bolder fs-5" style="color: {{ $attempt->passed ? '#059669' : '#dc2626' }};">{{ number_format($attempt->score, 0) }}%</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: #f1f5f9;">
                                <i class="fas fa-chart-bar fa-2x" style="color: #cbd5e1;"></i>
                            </div>
                            <p class="text-muted small fw-medium mb-0">{{ __('center::messages.blade_0550') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
