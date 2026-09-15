<?php

namespace App\Http\Requests\ManufacturingFormula;

use App\Models\Unit;
use App\Models\RawMaterial;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateManufacturingFormulaRequest extends FormRequest
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
            'product_id' => ['required', 'exists:products,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.raw_material_id' => [
                'required',
                'exists:raw_materials,id',
                'distinct',
            ],
            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.1',
            ],
            'items.*.unit_id' => [
                'required',
                'exists:units,id',
            ],
        ];
    }
   public function messages(): array
    {
        return [
            'product_id.required' => 'The product field is required.',
            'product_id.exists' => 'The selected product is invalid.',
            'name.required' => 'The name field is required.',
            'items.required' => 'At least one raw material is required.',
            'items.*.raw_material_id.required' => 'The raw material field is required.',
            'items.*.raw_material_id.exists' => 'The selected raw material is invalid.',
            'items.*.raw_material_id.distinct' => 'Duplicate raw materials are not allowed',
            'items.*.quantity.required' => 'The quantity field is required.',
            'items.*.quantity.numeric' => 'The quantity must be a number.',
            'items.*.quantity.min' => 'The quantity must be at least 0.1.',
            'items.*.unit_id.required' => 'The unit field is required.',
            'items.*.unit_id.exists' => 'The selected unit is invalid.',

        ];
    }
        public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            foreach ($this->items ?? [] as $index => $item) {
                $rawMaterial = RawMaterial::with('unit')->find($item['raw_material_id']);
                $unit = Unit::find($item['unit_id']);
                if (
                    $rawMaterial &&
                    $unit &&
                    $rawMaterial->unit->unit_category_id !== $unit->unit_category_id
                ) {
                    $validator->errors()->add(
                        "items.$index.unit_id",
                        'The selected unit must belong to the same category as the raw material unit.'
                    );
                }
            }
        });
    }
}
