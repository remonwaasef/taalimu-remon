@extends('layouts.app-next')

@section('title', __('instructor::reports.student_reports'))

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'reports'])
@endsection

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0 text-dark fw-bold" style="width: 50px;">#</th>
                        <th class="border-0 text-dark fw-bold">{{ __('instructor::reports.student_name') }}</th>
                        <th class="border-0 text-dark fw-bold">{{ __('instructor::reports.enrolled_courses') }}</th>
                        <th class="border-0 text-dark fw-bold text-center">{{ __('instructor::reports.attendance_stats') }}</th>
                        <th class="border-0 text-dark fw-bold text-center" style="min-width: 200px;">{{ __('instructor::reports.progress') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                        <tr>
                            <td class="px-4 py-3 text-muted fw-bold">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $student->name }}</div>
                                <div class="text-muted small"><i class="fas fa-phone-alt me-1 small"></i> {{ $student->phone }}</div>
                            </td>
                            <td>
                                @forelse($student->enrollments as $enrollment)
                                    <span class="badge bg-soft-primary text-primary rounded-pill px-3 py-2 mb-1 shadow-none border">
                                        <i class="fas fa-book-open me-1 small"></i> {{ $enrollment->course->title ?? __('instructor::reports.untitled_course') }}
                                    </span>
                                @empty
                                    <span class="text-muted small italic">{{ __('instructor::reports.no_courses') }}</span>
                                @endforelse
                            </td>
                            <td class="text-center">
                                <div class="d-inline-block bg-light rounded-3 px-3 py-2">
                                    <span class="fw-bold text-dark fs-5">{{ $student->attended_count }}</span>
                                    <span class="text-muted mx-1">/</span>
                                    <span class="text-muted small">{{ $student->total_sessions }}</span>
                                </div>
                                <div class="text-muted small mt-1">{{ __('instructor::reports.sessions') }}</div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <div class="progress w-100" style="height: 6px; border-radius: 10px;">
                                        <div class="progress-bar bg-{{ $student->attendance_percentage > 75 ? 'success' : ($student->attendance_percentage > 40 ? 'warning' : 'danger') }}" 
                                             role="progressbar" style="width: {{ $student->attendance_percentage }}%"></div>
                                    </div>
                                    <span class="fw-bold small">{{ $student->attendance_percentage }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="mb-3"><i class="fas fa-user-graduate fs-1 text-muted opacity-25"></i></div>
                                <h6 class="text-muted">{{ __('instructor::reports.no_students') }}</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
