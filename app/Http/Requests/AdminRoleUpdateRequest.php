<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdminRoleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $role=$this->route('role');
        return [
            'role_name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->where(function ($query) use ($role) {
                return $query->where('store_id', null)->where('id', '!=', $role->id);
            })],
            'permissions' => 'required|array',
            'permissions.*' => 'nullable|exists:permissions,id',

        ];
    }
}
