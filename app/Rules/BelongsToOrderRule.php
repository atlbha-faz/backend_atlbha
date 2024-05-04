<?php

namespace App\Rules;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\Validation\Rule;

class BelongsToOrderRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $orderId;
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
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
        $order = Order::find($this->orderId);

        if ($order) {
            $item = OrderItem::find($value);

            return $item && $item->order_id === $order->id;
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
        return 'هذا العنصر لاينتمي الى الطلب.';
    }
}
