<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\QuestionOption;

class QuestionBankController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Question::class);
        $questions = Question::with('category')->latest()->paginate(20);
        $categories = QuestionCategory::select('id', 'name', 'slug')->get();
        return view('center::questions.index', compact('questions', 'categories'));
    }

    public function create()
    {
        $this->authorize('create', Question::class);
        $categories = QuestionCategory::select('id', 'name', 'slug')->get();
        return view('center::questions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Question::class);
        $validated = $request->validate([
            'category_id' => 'nullable|exists:question_categories,id',
            'content' => 'required|string|min:5',
            'type' => 'required|in:mcq,true_false',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'options' => 'required|array|min:2',
            'options.*.content' => 'required|string',
            'correct_option' => 'required|integer',
        ]);

        $question = Question::create($validated);

        foreach ($request->options as $index => $optionData) {
            $question->options()->create([
                'content' => $optionData['content'],
                'is_correct' => $index == $request->correct_option,
            ]);
        }

        return redirect()->route('center.questions.index')->with('success', 'Question added to bank.');
    }

    public function edit(Question $question)
    {
        $this->authorize('update', $question);
        $categories = QuestionCategory::select('id', 'name', 'slug')->get();
        $question->load('options');
        return view('center::questions.edit', compact('question', 'categories'));
    }

    public function update(Request $request, Question $question)
    {
        $this->authorize('update', $question);
        $validated = $request->validate([
            'category_id' => 'nullable|exists:question_categories,id',
            'content' => 'required|string|min:5',
            'type' => 'required|in:mcq,true_false',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
        ]);

        $question->update($validated);

        return redirect()->route('center.questions.index')->with('success', 'Question updated.');
    }

    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);
        $question->delete();
        return back()->with('success', 'Question removed from bank.');
    }

    // Category Methods
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        QuestionCategory::create(['name' => $request->name]);
        return back()->with('success', 'Category created.');
    }
}
