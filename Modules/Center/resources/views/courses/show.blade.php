@extends('center::layouts.app-next')

@section('panel-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">{{ $course->title }}</h2>
            <div class="d-flex align-items-center gap-3 text-muted">
                @if($course->instructor)
                    <span><i class="fas fa-user-tie me-1"></i> {{ $course->instructor->name }}</span>
                @endif
                <span class="badge {{ $course->status == 'published' ? 'bg-success' : 'bg-secondary' }}">
                    {{ ucfirst($course->status) }}
                </span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('center.curriculum.edit', $course->id) }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-chalkboard me-2"></i>{{ __('center::courses.curriculum_content') }}</a>
            <a href="{{ route('center.courses.edit', $course->id) }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-edit me-2"></i>{{ __('center::courses.edit_course') }}</a>
            <button class="btn btn-primary rounded-pill px-4" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'enrollStudentModal' }))">
                <i class="fas fa-user-plus me-2"></i>{{ __('center::courses.enroll_student_modal_title') }}</button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 text-primary">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">{{ __('center::courses.enrolled_students') }}</h6>
                        <h3 class="fw-bold mb-0">{{ $course->enrollments->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 text-success">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">{{ __('center::courses.completion_rate') }}</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($course->enrollments->avg('progress'), 1) }}%</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3 text-info">
                        <i class="fas fa-money-bill fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">{{ __('center::courses.course_price') }}</h6>
                        <h3 class="fw-bold mb-0">{{ format_price($course->price) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Students List -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">{{ __('center::courses.students_list') }}</h5>
        </div>
        <div class="table-responsive" data-mobile-cards>
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-start">{{ __('center::courses.student') }}</th>
                        <th class="border-0">{{ __('center::courses.enrollment_date') }}</th>
                        <th class="border-0">{{ __('center::courses.progress') }}</th>
                        <th class="border-0">{{ __('center::courses.status') }}</th>
                        <th class="border-0 rounded-end">{{ __('center::courses.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($course->enrollments as $enrollment)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        {{ substr($enrollment->user->student->name ?? $enrollment->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $enrollment->user->student->name ?? $enrollment->user->name }}</h6>
                                        <small class="text-muted">{{ $enrollment->user->student->phone ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $enrollment->enrolled_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 rounded-pill" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: {{ $enrollment->progress }}%"></div>
                                    </div>
                                    <span class="ms-2 small">{{ $enrollment->progress }}%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-{{ $enrollment->status == 'active' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $enrollment->status == 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ $enrollment->user->student ? route('center.students.show', $enrollment->user->student->id) : '#' }}" class="btn btn-sm btn-light rounded-pill">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-users-slash fa-2x mb-3"></i>
                                <p class="mb-0">{{ __('center::courses.no_students_enrolled') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Enroll Student Modal (Alpine.js) -->
    <div
        x-data="{ show: false, activeTab: 0 }"
        x-on:open-modal.window="if ($event.detail === 'enrollStudentModal') { show = true; activeTab = 0 }"
        x-on:close-modal.window="if ($event.detail === 'enrollStudentModal') show = false"
        x-on:keydown.escape.window="show = false"
        x-effect="document.body.style.overflow = show ? 'hidden' : ''"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-label="{{ __('center::courses.enroll_student_modal_title') }}"
    >
        <div
            x-show="show"
            x-transition:enter="ease-out duration-250"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="show = false"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
        ></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-show="show"
                x-transition:enter="ease-out duration-250"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-900 text-start shadow-xl transition-all w-full max-w-2xl border border-brand-border dark:border-slate-800 my-8"
            >
                <div class="px-6 pt-5 pb-4 flex items-center justify-between gap-4">
                    <h3 class="font-bold text-lg text-slate-900 dark:text-slate-100">{{ __('center::courses.enroll_student_modal_title') }}</h3>
                    <button @click="show = false" type="button" aria-label="Close" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shrink-0">
                        <i class="fas fa-times text-base"></i>
                    </button>
                </div>

                <div class="px-6 pb-2">
                    <div class="grid grid-cols-2 gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-full" role="tablist">
                        <button id="existing-tab" type="button" role="tab" @click="activeTab = 0"
                            :class="activeTab === 0 ? 'bg-white dark:bg-slate-900 text-brand-primary shadow' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'"
                            class="rounded-full w-full font-semibold py-2.5 text-sm transition-all">
                            <i class="fas fa-search me-2"></i>{{ __('center::courses.enrolled_student_tab') }}
                        </button>
                        <button id="quick-tab" type="button" role="tab" @click="activeTab = 1"
                            :class="activeTab === 1 ? 'bg-white dark:bg-slate-900 text-brand-primary shadow' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'"
                            class="rounded-full w-full font-semibold py-2.5 text-sm transition-all">
                            <i class="fas fa-user-plus me-2"></i>{{ __('center::courses.quick_new_student_tab') }}
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Panel 1: Existing Student -->
                    <div id="existing-panel" role="tabpanel" x-show="activeTab === 0">
                        <form action="{{ route('center.courses.enroll', $course->id) }}" method="POST" class="p-2">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark mb-2">{{ __('center::courses.choose_student_label') }}</label>
                                <select name="student_id" class="form-select border-2" id="studentSelect" required>
                                    <option value="">{{ __('center::courses.search_student_modal_placeholder') }}</option>
                                </select>
                                <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i>{{ __('center::courses.search_unregistered_hint') }}</div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold fs-5 shadow-sm">{{ __('center::courses.confirm_enrollment_btn') }}<i class="fas fa-arrow-left ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Panel 2: Quick New Student -->
                    <div id="quick-panel" role="tabpanel" x-show="activeTab === 1" x-cloak class="hidden">
                        <form action="{{ route('center.courses.quick-enroll', $course->id) }}" method="POST" class="p-2">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="name" class="form-control border-2 rounded-4" id="qName" placeholder="{{ __('center::courses.full_name') }}" required>
                                        <label for="qName">{{ __('center::courses.full_name') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="tel" name="phone" class="form-control border-2 rounded-4" id="qPhone" placeholder="{{ __('center::courses.phone') }}" required>
                                        <label for="qPhone">{{ __('center::courses.phone') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="tel" name="parent_phone" class="form-control border-2 rounded-4" id="qParentPhone" placeholder="{{ __('center::courses.parent_phone') }}">
                                        <label for="qParentPhone">{{ __('center::courses.parent_phone') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-4">
                                        <select name="grade_id" class="form-select border-2 rounded-4" id="qGrade" required>
                                            <option value="">{{ __('center::courses.select_grade') }}</option>
                                            @foreach($stages as $stage)
                                                <optgroup label="📂 {{ $stage->name }}">
                                                    @foreach($stage->grades as $grade)
                                                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        <label for="qGrade">{{ __('center::courses.grade_level') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info border-0 rounded-4 py-3 small mb-4">
                                <i class="fas fa-magic me-2"></i>{{ __('center::courses.quick_enroll_modal_hint') }}</div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success rounded-pill py-3 fw-bold fs-5 shadow-sm">{{ __('center::courses.create_and_enroll_btn') }}<i class="fas fa-bolt ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
        <style>
            .ts-control { border-radius: 0.5rem !important; padding: 0.75rem 1rem !important; }
            .ts-dropdown { border-radius: 0.5rem !important; box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important; }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            new TomSelect('#studentSelect', {
                plugins: ['dropdown_input'],
                // Non-enrolled students are loaded on demand from the server
                // instead of being embedded in the page (full-table read before).
                valueField: 'id',
                labelField: 'text',
                searchField: [],
                maxOptions: 50,
                preload: 'focus',
                loadThrottle: 300,
                load: function(query, callback) {
                    const url = '{{ route('center.students.search') }}?exclude_course_id={{ $course->id }}&q=' + encodeURIComponent(query);
                    fetch(url, { headers: { 'Accept': 'application/json' } })
                        .then(r => r.ok ? r.json() : [])
                        .then(json => callback(json))
                        .catch(() => callback());
                }
            });

            @if(isset($auto_enroll) && $auto_enroll)
            document.addEventListener('DOMContentLoaded', function() {
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'enrollStudentModal' }));
            });
            @endif
        </script>
    @endpush
@endsection
