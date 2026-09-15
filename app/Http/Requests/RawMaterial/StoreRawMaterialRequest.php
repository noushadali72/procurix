<?php

namespace App\Http\Requests\RawMaterial;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRawMaterialRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],

            'sku' => [
                'nullable',
                'string',
                'max:255',
                'unique:raw_materials,sku',
            ],

            'cost_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'unit_id' => [
                'required',
                'exists:units,id',
            ],

            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The raw material name is required.',

            'sku.unique' => 'The SKU must be unique.',

            'cost_price.required' => 'The cost price is required.',
            'cost_price.numeric' => 'The cost price must be a number.',
            'cost_price.min' => 'The cost price must be at least 0.',

            'stock.required' => 'The stock quantity is required.',
            'stock.numeric' => 'The stock quantity must be a number.',
            'stock.min' => 'The stock quantity must be at least 0.',

            'unit_id.required' => 'The unit is required.',
            'unit_id.exists' => 'The selected unit is invalid.',

            'minimum_stock.required' => 'The minimum stock level is required.',
            'minimum_stock.numeric' => 'The minimum stock level must be a number.',
            'minimum_stock.min' => 'The minimum stock level must be at least 0.',
        ];
    }
}