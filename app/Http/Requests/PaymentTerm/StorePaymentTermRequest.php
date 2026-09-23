<?php

namespace App\Http\Requests\PaymentTerm;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentTermRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>[
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'due_days'=>[
                'required',
                'integer',
                'gt:1'
            ],
            'discount_days'=>[
                'nullable',
                'integer',
                'min:0'
            ],
            'discount_percentage'=>[
                'nullable',
                'numeric',
                'min:0'
            ]
        ];
    }
}
