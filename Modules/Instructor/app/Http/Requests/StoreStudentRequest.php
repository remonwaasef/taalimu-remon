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
        $tenantId = app('tenant')->id;

        return [
            'name' => ['required_without:student_id', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'phone' => [
                'required_without:student_id',
                'string',
                'max:20',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:10',
            ],
            'parent_phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'parent_email' => 'nullable|email|max:255',
            'email' => 'nullable|email|max:255',
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
            'student_id' => 'nullable|exists:students,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'الاسم يجب أن يحتوي على حروف ومسافات فقط.',
            'phone.regex' => 'صيغة رقم الهاتف غير صحيحة.',
            'phone.min' => 'رقم الهاتف يجب أن يكون 10 أرقام على الأقل.',
            'parent_phone.regex' => 'صيغة رقم ولي الأمر غير صحيحة.',
            'parent_phone.min' => 'رقم ولي الأمر يجب أن يكون 10 أرقام على الأقل.',
        ];
    }
}
