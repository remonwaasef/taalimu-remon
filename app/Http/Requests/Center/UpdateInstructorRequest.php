<?php

namespace App\Http\Requests\Center;

class UpdateInstructorRequest extends StoreInstructorRequest
{
    /**
     * Same rules as store, except the phone may be left empty on update.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['phone'] = ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'];
        $rules['email'] = ['nullable', 'email', 'max:255', \Illuminate\Validation\Rule::unique('instructors', 'email')->ignore($this->route('instructor'))->where('tenant_id', app('tenant')->id)];

        return $rules;
    }
}
