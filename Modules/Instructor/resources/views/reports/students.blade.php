@extends('instructor::components.layouts.hope-master')

@section('title', __('instructor::reports.student_reports'))
@section('page-title', __('instructor::reports.student_reports'))
@section('page-subtitle', __('instructor::reports.student_reports_subtitle'))

@section('content')
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">{{ __('instructor::reports.student_name') }}</th>
                        <th class="border-0">{{ __('instructor::reports.enrolled_courses') }}</th>
                        <th class="border-0 text-center">{{ __('instructor::reports.attendance_stats') }}</th>
                        <th class="border-0 text-center">{{ __('instructor::reports.progress') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-dark">{{ $student->name }}</div>
                                <small class="text-muted">{{ $student->phone }}</small>
                            </td>
                            <td>
                                @foreach($student->enrollments as $enrollment)
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 mb-1">{{ $enrollment->course->title }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <div class="fw-bold">{{ $student->attended_count }} / {{ $student->total_sessions }}</div>
                                <small class="text-muted">{{ __('instructor::reports.sessions') }}</small>
                            </td>
                            <td class="text-center" style="width: 200px;">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <div class="progress w-100" style="height: 6px; border-radius: 10px;">
                                        <div class="progress-bar bg-{{ $student->attendance_percentage > 75 ? 'success' : ($student->attendance_percentage > 40 ? 'warning' : 'danger') }}" 
                                             role="progressbar" style="width: {{ $student->attendance_percentage }}%"></div>
                                    </div>
                                    <span class="fw-bold small">{{ $student->attendance_percentage }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
