<?php

namespace App\Http\Requests;

use App\Helpers\Helper;
use App\Rules\returnDatePassed;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
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
            'data.*.order_item.*' => ['required','numeric',Rule::unique('return_orders')->where(function ($query) {
                return $query->where('order_id', $this->order_id);
            }),],
            'data.*.price.*' => 'required|numeric',
            'data.*.qty.*' => 'required|numeric',
        ];
    }
    // protected function failedValidation(Validator $validator){
    //     return Helper::sendError(null, $validator->errors());
    // }
}
