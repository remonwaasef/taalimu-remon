<?php

namespace App\Http\Requests\Center;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('student');
        $student = \App\Models\Student::find($id);
        $userId = $student ? $student->user_id : null;

        $tenantId = app()->bound('tenant') ? app('tenant')->id : null;

        return [
            'name' => ['nullable', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'code' => [
                'nullable',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('students', 'code')->ignore($id)->where('tenant_id', $tenantId),
            ],
            'national_id' => [
                'nullable',
                'string',
                'max:20',
                \Illuminate\Validation\Rule::unique('students', 'national_id')->ignore($id)->where('tenant_id', $tenantId),
            ],
            'email' => [
                'nullable',
                'email',
                \Illuminate\Validation\Rule::unique('users', 'email')->ignore($userId)->where('tenant_id', $tenantId),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:10',
                \Illuminate\Validation\Rule::unique('users', 'phone')->ignore($userId)->where('tenant_id', $tenantId),
            ],
            'parent_phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'parent_email' => 'nullable|email|max:255',
            'grade_id' => 'nullable|exists:grades,id',
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
        ];
    }

    public function messages()
    {
        return [
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
