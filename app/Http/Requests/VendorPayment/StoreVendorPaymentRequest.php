<?php

namespace App\Http\Requests\VendorPayment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreVendorPaymentRequest extends FormRequest
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
          
            'transaction_id'=>['nullable','string','max:50'],
            'amount'=>['required','numeric','gt:0'],
            'payment_method'=>['required','string','max:255'],
            'payment_date'=>['nullable','date'],
            'status'=>['nullable','in:successful,failed,refunded'],
            'references'=>['nullable','string','max:255'],
            'notes'=>['nullable','string','max:255'],
        ];
    }


}
