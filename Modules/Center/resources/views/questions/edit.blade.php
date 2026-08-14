@extends('center::layouts.app-next')

@section('panel-content')
<div class="container-fluid p-0">
    <div class="mb-4">
        <a href="{{ route('center.questions.index') }}" class="text-muted text-decoration-none small">
            <i class="fas fa-arrow-right me-1"></i>{{ __('center::questions.back_to_list') }}</a>
        <h4 class="fw-bold mt-2">{{ __('center::questions.edit_title') }}</h4>
    </div>

    <form action="{{ route('center.questions.update', $question) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::questions.question_text') }}</label>
                            <textarea name="content" class="form-control rounded-4" rows="4" placeholder="{{ __('center::questions.content_placeholder') }}" required>{{ old('content', $question->content) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::questions.options') }}</label>
                            <ul class="list-group">
                                @foreach($question->options as $index => $option)
                                    <li class="list-group-item d-flex align-items-center {{ $option->is_correct ? 'list-group-item-success' : '' }}">
                                        <span class="badge bg-{{ $option->is_correct ? 'success' : 'secondary' }} rounded-pill me-3">
                                            {{ $option->is_correct ? __('center::questions.correct') : __('center::questions.option') . ' ' . ($index + 1) }}
                                        </span>
                                        <span>{{ $option->content }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">{{ __('center::questions.explanation') }}</label>
                            <textarea name="explanation" class="form-control rounded-4" rows="2" placeholder="{{ __('center::questions.explanation_placeholder') }}">{{ old('explanation', $question->explanation) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-bold mb-0">{{ __('center::questions.question_settings') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">{{ __('center::questions.category') }}</label>
                            <select name="category_id" class="form-select rounded-pill">
                                <option value="">{{ __('center::questions.select_category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $question->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">{{ __('center::questions.difficulty') }}</label>
                            <select name="difficulty" class="form-select rounded-pill">
                                @foreach(['easy', 'medium', 'hard'] as $level)
                                    <option value="{{ $level }}" {{ old('difficulty', $question->difficulty) == $level ? 'selected' : '' }}>{{ __('center::questions.' . $level) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">{{ __('center::questions.points') }}</label>
                            <input type="number" name="points" class="form-control rounded-pill" value="{{ old('points', $question->points) }}" min="1">
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">{{ __('center::questions.type') }}</label>
                            <select name="type" class="form-select rounded-pill">
                                <option value="mcq" {{ old('type', $question->type) == 'mcq' ? 'selected' : '' }}>{{ __('center::questions.mcq') }}</option>
                                <option value="true_false" {{ old('type', $question->type) == 'true_false' ? 'selected' : '' }}>{{ __('center::questions.true_false') }}</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm">
                            <i class="fas fa-save me-2"></i>{{ __('center::questions.save_question') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
