<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Services\GamificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class QuizService
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }
    /**
     * Get questions for a quiz (fixed or randomized from bank).
     */
    public function getQuizQuestions(Quiz $quiz)
    {
        if (!$quiz->is_randomized) {
            return $quiz->questions()->with('options')->get();
        }

        $query = Question::where('tenant_id', $quiz->tenant_id ?? \Modules\Tenancy\Services\TenantResolver::get()->id)
            ->with(['options' => function($q) {
                $q->inRandomOrder();
            }]);

        if ($quiz->category_id) {
            $query->where('category_id', $quiz->category_id);
        }

        if ($quiz->random_questions_count) {
            return $query->inRandomOrder()->limit($quiz->random_questions_count)->get();
        }

        return $query->inRandomOrder()->get();
    }

    /**
     * Submit a quiz and calculate results.
     */
    public function submitQuiz(Quiz $quiz, array $answers)
    {
        $score = 0;
        $totalPoints = $quiz->questions->sum('points');
        
        foreach ($answers as $questionId => $optionId) {
            $option = QuestionOption::find($optionId);
            // Ensure the option belongs to a question in this quiz
            if ($option && $option->question->quiz_id === $quiz->id && $option->is_correct) {
                $score += $option->question->points;
            }
        }

        $percentage = ($totalPoints > 0) ? ($score / $totalPoints) * 100 : 0;
        $passed = $percentage >= $quiz->passing_score;

        $attempt = $quiz->attempts()->create([
            'user_id' => Auth::id(),
            'score' => $percentage, 
            'passed' => $passed,
            'completed_at' => now(),
        ]);

        if ($passed) {
            $this->gamificationService->awardPoints(
                Auth::user(), 
                (int)$score, 
                "Passed quiz: " . $quiz->title, 
                $attempt
            );
        }

        return $attempt;
    }

    /**
     * Validate if the quiz was submitted within the time limit.
     */
    public function isTimeValid(Quiz $quiz, $startTime)
    {
        if (!$quiz->duration_minutes || !$startTime) {
            return true;
        }

        $startTime = Carbon::parse($startTime);
        $allowedTime = $startTime->copy()->addMinutes($quiz->duration_minutes)->addMinute(); // 1 min buffer
        
        return now()->lessThanOrEqualTo($allowedTime);
    }

    /**
     * Check if user already passed the quiz.
     */
    public function hasPassed(Quiz $quiz, $userId)
    {
        return $quiz->attempts()
            ->where('user_id', $userId)
            ->where('passed', true)
            ->exists();
    }
}


