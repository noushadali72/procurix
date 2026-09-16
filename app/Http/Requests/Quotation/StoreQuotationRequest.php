<?php

namespace App\Http\Requests\Quotation;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreQuotationRequest extends FormRequest
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
            'purchase_request_id' => [
                'required',
                'exists:purchase_requests,id',

            ],

            'vendor_id' => [
                'required',
                'exists:vendors,id',
            ],

            'quotation_number' => [
                'nullable',
                'integer',
            ],

            'status' => [
                'required',
                'in:pending,accepted,expired',
            ],

            'quotation_date' => [
                'required',
                'date',
            ],

            'valid_until' => [
                'nullable',
                'date',
                'after_or_equal:quotation_date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.raw_material_id' => [
                'required',
                'exists:raw_materials,id',
            ],

            'items.*.qty' => [
                'required',
                'numeric',
                'min:0.1',
            ],

            'items.*.unit_id' => [
                'required',
                'exists:units,id',
            ],

            'items.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function messages(): array
{
    return [
        'purchase_request_id.required' =>
            'The purchase request is required.',

        'purchase_request_id.exists' =>
            'The selected purchase request is invalid.',


        'vendor_id.required' =>
            'Please select a vendor.',

        'vendor_id.exists' =>
            'The selected vendor is invalid.',


        'quotation_number.integer' =>
            'The quotation number must be a valid number.',


        'status.required' =>
            'Please select a quotation status.',

        'status.in' =>
            'The selected quotation status is invalid.',


        'quotation_date.required' =>
            'The quotation date is required.',

        'quotation_date.date' =>
            'Please enter a valid quotation date.',


        'valid_until.date' =>
            'Please enter a valid expiry date.',

        'valid_until.after_or_equal' =>
            'The valid until date must be on or after the quotation date.',


        'notes.string' =>
            'The notes must be valid text.',


        'items.required' =>
            'At least one quotation item is required.',

        'items.array' =>
            'The quotation items are invalid.',

        'items.min' =>
            'At least one quotation item is required.',


        'items.*.raw_material_id.required' =>
            'Please select a raw material.',

        'items.*.raw_material_id.exists' =>
            'The selected raw material is invalid.',


        'items.*.qty.required' =>
            'Please enter the quantity.',

        'items.*.qty.numeric' =>
            'The quantity must be a valid number.',

        'items.*.qty.min' =>
            'The quantity must be at least 0.1.',


        'items.*.unit_id.required' =>
            'Please select a unit.',

        'items.*.unit_id.exists' =>
            'The selected unit is invalid.',


        'items.*.price.required' =>
            'Please enter the unit price.',

        'items.*.price.numeric' =>
            'The unit price must be a valid number.',

        'items.*.price.min' =>
            'The unit price cannot be negative.',
    ];
}

}
