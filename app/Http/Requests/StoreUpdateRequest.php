<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
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
        $store=$this->route('store'); 
        $store = Store::query()->find($store);
        return [
            'name' => 'required|string|max:255',
            'user_name' => ['required', 'string', Rule::unique('users')->where(function ($query) use ($store) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0)
                    ->where('id', '!=', $store->user->id);
            })],
            'store_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) use ($store) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0)
                    ->where('id', '!=', $store->user->id);
            })],
            'store_email' => 'required|email|unique:stores,store_email,' . $store->id,
            'password' => 'required|min:8|string',
            'domain' => ['required', 'string', Rule::unique('stores')->where(function ($query) use ($store) {
                return $query->where('is_deleted', 0)->where('id', '!=', $store->id);
            })],
            'userphonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users', 'phonenumber')->where(function ($query) use ($store) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0)
                    ->where('id', '!=', $store->user->id);
            })],
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', 'unique:stores,phonenumber,' . $store->id],
            // 'package_id' =>'required',
            'activity_id' => 'required|array',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'user_country_id' => 'required|exists:countries,id',
            'user_city_id' => 'required|exists:cities,id',
            'periodtype' => 'required|in:6months,year',
        ];
    }
}
