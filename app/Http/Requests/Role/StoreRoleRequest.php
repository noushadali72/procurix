<?php

namespace App\Http\Requests\Role;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'role'=>[
                'required',
                'string',
                'max:255',
                'unique:roles,role',
            ],
            'label'=>['nullable','string','max:255'],
            'description'=>['nullable','string','max:255'],
            // 'permissions'=>['required','array','min:1'],
            // 'permissions.*.'=>['']
        ];
    }
}
