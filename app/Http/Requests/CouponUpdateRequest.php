<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponUpdateRequest extends FormRequest
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
            'discount_type' => 'required|in:fixed,percent',
            'discount' => ['required', 'numeric', 'gt:0'],
            'expire_date' => ['required', 'date'],
            'start_at' => ['required', 'date'],
            'total_redemptions' => ['required', 'numeric'],
            'user_redemptions' => ['nullable', 'numeric']
        ];
    }
}
