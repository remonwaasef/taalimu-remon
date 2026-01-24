<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    protected $quizService;

    public function __construct(\App\Services\QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', \App\Models\Quiz::class);
        $tenantId = app('tenant')->id;
        
        // Fetch quizzes belonging to this tenant's courses
        $quizzes = \App\Models\Quiz::whereHas('lesson.section.course', function($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        })->with('lesson.section.course')->get();

        $recentAttempts = \App\Models\QuizAttempt::whereHas('quiz.lesson.section.course', function($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        })->with(['quiz', 'user'])->latest()->take(10)->get();

        return view('center::quizzes.index', compact('quizzes', 'recentAttempts'));
    }

    public function store(Request $request, \App\Models\Lesson $lesson)
    {
        $this->authorize('update', $lesson->section->course);
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'passing_score' => 'required|integer|min:0|max:100',
            'duration_minutes' => 'nullable|integer|min:1|max:480', // Max 8 hours
        ]);

        $quiz = $lesson->quiz()->create($validated);

        return redirect()->route('center.quizzes.edit', $quiz)->with('success', 'Quiz created.');
    }

    public function edit(\App\Models\Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $quiz->load('questions.options');
        $categories = \App\Models\QuestionCategory::select('id', 'name', 'slug')->get();
        return view('center::quizzes.edit', compact('quiz', 'categories'));
    }

    public function update(\App\Http\Requests\Center\UpdateQuizRequest $request, \App\Models\Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        
        $quiz->update($request->validated());

        return back()->with('success', 'Quiz updated.');
    }

    public function storeQuestion(Request $request, \App\Models\Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $validated = $request->validate([
            'content' => 'required|string|min:5|max:2000',
            'type' => 'required|in:mcq,true_false',
            'points' => 'required|integer|min:1|max:100',
        ]);

        $question = $quiz->questions()->create($validated);

        // Create default options based on type
        if ($request->type == 'true_false') {
            $question->options()->create(['content' => 'True', 'is_correct' => false]);
            $question->options()->create(['content' => 'False', 'is_correct' => false]);
        } else {
            // Create 2 empty options for MCQ
            $question->options()->create(['content' => 'Option 1', 'is_correct' => false]);
            $question->options()->create(['content' => 'Option 2', 'is_correct' => false]);
        }

        return back()->with('success', 'Question added.');
    }

    public function updateQuestion(Request $request, \App\Models\Question $question)
    {
        $this->authorize('update', $question->quiz);
        $request->validate([
            'content' => 'required|string|min:5|max:2000',
            'points' => 'required|integer|min:1|max:100',
        ]);

        $question->update($request->only('content', 'points'));

        return back()->with('success', 'Question updated.');
    }

    public function destroyQuestion(\App\Models\Question $question)
    {
        $this->authorize('update', $question->quiz);
        $question->delete();
        return back()->with('success', 'Question deleted.');
    }

    public function storeOption(Request $request, \App\Models\Question $question)
    {
        $this->authorize('update', $question->quiz);
        $request->validate(['content' => 'required|string|min:1|max:500']);
        $question->options()->create(['content' => $request->content, 'is_correct' => false]);
        return back()->with('success', 'Option added.');
    }

    public function updateOption(Request $request, \App\Models\QuestionOption $option)
    {
        $this->authorize('update', $option->question->quiz);
        $request->validate(['content' => 'required|string|min:1|max:500']);
        $option->update(['content' => $request->content]);
        return back()->with('success', 'Option updated.');
    }

    public function destroyOption(\App\Models\QuestionOption $option)
    {
        $this->authorize('update', $option->question->quiz);
        $option->delete();
        return back()->with('success', 'Option deleted.');
    }

    public function setCorrectOption(\App\Models\QuestionOption $option)
    {
        $this->authorize('update', $option->question->quiz);
        // Reset other options for this question
        $option->question->options()->update(['is_correct' => false]);
        $option->update(['is_correct' => true]);
        return back()->with('success', 'Correct answer set.');
    }

    // Student Methods
    public function show(\App\Models\Quiz $quiz)
    {
        $this->authorize('view', $quiz);
        
        if ($this->quizService->hasPassed($quiz, auth()->id())) {
            return redirect()->route('center.quizzes.result', [
                'attempt' => $quiz->attempts()->where('user_id', auth()->id())->where('passed', true)->first()->id
            ])->with('info', 'You have already passed this quiz.');
        }

        $sessionKey = 'quiz_start_' . $quiz->id . '_' . auth()->id();
        if (!session()->has($sessionKey)) {
            session([$sessionKey => now()]);
        }

        $startTime = session($sessionKey);
        $endTime = null;
        if ($quiz->duration_minutes) {
            $endTime = \Carbon\Carbon::parse($startTime)->addMinutes($quiz->duration_minutes);
        }

        $questions = $this->quizService->getQuizQuestions($quiz);
        return view('center::quizzes.show', compact('quiz', 'endTime', 'questions'));
    }

    public function submit(Request $request, \App\Models\Quiz $quiz)
    {
        $this->authorize('view', $quiz);
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|exists:question_options,id',
        ]);

        $sessionKey = 'quiz_start_' . $quiz->id . '_' . auth()->id();
        $startTime = session($sessionKey);

        if (!$this->quizService->isTimeValid($quiz, $startTime)) {
            return back()->with('error', 'Time limit exceeded. Submission rejected.');
        }

        $attempt = $this->quizService->submitQuiz($quiz, $request->answers);

        // Clear session
        session()->forget($sessionKey);

        return redirect()->route('center.quizzes.result', $attempt);
    }

    public function result(\App\Models\QuizAttempt $attempt)
    {
        $this->authorize('view', $attempt);
        return view('center::quizzes.result', compact('attempt'));
    }

    // Unused resource methods removed
}
