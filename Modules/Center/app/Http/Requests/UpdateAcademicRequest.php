<?php

namespace Modules\Center\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', app('tenant'));
    }

    public function rules(): array
    {
        return [
            'stages' => 'nullable|array',
            'stages.*.id' => 'nullable|exists:stages,id',
            'stages.*.name' => 'required|string|max:255',
            'stages.*.grades' => 'nullable|array',
            'stages.*.grades.*.id' => 'nullable|exists:grades,id',
            'stages.*.grades.*.name' => 'required|string|max:255',
            'deleted_stages' => 'nullable|array',
            'deleted_grades' => 'nullable|array',
            'settings' => 'nullable|array',
        ];
    }
}
