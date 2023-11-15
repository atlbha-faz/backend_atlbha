<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function cheackOut(Request $request, $domain)
    {
        // انشاء الorder
        $store_domain = Store::where('is_deleted', 0)->where('domain', $domain)->pluck('id')->first();
        if ($store_domain == null) {
            $success['status'] = 200;

            return $this->sendResponse($success, ' المتجر غير موجود', 'store is not exist');

        } else {
            $cart = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_domain)->first();

            $input = $request->all();
            $validator = Validator::make($input, [
                'city' => 'required|string|max:255',
                'streetaddress' => 'required|string',
                'district' => 'nullable|string',
                'postal_code' => 'nullable|string',
                'paymentype_id' => 'nullable',
                'shippingtype_id' => 'required',
                'cod' => 'nullable',
                'description' => 'required|string',

            ]);
            if ($validator->fails()) {
                return $this->sendError(null, $validator->errors());
            }

// Create a new order
            $order = new Order();
            $order_number = Order::orderBy('id', 'desc')->first();

            if (is_null($order_number)) {
                $number = 0001;
            } else {

                $number = $order_number->order_number;
                $number = ((int) $number) + 1;
            }
            $order->order_number = str_pad($number, 4, '0', STR_PAD_LEFT);
            $order->user_id = $cart->user->id; // Assign the customer's ID
            $order->total_price = $cart->total; // Initialize the total price
            $order->quantity = $cart->count;
            $order->tax = 15;
            $order->shipping_price = $cart->shipping_price;
            // $order->discount = $cart->;
            $order->order_status = "new";
            $order->payment_status = "pending";
            $order->store_id = $store_domain;
            $order->shippingtype_id = $request->shippingtype_id;
            $order->paymentype_id = $request->paymentype_id;
            $order->cod = $request->cod;
            $order->description = $request->description;

            // Save the order to the database
            $order->save();

            // Loop through the cart items and associate them with the order
            foreach ($cart->cartDetails as $cartItem) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id; // Associate the order with the order item
                $orderItem->product_id = $cartItem->product_id;
                $orderItem->quantity = $cartItem->qty;
                $orderItem->price = $cartItem->price;
                $orderItem->store_id = $store_domain;
                $orderItem->user_id = auth()->user()->id;

                $orderItem->save();

            }

            $orderaddress = OrderAddress::create([
                'city' => $request->city,
                'streeta_ddress' => $request->street_address,
                'district' => $request->district,
                'postal_code' => $request->postal_code,
                'order_id' => $order->id,
                'user_id' => auth()->user()->id,

            ]);
            if ($order->cod == 1) {
                $cart->delete();
            }
            $success['orderaddress'] = new OrderResource($order);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم إضافة تعليق بنجاح', 'comment Added successfully');

        }
    }

}
