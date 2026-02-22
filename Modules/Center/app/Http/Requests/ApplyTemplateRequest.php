<?php

namespace Modules\Center\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', app('tenant'));
    }

    public function rules(): array
    {
        return [
            'template_key' => 'required|string|in:' . implode(',', array_keys(config('academic.templates', []))),
        ];
    }
}
