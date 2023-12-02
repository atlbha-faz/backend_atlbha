<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PaymenttypeResource;
use App\Http\Resources\ShippingtypeTemplateResource;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Coupon;
use App\Models\coupons_products;
use App\Models\coupons_users;
use App\Models\Importproduct;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\shippingtype_store;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            if ($cart == null) {
                $success['status'] = 200;

                return $this->sendResponse($success, ' سلة فارغة', 'cart is not exist');

            }
            $input = $request->all();
            $validator = Validator::make($input, [
                'city' => 'required|string|max:255',
                'street_address' => 'required|string',
                'district' => 'required|string',
                'postal_code' => 'nullable|string',
                'default_address' => 'required',
                'paymentype_id' => 'required|exists:paymenttypes,id',
                'shippingtype_id' => 'required|exists:shippingtypes,id',
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
            $order->weight = $cart->weight;
            $order->totalCount = $cart->totalCount;
            $order->subtotal = $cart->subtotal;
            $order->tax = $cart->tax;
            $order->discount = $cart->discount_total;
            $order->order_status = "new";
            $order->payment_status = "pending";
            $order->store_id = $store_domain;
            $order->shippingtype_id = $request->shippingtype_id;
            $order->paymentype_id = $request->paymentype_id;
            $order->cod = $request->cod;
            $order->description = $request->description;

            // Save the order to the database
            $order->save();

            $shipping_price = shippingtype_store::where('shippingtype_id', $order->shippingtype_id)->where('store_id', $store_domain)->first();
            if ($shipping_price == null) {
                $shipping_price = 35;
            } else {
                $shipping_price = $shipping_price->price;
            }
            if ($order->weight > 15) {
                $extra_shipping_price = ($order->weight - 15) * 3;
            } else {
                $extra_shipping_price = 0;
            }

            $order->update([
                'shipping_price' => $shipping_price,
            ]);

            // Loop through the cart items and associate them with the order
            foreach ($cart->cartDetails as $cartItem) {
                $product = Product::where('is_deleted', 0)->where('id', $cartItem->product_id)->where('store_id', $store_domain)->first();
                // $product=Product::where('is_deleted',0)->where('id', $cartItem->product_id)->first();
                if ($product != null) {
                    $product->update([
                        'stock' => $product->stock - $cartItem->qty,
                    ]);
                    //   $product-> quantity=  $product-> quantity-$cartItem->qty;
                } else {
                    $importProduct = Importproduct::where('product_id', $cartItem->product_id)->where('store_id', $store_domain)->first();
                    if ($importProduct != null) {
                        $importProduct->update([
                            'qty' => $importProduct->qty - $cartItem->qty,
                        ]);
                    }
                }
            }
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
            $subtotal = OrderItem::where('order_id', $order->id)->get()->reduce(function ($total, $item) {
                return $total + ($item->quantity * $item->price);
            });

            $order->update([
                'total_price' => $subtotal + $order->shipping_price + $extra_shipping_price,
            ]);
            $orderAddress = OrderAddress::where('user_id', auth()->user()->id)->where('id', $request->shippingAddress_id)->first();

            if ($orderAddress === null) {

                $orderaddress = OrderAddress::create([
                    'city' => $request->city,
                    'street_address' => $request->street_address,
                    'district' => $request->district,
                    'postal_code' => $request->postal_code,
                    'default_address' => $request->default_address,
                    'user_id' => auth()->user()->id,
                    'shippingtype_id' => $request->shippingtype_id,

                ]);
                if ($orderaddress->default_address === '1') {

                    $addresses = OrderAddress::where('user_id', auth()->user()->id)->whereNot('id', $orderaddress->id)->get();
                    foreach ($addresses as $address) {
                        $address->update([
                            'default_address' => 0,
                        ]);
                    }
                }

                $order->order_addresses()->attach($orderaddress->id, ["type" => "shipping"]);
            } else {
                $orderAddress->update([
                    'city' => $request->city,
                    'street_address' => $request->street_address,
                    'district' => $request->district,
                    'postal_code' => $request->postal_code,
                    'shippingtype_id' => $request->shippingtype_id,
                ]);
                $order->order_addresses()->attach($orderAddress->id, ["type" => "shipping"]);
                if ($orderAddress->default_address === '1') {

                    $addresses = OrderAddress::where('user_id', auth()->user()->id)->whereNot('id', $orderAddress->id)->get();
                    foreach ($addresses as $address) {
                        $address->update([
                            'default_address' => 0,
                        ]);
                    }
                }

            }

            if ($order->paymentype_id == 4) {

//الدفع عند الاستلام
                $order->update([
                    'total_price' => $order->total_price + 10,
                    'payment_status' => "pending",
                    'order_status' => "new",
                    'cod' => 1,
                ]);

                $cart->delete();
            } else {
                if ($order->paymentype_id == 1) {
                    // $customer_details=array(
                    //     "name"=>$order->user->user_name,
                    //     "email"=>$order->user->email,
                    //     "phone"=>$order->user->phonenumber,

                    //     "city"=>,
                    //     "state"=>,
                    //     "country"=>,
                    //     "zip"=>
                    // );
                    $data = array(
                        'profile_id' => '104143',
                        'tran_type' => 'sale',
                        'tran_class' => 'ecom',
                        'cart_id' => $order->id,
                        'cart_currency' => 'SAR',
                        'cart_amounts' => $order->total_price,
                        'cart_description' => 'Description of the item',
                        'paypage_lang' => 'ar',

                    );
                }
                $order->update([
                    'payment_status' => "pending",
                    'order_status' => "new",
                    'cod' => 0,
                ]);

                $cart->delete();
            }

            $success['order'] = new OrderResource($order);

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم ارسال الطلب بنجاح', 'order send successfully');

        }
    }
    public function paymentmethods($domain)
    {
        $store = Store::where('is_deleted', 0)->where('domain', $domain)->first();
        $success['payment_types'] = PaymenttypeResource::collection($store->paymenttypes);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع طرق الدفع بنجاح', 'Payment Types return successfully');
    }

    public function shippingcompany($domain)
    {

        $store = Store::where('is_deleted', 0)->where('domain', $domain)->first();

        $shippingcompanys = DB::table('shippingtypes_stores')
            ->join('shippingtypes', 'shippingtypes.id', '=', 'shippingtypes_stores.shippingtype_id')->where('shippingtypes_stores.store_id', $store->id) // joining the contacts table , where user_id and contact_user_id are same
            ->select('shippingtypes.*', 'shippingtypes_stores.price')
            ->get();

        $success['shipping_company'] = ShippingtypeTemplateResource::collection($shippingcompanys);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع طرق الشحن بنجاح', 'shipping Types return successfully');
    }

    public function applyCoupon(Request $request, $domain, $cart_id)
    {
        $store_domain = Store::where('is_deleted', 0)->where('domain', $domain)->pluck('id')->first();
        if ($store_domain == null) {
            $success['status'] = 200;

            return $this->sendResponse($success, ' المتجر غير موجود', 'store is not exist');

        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'code' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $coupon = Coupon::where('code', $request->code)->where('is_deleted', 0)->first();

        if ($coupon != null && $coupon->status == 'active') {

            $cart = Cart::where('id', $cart_id)->where('store_id', $store_domain)->first();
            $total = $cart->total - $cart->shipping_price;
            if ($total > $coupon->total_price) {
                $coupon->users()->attach(auth()->user()->id);
                $user = User::where('id', auth()->user()->id)->first();

                $useCouponUser = coupons_users::where('user_id', auth()->user()->id)->where('coupon_id', $coupon->id)->get();
                $useCouponAll = coupons_users::where('coupon_id', $coupon->id)->get();
                if ($coupon->user_redemptions >= count($useCouponUser) && $coupon->total_redemptions >= count($useCouponAll)) {
                    if ($coupon->coupon_apply == 'all') {
                        if ($coupon->discount_type == 'fixed') {
                            $cartAfterdiscount = $cart->total - $coupon->discount;
                            $cart->update([
                                'total' => $cartAfterdiscount,
                                'discount_type' => 'fixed',
                                'discount_value' => $coupon->discount,
                                'discount_total' => ($cart->discount_total !== null ? $cart->discount_total : 0) + $coupon->discount,

                            ]);

                        } else {
                            $couponDiscountPercent = $coupon->discount / 100;
                            $discountAmount = $cart->total * $couponDiscountPercent;
                            $cartAfterdiscount = $cart->total - $discountAmount;
                            $cartCopun = $cart->total - $cartAfterdiscount;
                            $cart->update([
                                'total' => $cartAfterdiscount,
                                'discount_type' => 'percent',
                                'discount_value' => $coupon->discount . '%',
                                'discount_total' => ($cart->discount_total !== null ? $cart->discount_total : 0) + round($cartCopun, 2),
                            ]);

                        }
                        if ($coupon->free_shipping == 1) {
                            $cart->update([
                                'shipping_price' => 0,
                                'total' => $cart->total - $cart->shipping_price,
                            ]);

                        }

                    } else {
                        if ($coupon->coupon_apply == 'selected_product') {
                            $product_ids = array();
                            foreach ($cart->cartDetails as $cartDetail) {
                                $product_ids[] = $cartDetail->product_id;
                            }

                            $couponProducts = coupons_products::where('coupon_id', $coupon->id)->whereIn('product_id', $product_ids)->get();
                            if ($couponProducts != null) {
                                $products = array();
                                foreach ($couponProducts as $couponProduct) {
                                    $products[] = $couponProduct->product_id;
                                }

                                if ($coupon->discount_type == 'fixed') {
                                    $cartAfterdiscount = $cart->total - $coupon->discount;

                                    $cart->update([
                                        'total' => $cartAfterdiscount,
                                        'discount_type' => 'fixed',
                                        'discount_value' => $coupon->discount,
                                        'discount_total' => ($cart->discount_total !== null ? $cart->discount_total : 0) + $coupon->discount,
                                    ]);

                                } else {
                                    $sum = 0;
                                    $cartDetails = CartDetail::where('cart_id', $cart->id)->whereIn('product_id', $products)->get();
                                    foreach ($cartDetails as $cartDetail) {
                                        $sum = $sum + ($cartDetail->price * $cartDetail->qty);
                                    }
                                    $couponDiscountPercent = $coupon->discount / 100;
                                    $discountAmount = $sum * $couponDiscountPercent;
                                    $cartAfterdiscount = $cart->total - $discountAmount;
                                    $cartCopun = $cart->total - $cartAfterdiscount;
                                    $cart->update([
                                        'total' => $cartAfterdiscount,
                                        'discount_type' => 'percent',
                                        'discount_value' => $coupon->discount . '%',
                                        'discount_total' => ($cart->discount_total !== null ? $cart->discount_total : 0) + round($cartCopun, 2),
                                    ]);

                                }
                            }

                        }
                    }
                } else {
                    $success['status'] = 200;

                    return $this->sendResponse($success, 'الكوبون غير صالح', 'The coupon is invalid');

                }
            } else {
                $success['status'] = 200;

                return $this->sendResponse($success, 'الكوبون غير صالح', 'The coupon is invalid');

            }
            $success['cart'] = new CartResource($cart);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تفعيل الكوبون بنجاح', 'coupon updated successfully');

        } else {
            $success['status'] = 200;

            return $this->sendResponse($success, 'الكوبون غير صالح', 'The coupon is invalid');

        }

    }
    public function ordersUser(Request $request, $domain)
    {
        $store_domain = Store::where('is_deleted', 0)->where('domain', $domain)->pluck('id')->first();
        if ($store_domain == null) {
            $success['status'] = 200;

            return $this->sendResponse($success, ' المتجر غير موجود', 'store is not exist');

        } else {
            $orders = Order::where('user_id', auth()->user()->id)->where('store_id', $store_domain)->get();
            $success['order'] = OrderResource::collection($orders);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم إرجاع الطلبات بنجاح', 'orders get successfully');

        }
    }
    public function orderUser(Request $request, $domain, $order_id)
    {
        $store_domain = Store::where('is_deleted', 0)->where('domain', $domain)->pluck('id')->first();
        if ($store_domain == null) {
            $success['status'] = 200;

            return $this->sendResponse($success, ' المتجر غير موجود', 'store is not exist');

        } else {
            $order = Order::where('user_id', auth()->user()->id)->where('store_id', $store_domain)->where('id', $order_id)->first();
            $success['order'] = new OrderResource($order);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم إرجاع الطلب بنجاح', 'order get successfully');

        }
    }

}
