<?php

namespace App\Http\Requests\Role;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
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
            Rule::unique('role','role')->ignore($this->route('role')->id)
        ],
            'label'=>['nullable','string','max:255'],
            'description'=>['nullable','string','max:255'],
        ];
    }
}
