<?php

namespace App\Http\Requests\PurchaseReturn;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'return_date' => ['required', 'date'],
            'reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.goods_receipt_item_id' => [
                'required',
                'integer',
                'exists:goods_receipt_items,id'
            ],
            'items.*.qty' => [
                'nullable',
                'numeric',
                'gte:0'
            ],
        ];
    }
}
