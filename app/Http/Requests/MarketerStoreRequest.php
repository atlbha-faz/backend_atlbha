<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MarketerStoreRequest extends FormRequest
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
           'checkbox_field' => 'required|in:1',
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['marketer'])->where('is_deleted', 0);
            })],

            'password' => 'nullable',
            'password_confirm' => 'nullable',
            'user_name' => ' nullable',
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['marketer'])->where('is_deleted', 0);
            }),
            ],
            'snapchat' => 'required|url',
            'facebook' => 'required|url',
            'twiter' => 'required|url',
            'whatsapp' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'youtube' => 'required|url',
            'instegram' => 'required|url',
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'status' => 'required|in:active,not_active',
        ];
    }
}
