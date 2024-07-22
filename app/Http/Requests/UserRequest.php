<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'user_name' => ['required', 'string', 'max:255', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['admin_employee', 'admin'])
                    ->where('is_deleted', 0);
            }),
            ],
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['admin_employee', 'admin'])
                    ->where('is_deleted', 0);
            }),
            ],
            'password' => 'required|min:8|string',
            'password_confirm' => 'required|same:password',

            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['admin_employee', 'admin'])
                    ->where('is_deleted', 0);
            })],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],

            'role' => 'required|string|max:255|exists:roles,name',
        ];
    }
}
