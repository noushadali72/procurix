<?php

namespace App\Http\Requests\Unit;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUnitRequest extends FormRequest
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
            'name'=> ['required', 'string', 'max:255'],
            'short_name'=> [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'short_name'),
                ],
            'unit_category_id'=> ['required', 'exists:unit_categories,id'],
            'is_base'=> ['nullable', 'boolean'],
            'conversion_factor'=> ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function message():array
    {
        return [
            'name.required' => 'The name field is required.',
            'short_name.required' => 'The short name field is required.',
            'short_name.unique' => 'The short name has already been taken.',
            'unit_category_id.required' => 'The unit category field is required.',
            'unit_category_id.exists' => 'The selected unit category is invalid.',
            'is_base.boolean' => 'The is base field must be a boolean.',
            'conversion_factor.numeric' => 'The conversion factor must be a number.',
            'conversion_factor.min' => 'The conversion factor must be at least 0.',

        ];
    }
        protected function prepareForValidation(): void
        {
            $this->merge([
                'is_base' => $this->boolean('is_base'),
            ]);
        }

}
