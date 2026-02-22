<?php

namespace Modules\Center\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', app('tenant'));
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'timezone' => 'nullable|string|in:' . implode(',', timezone_identifiers_list()),
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'settings' => 'nullable|array',
        ];
    }
}
