<?php

namespace App\Http\Controllers\api;

use App\Models\Order;
use App\Helpers\Helper;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AramexController extends Controller
{
    public function webhook(Request $request)
    {

        if ($request->has('Key')) {
            if ($request->has('Value')) {
                if ($request->Value['UpdateCode'] == 'SH005') {
                    $shipping = Shipping::where('shipping_id', $request->Key)->first();
                    $order_id = $shipping->order_id ?? 0;
                    $order = Order::where('id', $order_id)->first();
                    $order->order_status = 'completed';
                    $order->save();
                    if($order->store_id== null){
                        Helper::orderProductShow($order->id);
                    }
                }
            }
        }
    }
}
