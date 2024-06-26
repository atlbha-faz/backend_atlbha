<?php

namespace App\Rules;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Contracts\Validation\Rule;

class returnDatePassed implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
  
    public function __construct()
    {
     
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
      
        $order = Order::where('id', $value)->where('user_id', auth()->user()->id)->where('is_deleted', 0)->first();
        if($order){
        return Carbon::now()->lt($order->created_at->addDays(14));
        }
       return false;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "طلب الاسترجاع غير ممكن لانه تعدى المده المسموحه في الاسترجاع";
    }
}
