<?php

namespace App\Http\Requests\Center;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Parent Controller handles authorization with Gate
    }

    public function rules()
    {
        $tenantId = app()->bound('tenant') ? app('tenant')->id : null;

        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'code' => [
                'nullable', // Service will generate if null
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('students', 'code')->where('tenant_id', $tenantId),
            ],
            'national_id' => [
                'nullable',
                'string',
                'max:20',
                \Illuminate\Validation\Rule::unique('students', 'national_id')->where('tenant_id', $tenantId),
            ],
            'email' => [
                'nullable',
                'email',
                \Illuminate\Validation\Rule::unique('users', 'email')->where('tenant_id', $tenantId),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:10',
                \Illuminate\Validation\Rule::unique('users', 'phone')->where('tenant_id', $tenantId),
            ],
            'parent_phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'parent_email' => 'nullable|email|max:255',
            'grade_id' => 'required|exists:grades,id',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string|max:255',
            'parent_name' => ['nullable', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'parent_job' => ['nullable', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'parent_relation' => ['nullable', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'emergency_phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'school_name' => 'nullable|string|max:255',
            'section_type' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'profile_photo' => 'nullable|image|max:2048',
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
        ];
    }

    public function messages()
    {
        return [
            'course_ids.required' => 'يجب اختيار مجموعة دراسية واحدة على الأقل لتسجيل الطالب بها.',
            'course_ids.min' => 'يجب اختيار مجموعة دراسية واحدة على الأقل لتسجيل الطالب بها.',
            'name.regex' => __('center::students.val_name_regex'),
            'phone.regex' => __('center::students.val_phone_regex'),
            'phone.min' => __('center::students.val_phone_min'),
            'parent_phone.regex' => __('center::students.val_parent_phone_regex'),
            'parent_phone.min' => __('center::students.val_parent_phone_min'),
            'emergency_phone.regex' => __('center::students.val_emergency_phone_regex'),
            'emergency_phone.min' => __('center::students.val_emergency_phone_min'),
            'parent_name.regex' => __('center::students.val_parent_name_regex'),
            'parent_job.regex' => __('center::students.val_parent_job_regex'),
            'parent_relation.regex' => __('center::students.val_parent_relation_regex'),
            'section_type.regex' => __('center::students.val_section_type_regex'),
            'email.unique' => __('center::students.val_email_unique'),
            'grade_id.exists' => __('center::students.val_grade_id_exists'),
        ];
    }
}
