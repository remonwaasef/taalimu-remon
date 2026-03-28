@extends('center::layouts.hope-master')

@section('content')

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
                                                <span class="fw-bold">{{ $course->schedules->count() }} {{ __('center::schedules.schedules_count') }}</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4" style="min-width: 250px;">
                                                <h6 class="dropdown-header text-primary fw-bold mb-2">{{ __('center::messages.blade_0337') }}</h6>
                                                <div class="d-flex flex-column gap-2">
                                                    @foreach($course->schedules as $schedule)
                                                        @php
                                                            $days = [
                                                                0 => __('center::messages.blade_0357'), 1 => __('center::messages.blade_0358'), 2 => __('center::messages.blade_0359'), 
                                                                3 => __('center::messages.blade_0360'), 4 => __('center::messages.blade_0361'), 5 => __('center::messages.blade_0362'), 6 => __('center::messages.blade_0363')
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
                                        <button type="button" onclick="openEnrollModal('{{ $course->id }}', '{{ addslashes($course->title) }}')" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold shadow-sm d-none d-xl-inline-block border-0">
                                            <i class="fas fa-user-plus me-1"></i> {{ __('center::courses.enroll_student') }}
                                        </button>
                                        <div class="{{ ($loop->remaining < 2 && $courses->count() > 2) ? 'dropup' : 'dropdown' }}">
                                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                                <li><a class="dropdown-item" href="{{ route('center.courses.show', $course->id) }}"><i class="fas fa-eye me-2 text-muted"></i> {{ __('center::courses.view') }}</a></li>
                                                <li><button type="button" class="dropdown-item fw-bold text-success" onclick="openEnrollModal('{{ $course->id }}', '{{ addslashes($course->title) }}')"><i class="fas fa-user-plus me-2"></i> {{ __('center::courses.enroll_student') }}</button></li>
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

    <!-- Unified Enroll Student Modal -->
    <div class="modal fade" id="unifiedEnrollModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-5 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0 pt-4 px-4 bg-light bg-opacity-50">
                    <h5 class="modal-title fw-bold fs-4">{{ __('center::courses.enroll_student') }}: <span id="dynamicCourseTitle" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-light bg-opacity-50 border-bottom">
                    <!-- Custom Tabs -->
                    <ul class="nav nav-pills bg-white p-1 rounded-pill shadow-sm" id="enrollTabs" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link active rounded-pill w-100 fw-bold" id="existing-tab" data-bs-toggle="pill" data-bs-target="#existing-panel" type="button" role="tab">
                                <i class="fas fa-search me-2"></i>{{ __('center::messages.blade_0339') }}</button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link rounded-pill w-100 fw-bold" id="quick-tab" data-bs-toggle="pill" data-bs-target="#quick-panel" type="button" role="tab">
                                <i class="fas fa-user-plus me-2"></i>{{ __('center::messages.blade_0340') }}</button>
                        </li>
                    </ul>
                </div>
                <div class="modal-body p-4 pt-3">
                    <div class="tab-content" id="enrollTabsContent">
                        <!-- Panel 1: Existing Student -->
                        <div class="tab-pane fade show active" id="existing-panel" role="tabpanel">
                            <form id="existingStudentForm" action="" method="POST" class="p-2">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2">{{ __('center::messages.blade_0341') }}</label>
                                    <select name="student_id" class="form-select border-2" id="unifiedStudentSelect" required placeholder="{{ __('center::messages.blade_0353') }}">
                                        <option value="">{{ __('center::messages.blade_0342') }}</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->phone }})</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i>{{ __('center::messages.blade_0343') }}</div>
                                </div>
                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold fs-5 shadow-sm">{{ __('center::messages.blade_0344') }}<i class="fas fa-check-circle ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Panel 2: Quick New Student -->
                        <div class="tab-pane fade" id="quick-panel" role="tabpanel">
                            <form id="quickNewStudentForm" action="" method="POST" class="p-2">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="name" class="form-control border-2 rounded-4 bg-light" id="qName" placeholder="{{ __('center::messages.blade_0354') }}" required>
                                            <label for="qName">{{ __('center::messages.blade_0345') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="tel" name="phone" class="form-control border-2 rounded-4 bg-light" id="qPhone" placeholder="{{ __('center::messages.blade_0355') }}" required>
                                            <label for="qPhone">{{ __('center::messages.blade_0346') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="tel" name="parent_phone" class="form-control border-2 rounded-4 bg-light" id="qParentPhone" placeholder="{{ __('center::messages.blade_0356') }}">
                                            <label for="qParentPhone">{{ __('center::messages.blade_0347') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating mb-2">
                                            <select name="grade_id" class="form-select border-2 rounded-4 bg-light" id="qGrade" required>
                                                <option value="">{{ __('center::messages.blade_0348') }}</option>
                                                @foreach($stages as $stage)
                                                    <optgroup label="📂 {{ $stage->name }}">
                                                        @foreach($stage->grades as $grade)
                                                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                            <label for="qGrade">{{ __('center::messages.blade_0349') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert flex-row d-flex align-items-center bg-info bg-opacity-10 text-info border-0 rounded-4 py-3 small my-3">
                                    <i class="fas fa-magic fa-lg me-3 ms-1"></i>
                                    <div>{{ __('center::messages.blade_0350') }}</div>
                                </div>
                                <div class="d-grid gap-2 mt-2">
                                    <button type="submit" id="quickEnrollSubmitBtn" class="btn btn-success rounded-pill py-3 fw-bold fs-5 shadow-sm">{{ __('center::messages.blade_0351') }}<i class="fas fa-bolt ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
        <style>
            .ts-control { border-radius: 0.75rem !important; padding: 0.85rem 1rem !important; border-width: 2px !important; background-color: #f8f9fa !important; }
            .ts-dropdown { border-radius: 0.75rem !important; box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; padding: 0.5rem; }
            .modal-content.rounded-5 { border-radius: 1.5rem !important; overflow: hidden; }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            let unifiedTomSelect = null;
            
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize TomSelect only once
                unifiedTomSelect = new TomSelect('#unifiedStudentSelect', {
                    plugins: ['dropdown_input'],
                    sortField: { field: "text", direction: "asc" },
                    maxOptions: 50,
                    render: {
                        no_results: function(data, escape) {
                            return '<div class="no-results p-3 text-muted text-center">{{ __('center::messages.blade_0352') }}</div>';
                        }
                    }
                });
                
                // Add loading state to the quick enroll form
                document.getElementById('quickNewStudentForm').addEventListener('submit', function() {
                    let btn = document.getElementById('quickEnrollSubmitBtn');
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> {{ __('center::schedules.saving') }}';
                });
                
                // Also add loading to existing form
                document.getElementById('existingStudentForm').addEventListener('submit', function() {
                    let btn = this.querySelector('button[type="submit"]');
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> {{ __('center::schedules.registering') }}';
                });
            });

            function openEnrollModal(courseId, courseTitle) {
                // Set the dynamic title
                document.getElementById('dynamicCourseTitle').innerText = courseTitle;
                
                // Update forms actions based on course ID
                let enrollUrl = `{{ route('center.courses.enroll', '__ID__') }}`.replace('__ID__', courseId);
                let quickEnrollUrl = `{{ route('center.courses.quick-enroll', '__ID__') }}`.replace('__ID__', courseId);
                
                document.getElementById('existingStudentForm').action = enrollUrl;
                document.getElementById('quickNewStudentForm').action = quickEnrollUrl;
                
                // Clear inputs if any previous data
                if (unifiedTomSelect) {
                    unifiedTomSelect.clear();
                }
                document.getElementById('qName').value = '';
                document.getElementById('qPhone').value = '';
                document.getElementById('qParentPhone').value = '';
                document.getElementById('qGrade').value = '';
                
                // Ensure Existing Tab is shown by default
                let existingTab = new bootstrap.Tab(document.getElementById('existing-tab'));
                existingTab.show();
                
                // Show modal
                var myModal = new bootstrap.Modal(document.getElementById('unifiedEnrollModal'));
                myModal.show();
            }
        </script>
    @endpush
@endsection
