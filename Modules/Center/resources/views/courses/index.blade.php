@extends('center::layouts.master')

@section('content')
    @include('center::layouts.setup_tabs')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::courses.title') }}</h2>
        <a href="{{ route('center.courses.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <span class="me-2">+</span> {{ __('center::courses.add_new') }}
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <!-- Search & Filter -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <x-ui.search action="{{ route('center.courses.index') }}" placeholder="{{ __('center::courses.search_placeholder') }}" />
                </div>
                <div class="col-md-3">
                    <x-ui.filter name="status" :options="['published' => __('center::courses.status_published'), 'draft' => __('center::courses.status_draft'), 'archived' => __('center::courses.status_archived')]" label="{{ __('center::courses.status_label') }}" />
                </div>
            </div>

            <!-- Courses Table -->
            <div class="table-responsive pb-5" style="min-height: 350px; overflow-x: auto;">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start">{{ __('center::courses.course_name') }}</th>
                            <th class="border-0">{{ __('center::courses.instructor') }}</th>
                            <th class="border-0">{{ __('center::courses.schedules') }}</th>
                            <th class="border-0">{{ __('center::courses.price') }}</th>
                            <th class="border-0">{{ __('center::courses.status') }}</th>
                            <th class="border-0 rounded-end">{{ __('center::courses.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($course->image)
                                            <img src="{{ Storage::url($course->image) }}" 
                                                 class="rounded-3 me-3" 
                                                 style="width: 48px; height: 48px; object-fit: cover;" 
                                                 alt="{{ $course->title }}"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        @endif
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center me-3" 
                                             style="width: 48px; height: 48px; {{ $course->image ? 'display: none;' : '' }}">
                                            📚
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $course->title }}</div>
                                            <small class="text-muted">{{ Str::limit($course->description, 30) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $course->instructor->name ?? __('center::courses.not_specified') }}</td>
                                <td>
                                    @if($course->schedules->count() > 0)
                                        <div class="{{ ($loop->remaining < 2 && $courses->count() > 2) ? 'dropup' : 'dropdown' }}">
                                            <button class="btn btn-light btn-sm rounded-pill border shadow-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="far fa-calendar-alt text-primary"></i>
                                                <span class="fw-bold">{{ $course->schedules->count() }} مواعيد</span>
                                            </button>
                                            <div class="dropdown-menu border-0 shadow-lg p-2 rounded-4" style="min-width: 250px;">
                                                <h6 class="dropdown-header text-primary fw-bold mb-2">جدول المواعيد</h6>
                                                <div class="d-flex flex-column gap-2">
                                                    @foreach($course->schedules as $schedule)
                                                        @php
                                                            $days = [
                                                                0 => 'الأحد', 1 => 'الاثنين', 2 => 'الثلاثاء', 
                                                                3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت'
                                                            ];
                                                            $dayName = $days[$schedule->day_of_week] ?? $schedule->day_of_week;
                                                            $start = \Carbon\Carbon::parse($schedule->start_time)->format('h:i A');
                                                            $end = \Carbon\Carbon::parse($schedule->end_time)->format('h:i A');
                                                        @endphp
                                                        <div class="d-flex align-items-center bg-light rounded-3 p-2">
                                                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm text-primary me-2 flex-shrink-0" style="width: 32px; height: 32px;">
                                                                <i class="fas fa-calendar-day fa-sm"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $dayName }}</div>
                                                                <div class="text-muted d-flex align-items-center gap-1" style="font-size: 0.7rem;">
                                                                    <span>{{ $start }} - {{ $end }}</span>
                                                                    @if($schedule->classroom)
                                                                        <span class="vr mx-1"></span>
                                                                        <i class="fas fa-map-marker-alt text-danger"></i> {{ $schedule->classroom->name }}
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small fst-italic">{{ __('center::courses.not_specified') }}</span>
                                    @endif
                                </td>
                                <td class="fw-bold text-success">{{ number_format($course->price, 2) }} {{ __('center::courses.currency') }}</td>
                                <td>
                                    @php
                                        $badges = [
                                            'published' => 'success',
                                            'draft' => 'secondary',
                                            'archived' => 'warning'
                                        ];
                                        $labels = [
                                            'published' => __('center::courses.status_published'),
                                            'draft' => __('center::courses.status_draft'),
                                            'archived' => __('center::courses.status_archived')
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $badges[$course->status] ?? 'secondary' }} bg-opacity-10 text-{{ $badges[$course->status] ?? 'secondary' }} rounded-pill px-3">
                                        {{ $labels[$course->status] ?? $course->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1 justify-content-end">
                                        <a href="{{ route('center.courses.show', [$course->id, 'enroll' => 1]) }}" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold shadow-sm d-none d-xl-inline-block">
                                            <i class="fas fa-user-plus me-1"></i> {{ __('center::courses.enroll_student') }}
                                        </a>
                                        <div class="{{ ($loop->remaining < 2 && $courses->count() > 2) ? 'dropup' : 'dropdown' }}">
                                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                                <li><a class="dropdown-item" href="{{ route('center.courses.show', $course->id) }}"><i class="fas fa-eye me-2 text-muted"></i> {{ __('center::courses.view') }}</a></li>
                                                <li><a class="dropdown-item fw-bold text-success" href="{{ route('center.courses.show', [$course->id, 'enroll' => 1]) }}"><i class="fas fa-user-plus me-2"></i> {{ __('center::courses.enroll_student') }}</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="{{ route('center.courses.edit', $course->id) }}"><i class="fas fa-edit me-2 text-muted"></i> {{ __('center::courses.edit') }}</a></li>
                                                <li><a class="dropdown-item" href="{{ route('center.curriculum.edit', $course->id) }}"><i class="fas fa-book-open me-2 text-muted"></i> {{ __('center::courses.content') }}</a></li>
                                                <li><a class="dropdown-item" href="{{ route('center.schedules.create', ['course_id' => $course->id]) }}"><i class="fas fa-calendar-plus me-2 text-info"></i> {{ __('center::students.add_new_schedule') }}</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('center.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('{{ __('center::courses.delete_confirm') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash-alt me-2"></i> {{ __('center::courses.delete') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">{{ __('center::courses.no_courses') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $courses->links('components.ui.pagination') }}
            </div>
        </div>
    </div>
@endsection
