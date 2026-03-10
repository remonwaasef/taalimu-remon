<x-instructor::layouts.master>
@section('page-title', 'الحضور والغياب')
@section('content')
    <div class="mb-4">
        <h2 class="fw-bold text-dark">الحضور والغياب</h2>
        <p class="text-muted">تسجيل حضور وغياب الطلاب في حصصك اليومية.</p>
    </div>

    <div class="row g-4">
        <!-- Today's Sessions -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2" style="color: var(--primary-color);"></i>حصص اليوم ({{ now()->format('Y-m-d') }})</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">المجموعة</th>
                                    <th class="border-0">التفاصيل</th>
                                    <th class="border-0 text-center">إجراءات</th>
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
                                            <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $session->classroom->name ?? 'قاعة غير محددة' }}</div>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $sessionEnd = \Carbon\Carbon::parse($session->end_time);
                                                $isEnded = now()->isAfter($sessionEnd);
                                            @endphp
                                            <div class="d-flex justify-content-center gap-2">
                                                @if($isEnded)
                                                    <a href="{{ route('instructor.attendance.show', $session) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                        <i class="bi bi-person-x me-1"></i>مراجعة الحضور</a>
                                                @else
                                                    <a href="{{ route('instructor.attendance.show', $session) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 border-0" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                                                        <i class="bi bi-card-checklist me-1"></i>تحضير يدوي</a>
                                                    <a href="{{ route('instructor.scanner', $session->course) }}" class="btn btn-primary btn-sm rounded-pill px-3 border-0 shadow-sm" style="background: var(--primary-color);">
                                                        <i class="bi bi-qr-code me-1"></i> مسح QR
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">لا توجد حصص مجدولة لهذا اليوم.</td>
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
                    <h5 class="fw-bold mb-0"><i class="bi bi-ui-checks me-2 text-success"></i>آخر التسجيلات</h5>
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
                                            {{ $record->status == 'present' ? 'حاضر' : ($record->status == 'late' ? 'متأخر' : 'غائب') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">لا توجد تسجيلات حضور حديثة.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
</x-instructor::layouts.master>
