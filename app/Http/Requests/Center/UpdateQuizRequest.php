<?php

namespace App\Http\Requests\Center;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in Controller via Policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'passing_score' => 'required|integer|min:0|max:100',
            'duration_minutes' => 'nullable|integer|min:0',
            'is_randomized' => 'boolean',
            'random_questions_count' => 'nullable|integer|min:1',
            'category_id' => 'nullable|exists:question_categories,id',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('is_randomized')) {
            $this->merge([
                'is_randomized' => $this->boolean('is_randomized'),
            ]);
        }
    }
}
