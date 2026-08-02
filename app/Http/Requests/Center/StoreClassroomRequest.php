<?php

namespace App\Http\Requests\Center;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'type' => 'nullable|string|in:hall,lab,virtual',
            'color' => 'nullable|string|max:7',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('center::classrooms.validation_name_required'),
            'capacity.min' => __('center::classrooms.validation_capacity_min'),
        ];
    }
}
