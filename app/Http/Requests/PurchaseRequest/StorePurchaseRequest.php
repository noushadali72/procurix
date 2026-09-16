<?php

namespace App\Http\Requests\PurchaseRequest;

use App\Models\RawMaterial;
use App\Models\Unit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    'completed',
                    'pending',
                    'active',
                    'draft'
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],
            'due_date'=>'nullable|date',

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.raw_material_id' => [
                'required',
                'integer',
                'exists:raw_materials,id',
                'distinct',
            ],

            'items.*.qty' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.unit_id' => [
                'required',
                'integer',
                'exists:units,id',
            ],
        ];
    }


    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $items = $this->input('items', []);

            foreach ($items as $index => $item) {

                $rawMaterialId = $item['raw_material_id'] ?? null;
                $unitId = $item['unit_id'] ?? null;

                if (!$rawMaterialId || !$unitId) {
                    continue;
                }

                $rawMaterial = RawMaterial::with('unit')
                    ->find($rawMaterialId);

                $unit = Unit::find($unitId);

                if (!$rawMaterial || !$rawMaterial->unit || !$unit) {
                    continue;
                }

                if (
                    $rawMaterial->unit->unit_category_id !==
                    $unit->unit_category_id
                ) {
                    $validator->errors()->add(
                        "items.$index.unit_id",
                        'The selected unit must belong to the same category as the raw material unit.'
                    );
                }
            }
        });
    }


    public function messages(): array
    {
        return [
            'status.required' => 'The status is required.',
            'status.in' => 'The selected status is invalid.',

            'items.required' => 'At least one item is required.',
            'items.array' => 'The items must be valid.',
            'items.min' => 'At least one item is required.',

            'items.*.raw_material_id.required' => 'Please select a raw material.',
            'items.*.raw_material_id.exists' => 'The selected raw material is invalid.',
            'items.*.raw_material_id.distinct' =>
                'The selected raw material has already been added. Please choose a different raw material.',

            'items.*.qty.required' => 'The quantity is required.',
            'items.*.qty.numeric' => 'The quantity must be a number.',
            'items.*.qty.gt' => 'The quantity must be greater than 0.',

            'items.*.unit_id.required' => 'Please select a unit.',
            'items.*.unit_id.exists' => 'The selected unit is invalid.',
        ];
    }
}