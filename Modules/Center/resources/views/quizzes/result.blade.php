@extends('center::layouts.hope-master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    @if($attempt->passed)
                        <i class="fas fa-check-circle text-success fa-5x mb-3"></i>
                        <h2 class="text-success">Congratulations!</h2>
                        <p class="lead">You passed the quiz.</p>
                    @else
                        <i class="fas fa-times-circle text-danger fa-5x mb-3"></i>
                        <h2 class="text-danger">Keep Trying!</h2>
                        <p class="lead">You didn't pass this time.</p>
                    @endif

                    <div class="display-1 fw-bold my-4 {{ $attempt->passed ? 'text-success' : 'text-danger' }}">
                        {{ $attempt->score }}%
                    </div>

                    <p class="text-muted">Passing Score: {{ $attempt->quiz->passing_score }}%</p>

                    <div class="mt-4">
                        <a href="{{ route('center.courses.player', ['course' => $attempt->quiz->lesson->section->course_id, 'lesson' => $attempt->quiz->lesson_id]) }}" class="btn btn-primary">Back to Lesson</a>
                        @if(!$attempt->passed)
                            <a href="{{ route('center.quizzes.show', $attempt->quiz) }}" class="btn btn-outline-secondary">Retake Quiz</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
