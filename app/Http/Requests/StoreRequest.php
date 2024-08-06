<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0);
            })],

            'store_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0);
            })],
            'store_email' => ['required', 'email', Rule::unique('stores')->where(function ($query) {
                return $query->where('is_deleted', 0);
            })],
            'password' => 'required|min:8|string',
            'domain' => ['required', 'string', Rule::unique('stores')->where(function ($query) {
                return $query->where('is_deleted', 0);
            })],
            'userphonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users', 'phonenumber')->where(function ($query) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0);
            })],

            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('stores')->where(function ($query) {
                return $query->where('is_deleted', 0);
            })],
            'activity_id' => 'required|array',
            'subcategory_id' => ['nullable', 'array'],
            'package_id' =>'required',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'user_country_id' => 'required|exists:countries,id',
            'user_city_id' => 'required|exists:cities,id',
            //'periodtype' => 'nullable|required_unless:package_id,1|in:6months,year',
            'periodtype' => 'required|in:6months,year',
            'status' => 'required|in:active,inactive',
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
        ];
    }
}
