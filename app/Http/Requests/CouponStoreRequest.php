<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CouponStoreRequest extends FormRequest
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
            'code' => ['required', 'regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9]+$/', 'max:255',
                Rule::unique('coupons')->where(function ($query) {
                    return $query->where('store_id', null);
                })->where('is_deleted', 0)],
            'discount_type' => 'required|in:fixed,percent',
            'discount' => ['required', 'numeric', 'gt:0'],
            'start_at' => ['required', 'date'],
            'expire_date' => ['required', 'date'],
            'total_redemptions' => ['required', 'numeric'],
            'user_redemptions' => ['nullable', 'numeric'],
        ];
    }
}
