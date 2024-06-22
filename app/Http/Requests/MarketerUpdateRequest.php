<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MarketerUpdateRequest extends FormRequest
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
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) use ($marketer) {
                return $query->whereIn('user_type', ['marketer'])->where('is_deleted', 0)->where('id', '!=', $marketer->user->id);
            }),
            ],

            'password' => 'nullable',
            'password_confirm' => 'nullable',
            'user_name' => ' nullable',
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) use ($marketer) {
                return $query->whereIn('user_type', ['marketer'])->where('is_deleted', 0)->where('id', '!=', $marketer->user->id);
            }),
            ],
            'snapchat' => 'required',
            'facebook' => 'required',
            'twiter' => 'required',
            'whatsapp' => 'required',
            'youtube' => 'required',
            'instegram' => 'required',
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'status' => 'required|in:active,not_active',
        ];
    }
}
