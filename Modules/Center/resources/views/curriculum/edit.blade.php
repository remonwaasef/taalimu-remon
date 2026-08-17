@extends('center::layouts.app-next')

@section('panel-content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Curriculum for: {{ $course->title }}</h1>
        <a href="{{ route('center.courses.index') }}" class="btn btn-secondary">Back to Courses</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Course Resources Section -->
    <div class="card mb-4 border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-paperclip text-primary me-2"></i>{{ __('center::curriculum.course_resources') }}</h5>
            <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#resourceForm">
                <i class="fas fa-plus me-1"></i>{{ __('center::curriculum.add_file') }}</button>
        </div>
        <div class="collapse" id="resourceForm">
            <div class="card-body bg-light border-top">
                <form action="{{ route('center.resources.store', $course) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="title" class="form-control" placeholder="{{ __('center::curriculum.file_title_placeholder') }}" required>
                        </div>
                        <div class="col-md-4">
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">{{ __('center::curriculum.upload') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @forelse($course->resources as $res)
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 bg-white">
                            <div class="d-flex align-items-center overflow-hidden">
                                <i class="fas fa-file-pdf text-danger fs-4 me-3"></i>
                                <div class="text-truncate">
                                    <div class="fw-bold text-truncate" style="max-width: 150px;">{{ $res->title }}</div>
                                    <small class="text-muted">{{ strtoupper($res->file_type) }} • {{ round($res->file_size / 1024 / 1024, 2) }} MB</small>
                                </div>
                            </div>
                            <form action="{{ route('center.resources.destroy', $res) }}" method="POST" class="ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('{{ __('center::curriculum.confirm_delete_file') }}')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-3">{{ __('center::curriculum.no_files') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Add Section Form -->
    <div class="card mb-4 border-0 shadow-sm rounded-4">
        <div class="card-body">
            <form action="{{ route('center.sections.store', $course) }}" method="POST" class="row g-3 align-items-center">
                @csrf
                <div class="col-auto flex-grow-1">
                    <input type="text" name="title" class="form-control rounded-pill" placeholder="{{ __('center::curriculum.section_title_placeholder') }}" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('center::curriculum.add_section') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sections List -->
    <div id="sections-list" data-url="{{ route('center.sections.reorder', $course) }}">
        @foreach($course->sections as $section)
            <div class="card mb-3 section-item" data-id="{{ $section->id }}">
                <div class="card-header d-flex justify-content-between align-items-center handle" style="cursor: move; background-color: #f8f9fa;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-grip-lines me-2 text-muted"></i>
                        <form action="{{ route('center.sections.update', $section) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('PUT')
                            <input type="text" name="title" value="{{ $section->title }}" class="form-control form-control-sm border-0 bg-transparent fw-bold" style="width: 300px;">
                        </form>
                    </div>
                    <div>
                        <form action="{{ route('center.sections.destroy', $section) }}" method="POST" class="d-inline-block" id="deleteRowForm_1">
                            @csrf
                            @method('DELETE')
                            <button type="button" data-confirm-delete data-form="deleteRowForm_1" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Lessons List -->
                    <ul class="list-group lessons-list" id="lessons-section-{{ $section->id }}" data-url="{{ route('center.lessons.reorder', $section) }}">
                        @foreach($section->lessons as $lesson)
                            <li class="list-group-item d-flex justify-content-between align-items-center lesson-item" data-id="{{ $lesson->id }}">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-grip-vertical me-2 text-muted handle-lesson" style="cursor: move;"></i>
                                    <i class="fas fa-{{ $lesson->type == 'video' ? 'video' : ($lesson->type == 'quiz' ? 'question-circle' : 'file-alt') }} me-2 text-primary"></i>
                                    <span>{{ $lesson->title }}</span>
                                    @if($lesson->is_free)
                                        <span class="badge bg-success ms-2">Free Preview</span>
                                    @endif
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editLessonModal-{{ $lesson->id }}"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('center.lessons.destroy', $lesson) }}" method="POST" class="d-inline-block" id="deleteRowForm_2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" data-confirm-delete data-form="deleteRowForm_2" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>

                            </li>
                        @endforeach
                    </ul>
                    
                    <!-- Add Lesson Form -->
                    <div class="mt-3">
                        <form action="{{ route('center.lessons.store', $section) }}" method="POST" class="row g-2">
                            @csrf
                            <div class="col-auto">
                                <input type="text" name="title" class="form-control form-control-sm" placeholder="New Lesson Title" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-outline-primary">Add Lesson</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Edit Lesson Modals (Moved outside the loop) -->
    @foreach($course->sections as $section)
        @foreach($section->lessons as $lesson)
            <div class="modal fade" id="editLessonModal-{{ $lesson->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('center.lessons.update', $lesson) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Lesson</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" id="lesson_title_{{ $lesson->id }}" class="form-control" value="{{ $lesson->title }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-select">
                                        <option value="video" {{ $lesson->type == 'video' ? 'selected' : '' }}>Video</option>
                                        <option value="text" {{ $lesson->type == 'text' ? 'selected' : '' }}>Text</option>
                                        <option value="quiz" {{ $lesson->type == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                        <option value="assignment" {{ $lesson->type == 'assignment' ? 'selected' : '' }}>Assignment</option>
                                    </select>
                                </div>

                                @if($lesson->type == 'quiz')
                                    <div class="mb-3">
                                        @if($lesson->quiz)
                                            <a href="{{ route('center.quizzes.edit', $lesson->quiz) }}" class="btn btn-sm btn-info w-100">Manage Quiz Questions</a>
                                        @else
                                            <div class="p-3 border rounded bg-light">
                                                <p class="small text-muted mb-2">Quiz Setup required</p>
                                                <button type="button" class="btn btn-sm btn-outline-info w-100" onclick="submitInitialSetup('{{ route('center.quizzes.store', $lesson) }}', 'lesson_title_{{ $lesson->id }}', {passing_score: 50})">
                                                    Initial Quiz Setup
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @elseif($lesson->type == 'assignment')
                                    <div class="mb-3">
                                        @if($lesson->assignment)
                                            <a href="{{ route('center.assignments.edit', $lesson->assignment) }}" class="btn btn-sm btn-warning w-100">Edit Assignment Details</a>
                                        @else
                                            <div class="p-3 border rounded bg-light">
                                                <p class="small text-muted mb-2">Assignment Setup required</p>
                                                <button type="button" class="btn btn-sm btn-outline-warning w-100" onclick="submitInitialSetup('{{ route('center.assignments.store', $lesson) }}', 'lesson_title_{{ $lesson->id }}', {max_score: 100})">
                                                    Initial Assignment Setup
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label">Content (URL or Text)</label>
                                    <textarea name="content" class="form-control" rows="3">{{ $lesson->content }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Duration (minutes)</label>
                                    <input type="number" name="duration" class="form-control" value="{{ $lesson->duration }}">
                                </div>
                                <div class="form-check">
                                    <input type="hidden" name="is_free" value="0">
                                    <input type="checkbox" name="is_free" value="1" class="form-check-input" id="freeCheckModal-{{ $lesson->id }}" {{ $lesson->is_free ? 'checked' : '' }}>
                                    <label class="form-check-label" for="freeCheckModal-{{ $lesson->id }}">Free Preview</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endforeach

    <!-- Hidden form for initial setup actions (avoiding nested forms) -->
    <form id="initialSetupForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="title" id="setup_title">
        <div id="setup_extra_fields"></div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Sections Sorting
    new Sortable(document.getElementById('sections-list'), {
        handle: '.handle',
        animation: 150,
        onEnd: function (evt) {
            let url = document.getElementById('sections-list').dataset.url;
            let sections = Array.from(document.querySelectorAll('.section-item')).map(el => el.dataset.id);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ sections: sections })
            });
        }
    });

    // Lessons Sorting
    document.querySelectorAll('.lessons-list').forEach(function(el) {
        new Sortable(el, {
            group: 'lessons', // Allow dragging between sections
            handle: '.handle-lesson',
            animation: 150,
            onEnd: function (evt) {
                let sectionId = evt.to.id.replace('lessons-section-', '');
                let url = evt.to.dataset.url;
                let lessons = Array.from(evt.to.querySelectorAll('.lesson-item')).map(el => el.dataset.id);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ lessons: lessons })
                });
            }
    });

    // Helper for initial setup without nested forms
    function submitInitialSetup(url, titleInputId, extraFields = {}) {
        const form = document.getElementById('initialSetupForm');
        const titleInput = document.getElementById(titleInputId);
        const extraContainer = document.getElementById('setup_extra_fields');
        
        form.action = url;
        document.getElementById('setup_title').value = titleInput.value;
        
        // Clear and add extra fields
        extraContainer.innerHTML = '';
        for (const [key, value] of Object.entries(extraFields)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            extraContainer.appendChild(input);
        }
        
        form.submit();
    }
</script>
@endpush
@endsection
