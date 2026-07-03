<?php

namespace App\Http\Requests\Center;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstructorRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => 'nullable|email|max:255',
            'phone' => ['required', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'specialization' => ['required', 'string', 'max:100'],
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
     */
    public function messages(): array
    {
        return [
            'name.required' => __('center::instructors.val_name_required'),
            'name.regex' => __('center::instructors.val_name_regex'),
            'name.max' => __('center::instructors.val_name_max'),
            'phone.regex' => __('center::instructors.val_phone_regex'),
            'phone.min' => __('center::instructors.val_phone_min'),
            'email.email' => __('center::instructors.val_email_email'),
            'specialization.required' => __('center::instructors.val_specialization_required'),
            'specialization.regex' => __('center::instructors.val_specialization_regex'),
            'commission_rate.required' => __('center::instructors.val_commission_rate_required'),
            'commission_type.required' => __('center::instructors.val_commission_type_required'),
            'bio.string' => __('center::instructors.val_bio_string'),
            'image.image' => __('center::instructors.val_image_image'),
            'image.max' => __('center::instructors.val_image_max'),
        ];
    }
}
