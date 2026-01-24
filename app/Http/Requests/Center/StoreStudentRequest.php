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
        $tenantId = app('tenant')->id;
        
        return [
            'name' => ['nullable', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'code' => [
                'nullable', // Service will generate if null
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('students', 'code')->where('tenant_id', $tenantId)
            ],
            'national_id' => [
                'nullable',
                'string',
                'max:20',
                \Illuminate\Validation\Rule::unique('students', 'national_id')->where('tenant_id', $tenantId)
            ],
            'email' => 'nullable|email|unique:users,email',
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'parent_phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
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
            'name.regex' => 'اسم الطالب يجب أن يحتوي على حروف فقط.',
            'phone.regex' => 'رقم الهاتف يجب أن يحتوي على أرقام فقط.',
            'phone.min' => 'رقم الهاتف يجب أن لا يقل عن 10 أرقام.',
            'parent_phone.regex' => 'رقم ولي الأمر يجب أن يحتوي على أرقام فقط.',
            'parent_phone.min' => 'رقم ولي الأمر يجب أن لا يقل عن 10 أرقام.',
            'emergency_phone.regex' => 'رقم الطوارئ يجب أن يحتوي على أرقام فقط.',
            'emergency_phone.min' => 'رقم الطوارئ يجب أن لا يقل عن 10 أرقام.',
            'parent_name.regex' => 'اسم ولي الأمر يجب أن يحتوي على حروف فقط.',
            'parent_job.regex' => 'الوظيفة يجب أن تحتوي على حروف فقط.',
            'parent_relation.regex' => 'صلة القرابة يجب أن تحتوي على حروف فقط.',
            'section_type.regex' => 'الشعبة يجب أن تحتوي على حروف فقط.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'grade_id.exists' => 'الصف الدراسي المختار غير صحيح.',
        ];
    }
}
