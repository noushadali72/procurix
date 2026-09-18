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
            'payment_proof'=>['nullable','image','mimes:png,jpg,jpeg','max:4096'],
            'status'=>['nullable','in:successful,failed,refunded'],
            'references'=>['nullable','string','max:255'],
            'notes'=>['nullable','string','max:255'],
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'payment_proof.image'=>'Payment proof must be valid image.',
            'payment_proof.mimes'=>'Payment proof must be either png,jpeg or jpg.',
            'payment_proof.max'=>'Payment proof image size less than 4MB.'
        ];  
    }


}
