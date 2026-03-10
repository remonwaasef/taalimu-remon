<x-instructor::layouts.master>
@section('page-title', 'تفاصيل الحضور')
@section('content')
    <div class="mb-4">
        <h2 class="fw-bold text-dark">تسجيل الحضور: {{ $schedule->course->title }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('instructor.attendance.index') }}">الحضور والغياب</a></li>
                <li class="breadcrumb-item active">تحضير الطلاب</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1"><i class="bi bi-people me-2"></i>قائمة الطلاب المسجلين</h5>
                        <p class="text-muted small mb-0">الحصة {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - القاعة {{ $schedule->classroom->name ?? 'غير محددة' }}</p>
                    </div>
                    <div class="text-end d-flex align-items-center gap-2">
                        <!-- Scan Button -->
                        <a href="{{ route('instructor.scanner', $schedule->course) }}" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">
                            <i class="bi bi-qr-code-scan me-1"></i>مسح بطاقة الطالب</a>

                        @php
                            $isEnded = now()->isAfter(\Carbon\Carbon::parse($schedule->end_time));
                            $hasUnrecorded = $schedule->course->enrollments->count() > $attendances->count();
                        @endphp
                        @if($isEnded && $hasUnrecorded)
                            <form action="{{ route('center.attendance.bulkAbsent', $schedule) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
                                    <i class="bi bi-person-x-fill me-1"></i>تسجيل الباقي غياب</button>
                            </form>
                        @endif
                        <span class="badge px-3 rounded-pill fw-bold" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">{{ today()->format('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">الطالب</th>
                                    <th class="border-0">كود الطالب</th>
                                    <th class="border-0 text-center">حالة الحضور اليوم</th>
                                    <th class="border-0 rounded-end text-center">تسجيل الحضور</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedule->course->enrollments as $enrollment)
                                    @php
                                        $student = $enrollment->user->student ?? null;
                                        $attendance = $student ? $attendances->get($student->id) : null;
                                    @endphp
                                    @if($student)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $student->name }}</div>
                                            <small class="text-muted">{{ $enrollment->user->email }}</small>
                                        </td>
                                        <td><code style="color: var(--primary-color); font-weight: bold; background-color: rgba(58, 12, 163, 0.05); padding: 2px 6px; rounded: 4px;">#{{ $student->id }}</code></td>
                                        <td class="text-center">
                                            @if($attendance)
                                                <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'late' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'late' ? 'warning' : 'danger') }} rounded-pill px-3">
                                                    @if($attendance->status == 'late')
                                                    متأخر ({{ $attendance->late_minutes }} دقيقة)
                                                    @else
                                                        {{ $attendance->status == 'present' ? 'حاضر' : 'غائب' }}
                                                    @endif
                                                    <small class="d-block text-muted" style="font-size: 0.6rem;">{{ $attendance->check_in_time->format('h:i A') }}</small>
                                                </span>
                                            @elseif($isEnded)
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">غائب</span>
                                            @else
                                                <span class="text-muted small">لم يسجل بعد</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <form action="{{ route('center.attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="present">
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'present' ? 'success' : 'outline-success' }} rounded-pill px-3" {{ $isEnded && (!$attendance || $attendance->status !== 'present') ? 'disabled' : '' }}>حاضر</button>
                                                </form>
                                                
                                                <form action="{{ route('center.attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="late">
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'late' ? 'warning' : 'outline-warning' }} rounded-pill px-3" {{ $isEnded && (!$attendance || $attendance->status !== 'late') ? 'disabled' : '' }}>متأخر</button>
                                                </form>

                                                <form action="{{ route('center.attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="absent">
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'absent' ? 'danger' : 'outline-danger' }} rounded-pill px-3">غائب</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">لا يوجد طلاب مسجلين في هذه المجموعة.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
</x-instructor::layouts.master>
