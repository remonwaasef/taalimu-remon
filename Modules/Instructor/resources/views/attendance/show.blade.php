<x-instructor::layouts.master>
@php
    $isEnded = now()->isAfter(\Carbon\Carbon::parse($schedule->end_time));
    $hasUnrecorded = $schedule->course->enrollments->count() > $attendances->count();
@endphp

@section('page-title', __('instructor::attendance.review_attendance'))
@section('page-subtitle', __('instructor::attendance.mark_attendance') . ': ' . $schedule->course->title)

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <!-- Scan Button -->
        <a href="{{ route('instructor.scanner', $schedule->course) }}" class="btn btn-glass shadow-sm">
            <i class="fas fa-qrcode me-2"></i> {{ __('instructor::attendance.scan_student_card') }}
        </a>

        @if($isEnded && $hasUnrecorded)
            <form action="{{ route('center.attendance.bulkAbsent', $schedule) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-glass text-white shadow-sm" style="background: rgba(220, 53, 69, 0.2) !important;">
                    <i class="fas fa-user-xmark me-2"></i> {{ __('instructor::attendance.mark_remaining_absent') }}
                </button>
            </form>
        @endif
        <span class="btn btn-glass cursor-default opacity-100">
            <i class="fas fa-calendar-alt me-2"></i> {{ today()->format('Y-m-d') }}
        </span>
    </div>
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-1"><i class="fas fa-users-viewfinder me-2"></i>{{ __('instructor::attendance.enrolled_students') }}</h5>
                    <p class="text-muted small mb-0">{{ __('instructor::attendance.session_details', [
                        'time' => \Carbon\Carbon::parse($schedule->start_time)->format('h:i A'),
                        'hall' => $schedule->classroom->name ?? __('instructor::attendance.classroom_not_specified')
                    ]) }}</p>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">{{ __('instructor::attendance.student') }}</th>
                                    <th class="border-0">{{ __('instructor::attendance.student_code') }}</th>
                                    <th class="border-0 text-center">{{ __('instructor::attendance.today_status') }}</th>
                                    <th class="border-0 rounded-end text-center">{{ __('instructor::attendance.mark_attendance') }}</th>
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
                                                        {{ __('instructor::attendance.late_minutes', ['minutes' => $attendance->late_minutes]) }}
                                                    @elseif($attendance->status == 'present')
                                                        {{ __('instructor::attendance.present') }}
                                                    @else
                                                        {{ __('instructor::attendance.absent') }}
                                                    @endif
                                                    <small class="d-block text-muted" style="font-size: 0.6rem;">{{ $attendance->check_in_time->format('h:i A') }}</small>
                                                </span>
                                            @elseif($isEnded)
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">{{ __('instructor::attendance.absent') }}</span>
                                            @else
                                                <span class="text-muted small">{{ __('instructor::attendance.not_recorded') }}</span>
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
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'present' ? 'success' : 'outline-success' }} rounded-pill px-3" {{ $isEnded && (!$attendance || $attendance->status !== 'present') ? 'disabled' : '' }}>{{ __('instructor::attendance.present') }}</button>
                                                </form>
                                                
                                                <form action="{{ route('center.attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="late">
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'late' ? 'warning' : 'outline-warning' }} rounded-pill px-3" {{ $isEnded && (!$attendance || $attendance->status !== 'late') ? 'disabled' : '' }}>{{ __('instructor::attendance.late') }}</button>
                                                </form>

                                                <form action="{{ route('center.attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="absent">
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'absent' ? 'danger' : 'outline-danger' }} rounded-pill px-3">{{ __('instructor::attendance.absent') }}</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">{{ __('instructor::attendance.no_students_enrolled') }}</td>
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
