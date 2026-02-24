<?php

namespace App\Http\Requests\Center;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstructorRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'email' => 'nullable|email|max:255',
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'specialization' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-\.]+$/u'],
            'status' => 'required|in:active,inactive,on_hold',
            'commission_rate' => 'required|numeric|min:0',
            'commission_type' => 'required|in:percentage,fixed',
            'national_id' => 'nullable|string|max:30',
            'gender' => 'nullable|in:male,female',
            'hiring_date' => 'nullable|date',
            'bio' => 'nullable|string|max:2000',
            'image' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم المدرس مطلوب.',
            'name.regex' => 'اسم المدرس يجب أن يحتوي على حروف فقط.',
            'name.max' => 'اسم المدرس يجب ألا يتجاوز 255 حرفاً.',
            'phone.regex' => 'رقم الهاتف يجب أن يحتوي على أرقام فقط.',
            'phone.min' => 'رقم الهاتف يجب أن لا يقل عن 10 أرقام.',
            'email.email' => 'البريد الإلكتروني غير صحيح.',
            'specialization.required' => 'تخصص المدرس مطلوب.',
            'specialization.regex' => 'التخصص يجب أن يحتوي على نص صحيح.',
            'commission_rate.required' => 'نسبة/مبلغ العمولة مطلوب.',
            'commission_type.required' => 'نوع العمولة مطلوب.',
            'bio.string' => 'النبذة يجب أن تكون نصاً.',
            'image.image' => 'الملف المرفق يجب أن يكون صورة.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
