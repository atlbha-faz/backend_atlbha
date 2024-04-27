<?php

namespace App\Http\Requests;

use App\Rules\returnDatePassed;
use Illuminate\Foundation\Http\FormRequest;

class ReturnOrderRequest extends FormRequest
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
            'comment' => 'string|max:255',
            'order_id' => ['required','numeric',new returnDatePassed()],
            'return_reason_id' => 'required|numeric',
            'store_id' => 'required',
            'data.*.order_item.*' => 'required|numeric',
            'data.*.price.*' => 'required|numeric',
            'data.*.qty.*' => 'required|numeric',
        ];
    }
}
