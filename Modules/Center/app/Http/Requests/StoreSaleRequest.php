<?php

namespace Modules\Center\Http\Requests;

use App\Models\Sale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Sale::class);
    }

    public function rules(): array
    {
        $tenantId = app('tenant')->id;

        return [
            'student_id' => [
                'required',
                Rule::exists('students', 'id')->where('tenant_id', $tenantId),
            ],
            'items' => 'required|array|min:1',
            'items.*.id' => [
                'required',
                Rule::exists('courses', 'id')->where('tenant_id', $tenantId),
            ],
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'paid_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
        ];
    }
}
