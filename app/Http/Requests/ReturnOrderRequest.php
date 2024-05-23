<?php

namespace App\Http\Requests;

use App\Helpers\Helper;
use App\Rules\returnDatePassed;
use Illuminate\Validation\Rule;
use App\Rules\BelongsToOrderRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


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
            
            'order_id' => ['required','numeric',new returnDatePassed()],
            'return_reason_id' => 'required|numeric',
            'comment' => ['string','required_if:return_reason_id,==,5'],
            'store_id' => 'required',
            'data.*.order_item_id' => ['required','numeric',new BelongsToOrderRule($this->order_id),'unique:return_orders,order_item_id'],
            'data.*.qty' => 'required|numeric',
        ];
    }

}
