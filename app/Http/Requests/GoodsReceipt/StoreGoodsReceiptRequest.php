<?php

namespace App\Http\Requests\GoodsReceipt;

use App\Models\PurchaseOrderItem;
use App\Models\RawMaterial;
use App\Models\Unit;
use Illuminate\Foundation\Http\FormRequest;

class StoreGoodsReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'received_date' => [
                'required',
                'date',
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

            'items.*.purchase_order_item_id' => [
                'required',
                'exists:purchase_order_items,id',
                'distinct',
            ],

            'items.*.qty' => [
                'required',
                'numeric',
                'min:0.0001',
            ],

            'items.*.unit_id' => [
                'required',
                'exists:units,id',
            ],

            'attachments' => [
                'nullable',
                'array',
            ],

            'attachments.*' => [
                'file',
                'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' =>
                'At least one item is required.',

            'items.*.purchase_order_item_id.distinct' =>
                'The same purchase order item cannot be added more than once.',

            'items.*.qty.min' =>
                'The received quantity must be greater than 0.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $purchaseOrder = $this->route('purchaseOrder');

            if (!$purchaseOrder) {
                return;
            }

            foreach ($this->items ?? [] as $index => $item) {

                if (
                    empty($item['purchase_order_item_id']) ||
                    empty($item['unit_id'])
                ) {
                    continue;
                }

                $orderItem = PurchaseOrderItem::with([
                    'rawMaterial.unit',
                ])->find($item['purchase_order_item_id']);

                $unit = Unit::find($item['unit_id']);

                if (!$orderItem || !$unit) {
                    continue;
                }

                
                // Make sure the item belongs to this purchase order
    

                if ($orderItem->purchase_order_id != $purchaseOrder->id) {

                    $validator->errors()->add(
                        "items.$index.purchase_order_item_id",
                        'The selected item does not belong to this purchase order.'
                    );

                    continue;
                }

                //Make sure the receiving unit has the same category
               

                if (
                    $orderItem->rawMaterial &&
                    $orderItem->rawMaterial->unit &&
                    $orderItem->rawMaterial->unit->unit_category_id
                        !== $unit->unit_category_id
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