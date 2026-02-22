@extends('center::layouts.master')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::messages.blade_0111') }}</h2>
        <p class="text-muted">{{ __('center::messages.blade_0112') }}</p>
    </div>

    <div class="row g-4">
        <!-- Today's Sessions -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>حصص اليوم ({{ now()->format('Y-m-d') }})</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">الحصة / الوقت</th>
                                    <th class="border-0">المعلم / القاعة</th>
                                    <th class="border-0 text-center">{{ __('center::messages.blade_0113') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todaySessions as $session)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $session->course->title }}</div>
                                            <small class="badge bg-primary bg-opacity-10 text-primary">
                                                {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} - 
                                                {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="small mb-1"><i class="bi bi-person me-1"></i>{{ $session->instructor->name ?? __('center::messages.blade_0119') }}</div>
                                            <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $session->classroom->name ?? __('center::schedules.classroom') }}</div>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $sessionEnd = \Carbon\Carbon::parse($session->end_time);
                                                $isEnded = now()->isAfter($sessionEnd);
                                            @endphp
                                            <div class="d-flex justify-content-center gap-2">
                                                @if($isEnded)
                                                    <a href="{{ route('center.attendance.show', $session) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                        <i class="bi bi-person-x me-1"></i>{{ __('center::messages.blade_0114') }}</a>
                                                @else
                                                    <a href="{{ route('center.attendance.show', $session) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                        <i class="bi bi-card-checklist me-1"></i>{{ __('center::messages.blade_0115') }}</a>
                                                    <a href="{{ route('center.attendance.qr', $session) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                                        <i class="bi bi-qr-code me-1"></i> عرض الـ QR
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">{{ __('center::messages.blade_0116') }}</td>
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
                    <h5 class="fw-bold mb-0"><i class="bi bi-ui-checks me-2 text-success"></i>{{ __('center::messages.blade_0117') }}</h5>
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
                                            {{ $record->status == 'present' ? __('center::messages.blade_0120') : ($record->status == 'late' ? __('center::messages.blade_0121') : __('center::messages.blade_0122')) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">{{ __('center::messages.blade_0118') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
