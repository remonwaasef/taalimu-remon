@extends('center::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar: Curriculum -->
        <div class="col-md-3 bg-light border-end vh-100 overflow-auto p-0">
            <div class="p-3 border-bottom">
                <h5 class="mb-0 text-truncate" title="{{ $course->title }}">{{ $course->title }}</h5>
                <div class="progress mt-2" style="height: 5px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $enrollment->progress }}%"></div>
                </div>
                <small class="text-muted">{{ $enrollment->progress }}% Complete</small>
            </div>
            
            <div class="accordion accordion-flush" id="curriculumAccordion">
                @foreach($course->sections as $section)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-{{ $section->id }}">
                            <button class="accordion-button {{ $lesson->section_id == $section->id ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $section->id }}">
                                {{ $section->title }}
                            </button>
                        </h2>
                        <div id="collapse-{{ $section->id }}" class="accordion-collapse collapse {{ $lesson->section_id == $section->id ? 'show' : '' }}" data-bs-parent="#curriculumAccordion">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach($section->lessons as $secLesson)
                                        <a href="{{ route('center.courses.player', ['course' => $course->id, 'lesson' => $secLesson->id]) }}" 
                                           class="list-group-item list-group-item-action d-flex align-items-center {{ $lesson->id == $secLesson->id ? 'active' : '' }}">
                                            <div class="me-2">
                                                @if(in_array($secLesson->id, $completedLessonIds))
                                                    <i class="fas fa-check-circle text-success"></i>
                                                @else
                                                    <i class="far fa-circle text-muted"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-truncate" style="max-width: 150px;">{{ $secLesson->title }}</span>
                                                    <small class="text-muted ms-1">{{ $secLesson->duration }}m</small>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Main Content: Player -->
        <div class="col-md-9 p-4 vh-100 overflow-auto">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">{{ $lesson->title }}</h2>
                @if(!in_array($lesson->id, $completedLessonIds))
                    <form action="{{ route('center.lessons.complete', ['course' => $course->id, 'lesson' => $lesson->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i> Mark as Complete</button>
                    </form>
                @else
                    <button class="btn btn-outline-success" disabled><i class="fas fa-check-double me-1"></i> Completed</button>
                @endif
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    @if($lesson->type == 'video')
                        <div class="ratio ratio-16x9 bg-dark">
                            @if(Str::contains($lesson->content, 'youtube.com') || Str::contains($lesson->content, 'youtu.be'))
                                <iframe src="{{ str_replace('watch?v=', 'embed/', $lesson->content) }}" allowfullscreen></iframe>
                            @elseif(Str::contains($lesson->content, 'vimeo.com'))
                                <iframe src="https://player.vimeo.com/video/{{ basename($lesson->content) }}" allowfullscreen></iframe>
                            @else
                                <!-- Local Video or other URL -->
                                <video controls>
                                    <source src="{{ $lesson->content }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>
                    @elseif($lesson->type == 'text')
                        <div class="p-4">
                            {!! strip_tags($lesson->content, '<p><br><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6><a><img><table><thead><tbody><tr><th><td><blockquote><pre><code>') !!}
                        </div>
                    @elseif($lesson->type == 'quiz')
                        <div class="p-5 text-center">
                            <i class="fas fa-clipboard-list fa-3x text-primary mb-3"></i>
                            <h3>الاختبار: {{ $lesson->title }}</h3>
                            @php $quiz = $lesson->quiz; @endphp
                            @if($quiz)
                                <p class="text-muted">درجة النجاح: {{ $quiz->passing_score }}% | الوقت: {{ $quiz->duration_minutes ?? __('center::messages.blade_0370') }} دقيقة</p>
                                <a href="{{ route('center.quizzes.show', $quiz) }}" class="btn btn-primary btn-lg rounded-pill px-5">{{ __('center::messages.blade_0364') }}</a>
                            @else
                                <p class="text-danger">{{ __('center::messages.blade_0365') }}</p>
                            @endif
                        </div>
                    @elseif($lesson->type == 'assignment')
                        <div class="p-5 text-center">
                            <i class="fas fa-file-upload fa-3x text-success mb-3"></i>
                            <h3>التكليف: {{ $lesson->title }}</h3>
                            @php $assignment = $lesson->assignment; @endphp
                            @if($assignment)
                                <p class="text-muted">أقصى درجة: {{ $assignment->max_score }} | موعد التسليم: {{ $assignment->due_date ? $assignment->due_date->format('Y-m-d') : 'غير محدد' }}</p>
                                <a href="{{ route('center.assignments.show', $assignment) }}" class="btn btn-success btn-lg rounded-pill px-5">{{ __('center::messages.blade_0366') }}</a>
                            @else
                                <p class="text-danger">{{ __('center::messages.blade_0367') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mt-4 border-0 rounded-4">
                <div class="card-header bg-white border-0 p-0 overflow-hidden">
                    <ul class="nav nav-pills nav-fill bg-light border-bottom p-1" id="lessonTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active rounded-pill py-2" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button">{{ __('center::messages.blade_0368') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-pill py-2" id="resources-tab" data-bs-toggle="tab" data-bs-target="#resources" type="button">
                                المصادر ({{ $course->resources->count() }})
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="lessonTabsContent">
                        <div class="tab-pane fade show active" id="about">
                            <h4 class="fw-bold mb-3">{{ $lesson->title }}</h4>
                            <div class="text-muted">
                                {!! nl2br(e($lesson->description ?? __('center::messages.blade_0371'))) !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="resources">
                            <div class="row g-3">
                                @forelse($course->resources as $res)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 bg-light transition shadow-sm-hover h-100">
                                            <div class="d-flex align-items-center overflow-hidden">
                                                <i class="fas fa-file-{{ in_array($res->file_type, ['pdf', 'doc', 'docx']) ? 'pdf' : 'alt' }} text-primary fs-4 me-3"></i>
                                                <div class="text-truncate">
                                                    <div class="fw-bold text-truncate" style="max-width: 200px;">{{ $res->title }}</div>
                                                    <small class="text-muted">{{ strtoupper($res->file_type) }} • {{ round($res->file_size / 1024 / 1024, 2) }} MB</small>
                                                </div>
                                            </div>
                                            <a href="{{ route('center.resources.download', $res) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted py-4">
                                        <i class="fas fa-folder-open fa-2x mb-2 opacity-50"></i>
                                        <p>{{ __('center::messages.blade_0369') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <!-- Navigation Buttons Logic -->
                @php
                    $allLessons = $course->sections->flatMap->lessons;
                    $currentIndex = $allLessons->search(function($item) use ($lesson) { return $item->id == $lesson->id; });
                    $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
                    $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;
                @endphp

                @if($prevLesson)
                    <a href="{{ route('center.courses.player', ['course' => $course->id, 'lesson' => $prevLesson->id]) }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-right me-1"></i> الدرس السابق: {{ $prevLesson->title }}
                    </a>
                @else
                    <div></div>
                @endif

                @if($nextLesson)
                    <a href="{{ route('center.courses.player', ['course' => $course->id, 'lesson' => $nextLesson->id]) }}" class="btn btn-primary rounded-pill px-4">
                        الدرس التالي: {{ $nextLesson->title }} <i class="fas fa-arrow-left ms-1"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
    // 1. Anti-Copy Protection
    document.addEventListener('contextmenu', event => event.preventDefault()); // Disable Right Click
    
    document.onkeydown = function(e) {
        // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S, Ctrl+P, Ctrl+C
        if (e.keyCode == 123 || 
            (e.ctrlKey && e.shiftKey && (e.keyCode == 'I'.charCodeAt(0) || e.keyCode == 'J'.charCodeAt(0) || e.keyCode == 'C'.charCodeAt(0))) || 
            (e.ctrlKey && (e.keyCode == 'U'.charCodeAt(0) || e.keyCode == 'S'.charCodeAt(0) || e.keyCode == 'P'.charCodeAt(0) || e.keyCode == 'C'.charCodeAt(0)))) {
            return false;
        }
    };

    // 2. Dynamic Watermarking Logic
    const watermarkId = 'wm-' + Math.random().toString(36).substr(2, 9);
    const studentInfo = "{{ auth()->user()->email }} | {{ auth()->user()->student->phone ?? auth()->user()->id }}";
    
    const wm = document.createElement('div');
    wm.id = watermarkId;
    wm.style.position = 'fixed';
    wm.style.zIndex = '9999';
    wm.style.pointerEvents = 'none';
    wm.style.opacity = '0.3';
    wm.style.color = '#fff';
    wm.style.fontSize = '12px';
    wm.style.fontWeight = 'bold';
    wm.style.textShadow = '1px 1px 2px #000';
    wm.style.padding = '5px';
    wm.style.whiteSpace = 'nowrap';
    wm.innerText = studentInfo;
    document.body.appendChild(wm);

    function moveWatermark() {
        // Only move if there is a video on screen
        const videoContainer = document.querySelector('.ratio-16x9') || document.body;
        const rect = videoContainer.getBoundingClientRect();
        
        const x = Math.random() * (rect.width - 200) + rect.left;
        const y = Math.random() * (rect.height - 50) + rect.top;
        
        wm.style.left = x + 'px';
        wm.style.top = y + 'px';
        
        // Randomly change opacity to make it harder to filter out
        wm.style.opacity = (Math.random() * 0.3 + 0.1).toString();
    }

    // Move every 5-10 seconds
    moveWatermark();
    setInterval(moveWatermark, Math.random() * 5000 + 5000);
</script>
@endsection
@endsection
