@extends('layouts.app-next')

@section('title', $recording->onlineClass->title ?? 'تسجيل')

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'recordings'])
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary-color);">{{ $recording->onlineClass->title }}</h4>
            <div class="text-muted small">{{ $recording->onlineClass->course->title ?? '' }} | {{ $recording->available_at->format('Y-m-d H:i') }}</div>
        </div>
        <a href="{{ route('instructor.recordings.index') }}" class="btn btn-light rounded-pill px-4">
            <i class="fas fa-arrow-right me-2"></i> {{ __('instructor::recordings.back') }}
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center">
                    <div class="fs-1 fw-bold text-primary">{{ $analytics['unique_viewers'] }}</div>
                    <div class="text-muted small">{{ __('instructor::recordings.unique_viewers') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center">
                    <div class="fs-1 fw-bold text-success">{{ $analytics['total_views'] }}</div>
                    <div class="text-muted small">{{ __('instructor::recordings.total_views') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center">
                    <div class="fs-1 fw-bold text-info">{{ number_format($analytics['avg_watch_seconds'] / 60, 1) }}</div>
                    <div class="text-muted small">{{ __('instructor::recordings.avg_watch_min') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center">
                    <div class="fs-1 fw-bold" style="color: var(--primary-color);">{{ $analytics['completion_rate'] }}%</div>
                    <div class="text-muted small">{{ __('instructor::recordings.completion_rate') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0">
            <h6 class="fw-bold mb-0"><i class="fas fa-users me-2"></i> {{ __('instructor::recordings.student_details') }}</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>{{ __('instructor::recordings.student') }}</th>
                            <th>{{ __('instructor::recordings.watched') }}</th>
                            <th>{{ __('instructor::recordings.completion') }}</th>
                            <th>{{ __('instructor::recordings.last_position') }}</th>
                            <th>{{ __('instructor::recordings.last_watched') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recording->progress()->whereIn('user_id', $recording->onlineClass->allowedUserIds())->with('user.student')->get() as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle shadow-sm" style="width:32px;height:32px;font-size:0.75rem;">{{ substr($p->user->name, 0, 1) }}</div>
                                    <div>
                                        <div class="fw-semibold small">{{ $p->user->name }}</div>
                                        <div class="text-muted extra-small">{{ $p->user->student->code ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ number_format($p->watched_seconds / 60, 1) }} {{ __('instructor::recordings.minutes') }}</td>
                            <td>
                                <div class="progress" style="height: 6px; width: 120px;">
                                    <div class="progress-bar bg-{{ $p->completion_percentage >= 95 ? 'success' : 'primary' }}" style="width: {{ $p->completion_percentage }}%"></div>
                                </div>
                                <div class="text-muted extra-small mt-1">{{ $p->completion_percentage }}%</div>
                            </td>
                            <td>{{ gmdate('H:i:s', $p->last_position_seconds) }}</td>
                            <td>{{ $p->updated_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">{{ __('instructor::recordings.no_progress_data') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Non-viewers --}}
    @if($analytics['non_viewers'] > 0)
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0">
            <h6 class="fw-bold mb-0 text-warning"><i class="fas fa-user-slash me-2"></i> {{ __('instructor::recordings.not_watched') }} ({{ $analytics['non_viewers'] }})</h6>
        </div>
        <div class="card-body">
            <p class="text-muted small">{{ __('instructor::recordings.not_watched_desc') }}</p>
        </div>
    </div>
    @endif
</div>
@endsection