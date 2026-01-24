@extends('center::layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Quiz Builder: {{ $quiz->title }}</h1>
        <a href="{{ route('center.curriculum.edit', $quiz->lesson->section->course) }}" class="btn btn-secondary">Back to Curriculum</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Quiz Settings -->
    <div class="card mb-4">
        <div class="card-header">Quiz Settings</div>
        <div class="card-body">
            <form action="{{ route('center.quizzes.update', $quiz) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $quiz->title }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Passing Score (%)</label>
                    <input type="number" name="passing_score" class="form-control" value="{{ $quiz->passing_score }}" min="0" max="100" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" class="form-control" value="{{ $quiz->duration_minutes }}" min="0">
                    <small class="text-muted">0 for unlimited</small>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ $quiz->description }}</textarea>
                </div>
                <div class="col-12 py-2 border-bottom mb-3">
                    <h6 class="fw-bold text-primary"><i class="fas fa-random me-2"></i> Randomization Logic</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="is_randomized" value="1" id="is_randomized" {{ $quiz->is_randomized ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="is_randomized">Enable Randomization</label>
                    </div>
                    <small class="text-muted">Pick questions from the Bank instead of the list below.</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Questions to Pick</label>
                    <input type="number" name="random_questions_count" class="form-control" value="{{ $quiz->random_questions_count }}" min="1">
                    <small class="text-muted">Leave empty to use all from category.</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Source Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">All Bank Questions</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $quiz->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm">
                        <i class="fas fa-save me-2"></i> Update Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Questions -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Questions</span>
            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addQuestionModal"><i class="fas fa-plus"></i> Add Question</button>
        </div>
        <div class="card-body">
            @foreach($quiz->questions as $index => $question)
                <div class="card mb-3 border-primary">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light">
                        <h5 class="mb-0">Q{{ $index + 1 }}: {{ Str::limit($question->content, 50) }} <span class="badge bg-info">{{ strtoupper(str_replace('_', ' ', $question->type)) }}</span></h5>
                        <div>
                            <span class="badge bg-secondary me-2">{{ $question->points }} Points</span>
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editQuestionModal-{{ $question->id }}"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('center.questions.destroy', $question) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this question?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
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
                                                <button type="submit" class="btn btn-sm {{ $option->is_correct ? 'btn-success' : 'btn-outline-secondary' }}" title="Mark as Correct">
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
                                        <form action="{{ route('center.options.destroy', $option) }}" method="POST" class="ms-2" onsubmit="return confirm('Delete option?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        @if($question->type == 'mcq')
                            <div class="mt-2">
                                <form action="{{ route('center.options.store', $question) }}" method="POST" class="d-flex">
                                    @csrf
                                    <input type="text" name="content" class="form-control form-control-sm me-2" placeholder="New Option" required>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Add Option</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Edit Question Modal -->
                <div class="modal fade" id="editQuestionModal-{{ $question->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('center.questions.update', $question) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Question</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Question Content</label>
                                        <textarea name="content" class="form-control" rows="3" required>{{ $question->content }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Points</label>
                                        <input type="number" name="points" class="form-control" value="{{ $question->points }}" min="1" required>
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
        </div>
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('center.questions.store', $quiz) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Question Content</label>
                        <textarea name="content" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Type</label>
                        <select name="type" class="form-select">
                            <option value="mcq">Multiple Choice</option>
                            <option value="true_false">True / False</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Points</label>
                        <input type="number" name="points" class="form-control" value="1" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Question</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
