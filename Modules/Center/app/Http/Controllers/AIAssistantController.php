<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ContentAssistantService;
use Illuminate\Http\Request;

class AIAssistantController extends Controller
{
    protected $assistant;

    public function __construct(ContentAssistantService $assistant)
    {
        $this->assistant = $assistant;
    }

    public function generateOutline(Request $request)
    {
        $this->authorize('create', \App\Models\Course::class);
        $request->validate([
            'topic' => 'required|string|max:200',
            'level' => 'sometimes|in:beginner,intermediate,advanced',
        ]);

        $result = $this->assistant->generateCourseOutline(
            $request->topic,
            $request->input('level', 'beginner')
        );

        return response()->json($result);
    }

    public function generateQuiz(Request $request)
    {
        $this->authorize('create', \App\Models\Course::class);
        $request->validate([
            'topic' => 'required|string|max:200',
            'questions' => 'sometimes|integer|min:3|max:10',
        ]);

        $result = $this->assistant->generateQuiz(
            $request->topic,
            $request->input('questions', 5)
        );

        return response()->json($result);
    }

    public function improveDescription(Request $request)
    {
        $this->authorize('create', \App\Models\Course::class);
        $request->validate([
            'description' => 'required|string|max:1000',
        ]);

        $result = $this->assistant->improveCourseDescription($request->description);

        return response()->json($result);
    }
}
