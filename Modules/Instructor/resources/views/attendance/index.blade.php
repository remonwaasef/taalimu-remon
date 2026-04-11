@extends('instructor::components.layouts.hope-master')

@section('page-title', __('instructor::attendance.title'))
@section('page-subtitle', __('instructor::attendance.subtitle'))

@section('content')
<div class="container-fluid">

    <div class="row g-4">
        <!-- Today's Sessions -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2" style="color: var(--primary-color);"></i>{{ __('instructor::attendance.today_sessions', ['date' => now()->format('Y-m-d')]) }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">{{ __('instructor::attendance.group') }}</th>
                                    <th class="border-0">{{ __('instructor::attendance.details') }}</th>
                                    <th class="border-0 text-center">{{ __('instructor::attendance.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todaySessions as $session)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $session->course->title }}</div>
                                            <small class="badge rounded-pill" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                                                {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} - 
                                                {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $session->classroom->name ?? __('instructor::attendance.classroom_not_specified') }}</div>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $sessionEnd = \Carbon\Carbon::parse($session->end_time);
                                                $isEnded = now()->isAfter($sessionEnd);
                                            @endphp
                                            <div class="d-flex justify-content-center gap-2">
                                                @if($isEnded)
                                                    <a href="{{ route('instructor.attendance.show', $session) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                        <i class="bi bi-person-x me-1"></i>{{ __('instructor::attendance.review_attendance') }}</a>
                                                @else
                                                    <a href="{{ route('instructor.attendance.show', $session) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 border-0" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                                                        <i class="bi bi-card-checklist me-1"></i>{{ __('instructor::attendance.manual_attendance') }}</a>
                                                    <a href="{{ route('instructor.scanner', $session->course) }}" class="btn btn-primary btn-sm rounded-pill px-3 border-0 shadow-sm" style="background: var(--primary-color);">
                                                        <i class="bi bi-qr-code me-1"></i> {{ __('instructor::attendance.qr_scan') }}
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">{{ __('instructor::attendance.no_sessions_today') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $todaySessions->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-ui-checks me-2 text-success"></i>{{ __('instructor::attendance.recent_activity') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        @forelse($recentAttendance as $record)
                            <div class="list-group-item px-0 border-0 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-{{ $record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger') }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person-check text-{{ $record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger') }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="fw-bold small">{{ $record->student->name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $record->course->title }}</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $record->check_in_time->diffForHumans() }}</small>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge bg-{{ $record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger') }} rounded-pill" style="font-size: 0.65rem;">
                                            @if($record->status == 'present')
                                                {{ __('instructor::attendance.present') }}
                                            @elseif($record->status == 'late')
                                                {{ __('instructor::attendance.late') }}
                                            @else
                                                {{ __('instructor::attendance.absent') }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">{{ __('instructor::attendance.no_recent_records') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
