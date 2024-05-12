<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Http\Request;

class AramexController extends Controller
{
    public function webhook(Request $request)
    {
        if ($request->has('key')) {
            if ($request->has('value')) {
                if ($request->value->UpdateCode == 'SH005') {
                    $shipping = Shipping::where('shipping_id', $request->key)->first();
                    $order_id = $shipping->order_id ?? 0;
                    $order = Order::where('id', $order_id)->first();
                    $order->order_status = 'completed';
                    $order->save();
                }
            }
        }
    }
}
