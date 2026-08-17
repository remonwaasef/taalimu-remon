@extends('center::layouts.app-next')

@section('panel-content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ __('center::quizzes.builder_title', ['title' => $quiz->title]) }}</h1>
        <a href="{{ route('center.curriculum.edit', $quiz->lesson->section->course) }}" class="btn btn-secondary">{{ __('center::quizzes.back_to_curriculum') }}</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header">{{ __('center::quizzes.quiz_settings') }}</div>
        <div class="card-body">
            <form action="{{ route('center.quizzes.update', $quiz) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">{{ __('center::quizzes.quiz_title') }}</label>
                    <input type="text" name="title" class="form-control" value="{{ $quiz->title }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('center::quizzes.passing_score_pct') }}</label>
                    <input type="number" name="passing_score" class="form-control" value="{{ $quiz->passing_score }}" min="0" max="100" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('center::quizzes.duration_mins') }}</label>
                    <input type="number" name="duration_minutes" class="form-control" value="{{ $quiz->duration_minutes }}" min="0">
                    <small class="text-muted">{{ __('center::quizzes.unlimited_hint') }}</small>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('center::quizzes.description') }}</label>
                    <textarea name="description" class="form-control" rows="2">{{ $quiz->description }}</textarea>
                </div>
                <div class="col-12 py-2 border-bottom mb-3">
                    <h6 class="fw-bold text-primary"><i class="fas fa-random me-2"></i> {{ __('center::quizzes.randomization_logic') }}</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="is_randomized" value="1" id="is_randomized" {{ $quiz->is_randomized ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="is_randomized">{{ __('center::quizzes.enable_randomization') }}</label>
                    </div>
                    <small class="text-muted">{{ __('center::quizzes.randomization_hint') }}</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('center::quizzes.questions_to_pick') }}</label>
                    <input type="number" name="random_questions_count" class="form-control" value="{{ $quiz->random_questions_count }}" min="1">
                    <small class="text-muted">{{ __('center::quizzes.questions_to_pick_hint') }}</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('center::quizzes.source_category') }}</label>
                    <select name="category_id" class="form-select">
                        <option value="">{{ __('center::quizzes.all_bank_questions') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $quiz->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm">
                        <i class="fas fa-save me-2"></i> {{ __('center::quizzes.update_settings') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('center::questions.title') }}</span>
            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addQuestionModal"><i class="fas fa-plus"></i> {{ __('center::quizzes.add_question') }}</button>
        </div>
        <div class="card-body">
            @foreach($quiz->questions as $index => $question)
                <div class="card mb-3 border-primary">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light">
                        <h5 class="mb-0">Q{{ $index + 1 }}: {{ Str::limit($question->content, 50) }} <span class="badge bg-info">
                            {{ $question->type == 'mcq' ? __('center::questions.mcq') : __('center::questions.true_false') }}
                        </span></h5>
                        <div>
                            <span class="badge bg-secondary me-2">{{ $question->points }} {{ __('center::questions.points_suffix') }}</span>
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editQuestionModal-{{ $question->id }}"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('center.quiz.questions.destroy', $question) }}" method="POST" class="d-inline-block" id="deleteRowForm_1">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-confirm-delete data-form="deleteRowForm_1" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="lead">{{ $question->content }}</p>
                        
                        <!-- Options -->
                        <ul class="list-group">
                            @foreach($question->options as $option)
                                <li class="list-group-item d-flex justify-content-between align-items-center {{ $option->is_correct ? 'list-group-item-success' : '' }}">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        @if($question->type == 'mcq')
                                            <form action="{{ route('center.options.correct', $option) }}" method="POST" class="me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $option->is_correct ? 'btn-success' : 'btn-outline-secondary' }}" title="{{ __('center::quizzes.mark_as_correct') }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('center.options.update', $option) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="content" class="form-control border-0 bg-transparent" value="{{ $option->content }}">
                                                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-save"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    @if($question->type == 'mcq')
                                        <form action="{{ route('center.options.destroy', $option) }}" method="POST" class="ms-2" id="deleteRowForm_2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-confirm-delete data-form="deleteRowForm_2" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        @if($question->type == 'mcq')
                            <div class="mt-2">
                                <form action="{{ route('center.options.store', $question) }}" method="POST" class="d-flex">
                                    @csrf
                                    <input type="text" name="content" class="form-control form-control-sm me-2" placeholder="{{ __('center::quizzes.new_option') }}" required>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">{{ __('center::questions.add_option') }}</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Edit Question Modal -->
                <div class="modal fade" id="editQuestionModal-{{ $question->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('center.quiz.questions.update', $question) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ __('center::questions.edit') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>{{ __('center::questions.content') }}</label>
                                        <textarea name="content" class="form-control" rows="3" required>{{ $question->content }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>{{ __('center::questions.points') }}</label>
                                        <input type="number" name="points" class="form-control" value="{{ $question->points }}" min="1" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('center::quizzes.close') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ __('center::quizzes.save_changes') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('center.quizzes.questions.store', $quiz) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('center::questions.new_question') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>{{ __('center::questions.content') }}</label>
                        <textarea name="content" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>{{ __('center::questions.type') }}</label>
                        <select name="type" class="form-select">
                            <option value="mcq">{{ __('center::questions.mcq') }}</option>
                            <option value="true_false">{{ __('center::questions.true_false') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>{{ __('center::questions.points') }}</label>
                        <input type="number" name="points" class="form-control" value="1" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('center::quizzes.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('center::quizzes.add_question') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
