<?php

namespace App\Http\Requests\VendorBill;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreVendorBillRequest extends FormRequest
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
            'purchase_order_id'=>[
                'required',
                'exists:purchase_orders,id'
            ],
            'vendor_id'=>['required','exists:vendors,id'],
            'bill_date'=>['nullable','date'],
            'due_date'=>['nullable','date'],
            'tax'=>['nullable','numeric','gt:0'],
            'status'=>[
                'nullable',
                'in:paid,partially_paid,unpaid,pending'
            ],
            'notes'=>['nullable','string','max:255']
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'purchase_order_id.required'=>'You must select purchase order first.',
            'purchase_order_id.exists'=>'Purchase order is invalid.',

            'vendor_id.required'=> 'Vendor is required.',
            'vendor_id.exists'=>'The selected vendors is invalid.',

            'bill_date.date'=>'Selected bill date is invalid.',
            'due_date.date'=>'Selected due date is invalid.',

            'tax.numeric'=>'Tax must be valid number.',
            'tax.gt'=>'Tax must be greater than 0.',

            'status.in'=>'Status must be either: Paid, Unpaid, Partially Paid, or pending.',
            'notes.string'=>'Notes must be valid text.',
            'notes.max'=>'Notes must be less than 255 chars.'
        ];
    }
}
