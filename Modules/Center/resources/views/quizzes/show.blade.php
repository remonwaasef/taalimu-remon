@extends('center::layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header sticky-top bg-white shadow-sm d-flex justify-content-between align-items-center" style="z-index: 1000;">
                    <h3 class="mb-0">{{ $quiz->title }}</h3>
                    @if($quiz->duration_minutes > 0 && isset($endTime))
                        <div class="text-center">
                            <small class="text-muted d-block">Time Remaining</small>
                            <span id="timer" class="badge bg-danger fs-5">
                                <i class="fas fa-clock"></i> --:--
                            </span>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if($quiz->description)
                        <p class="text-muted mb-4">{{ $quiz->description }}</p>
                    @endif

                    <form action="{{ route('center.quizzes.submit', $quiz) }}" method="POST" id="quizForm">
                        @csrf
                        
                        @foreach($quiz->questions as $index => $question)
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">
                                        <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                        {{ $question->content }}
                                    </h5>
                                    <div class="list-group">
                                        @foreach($question->options as $option)
                                            <label class="list-group-item list-group-item-action border-0 rounded mb-2" style="background-color: #f8f9fa;">
                                                <input class="form-check-input me-2" type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required>
                                                {{ $option->content }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i> Submit Quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($quiz->duration_minutes > 0 && isset($endTime))
            const endTime = new Date("{{ $endTime->format('Y-m-d H:i:s') }}").getTime();
            const timerElement = document.getElementById('timer');
            const form = document.getElementById('quizForm');
            
            const timerInterval = setInterval(function() {
                const now = new Date().getTime();
                const distance = endTime - now;
                
                if (distance < 0) {
                    clearInterval(timerInterval);
                    timerElement.innerHTML = "EXPIRED";
                    // Auto submit
                    alert('Time is up! Your quiz will be submitted automatically.');
                    form.submit();
                    return;
                }
                
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                timerElement.innerHTML = minutes + "m " + seconds + "s ";
                
                // Visual warning when less than 1 minute
                if (distance < 60000) {
                    timerElement.classList.remove('bg-danger');
                    timerElement.classList.add('bg-warning', 'text-dark', 'animate__animated', 'animate__flash');
                }
            }, 1000);
        @endif

        // Prevent accidental navigation
        window.onbeforeunload = function() {
            return "Are you sure you want to leave? Your progress might be lost.";
        };

        document.getElementById('quizForm').onsubmit = function() {
            window.onbeforeunload = null;
        };
    });
</script>
@endpush
@endsection
