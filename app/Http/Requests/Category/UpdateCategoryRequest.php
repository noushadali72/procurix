<?php

namespace App\Http\Requests\Category;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Enums\CostingMethod;

class UpdateCategoryRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:50',
            'slug' => 'nullable|string|min:2|max:100',
            'description' => 'nullable|string|min:5|max:255',
            'purchase_account_id' => 'nullable|exists:accounts,id',
            'inventory_account_id' => 'nullable|exists:accounts,id',
            'sales_account_id' => 'nullable|exists:accounts,id',
            'costing_method' => [
                'nullable',
                new Enum(CostingMethod::class),
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'costing_method.enum' => 'Please select a valid costing method (Standard or Moving Average).',
        ];
    }
}
