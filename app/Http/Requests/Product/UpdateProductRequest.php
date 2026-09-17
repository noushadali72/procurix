<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
                Rule::unique('products', 'sku')->ignore($this->route('product')->id),
            ],
            'unit_id' => ['required', 'exists:units,id'],
            'category_id'=>['nullable','exists:categories,id'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'sku.unique' => 'The SKU must be unique.',
            'unit_id.required' => 'The unit is required.',
            'unit_id.exists' => 'The selected unit is invalid.',
            'category_id.exists'=>'The selected category is invalid.',
            'cost_price.numeric' => 'The cost price must be a number.',
            'cost_price.min' => 'The cost price must be at least 0.',
            'sale_price.numeric' => 'The sale price must be a number.',
            'sale_price.min' => 'The sale price must be at least 0.',
            'stock.required' => 'The stock quantity is required.',
            'stock.integer' => 'The stock quantity must be an integer.',
            'stock.min' => 'The stock quantity must be at least 0.',
            'minimum_stock.required' => 'The minimum stock level is required.',
            'minimum_stock.integer' => 'The minimum stock level must be an integer.',
            'minimum_stock.min' => 'The minimum stock level must be at least 0.',
        ];
    }
}
