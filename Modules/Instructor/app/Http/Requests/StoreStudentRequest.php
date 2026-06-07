<?php

namespace Modules\Instructor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required_without:student_id|string|max:255',
            'phone' => 'required_without:student_id|string|digits:11',
            'parent_phone' => 'required_without:student_id|string|digits:11',
            'parent_email' => 'nullable|email|max:255',
            'email' => 'nullable|email|max:255',
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
            'student_id' => 'nullable|exists:students,id',
        ];
    }
}
