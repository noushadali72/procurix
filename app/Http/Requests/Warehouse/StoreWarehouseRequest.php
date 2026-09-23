<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
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
            'name'=>[
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'description'=>[
                'nullable',
                'string',
                'min:5',
                'max:255',
            ],
            'is_active'=>[
                'nullable',
                'boolean',

                
            ],
            'address'=>[
                'nullable',
                'string',
                'max:255',
                'min:2'
            ],
            'city'=>[
                'nullable',
                'string',
                'min:2',
                'max:255'
            ],
            'postal_code'=>[
                'nullable',
                'string',
                'min:2',
                'max:255'
            ],
            'country'=>[
                'nullable',
                'string',
                'min:2',
                'max:255'
            ],
            'phone_no'=>[
                'nullable',
                'string',
                'min:8',
                'max:20'
            ],
            'capacity'=>[
                'nullable',
                'string',
                'min:1',
                'max:255'
            ]
        ];
    }
}
