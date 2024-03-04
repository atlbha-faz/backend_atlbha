<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\Coupon;
use App\Models\Option;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\CartDetail;
use App\Models\Paymenttype;
use App\Models\OrderAddress;
use Illuminate\Http\Request;
use App\Models\coupons_users;
use App\Models\Importproduct;
use App\Models\coupons_products;
use App\Models\shippingtype_store;
use App\Services\FatoorahServices;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PaymenttypeResource;
use App\Http\Resources\ShippingtypeTemplateResource;
use App\Http\Controllers\api\BaseController as BaseController;

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

            ], [
                'paymentype_id.required' => 'اختر طرق الدفع اولاً',
                'shippingtype_id.required' => 'اختر طرق التوصيل اولاً']);
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

            if ($cart->free_shipping == 1) {
                $shipping_price = $cart->shipping_price;
            } else {
                $shipping_price = shippingtype_store::where('shippingtype_id', $order->shippingtype_id)->where('store_id', $store_domain)->first();
                if ($shipping_price == null) {
                    $shipping_price = 35;
                } else {
                    $shipping_price = $shipping_price->price;
                }
            }
            // if ($order->weight > 15) {
            //     $extra_shipping_price = ($order->weight - 15) * 3;
            // } else {
            //     $extra_shipping_price = 0;
            // }
            if ($cart->free_shipping == 1) {
                $order->update([
                    'shipping_price' => $shipping_price,
                    'total_price' => $order->total_price,
                ]);
            } else {
                $order->update([
                    'shipping_price' => $shipping_price,
                    'total_price' => ($order->total_price - 35) + $shipping_price,
                ]);

            }

            // Loop through the cart items and associate them with the order
            foreach ($cart->cartDetails as $cartItem) {
                $product = Product::where('is_deleted', 0)->where('id', $cartItem->product_id)->where('store_id', $store_domain)->first();
                // $product=Product::where('is_deleted',0)->where('id', $cartItem->product_id)->first();
                if ($product != null) {
                    $product->update([
                        'stock' => $product->stock - $cartItem->qty,
                    ]);
                    if ($cartItem->option_id != null) {
                        $optionQty = Option::where('id', $cartItem->option_id)->first();
                        $optionQty->update([
                            'quantity' => $optionQty->quantity - $cartItem->qty,
                        ]);
                    }
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
                $orderItem->option_id = $cartItem->option_id;
                $orderItem->store_id = $store_domain;
                $orderItem->user_id = auth()->user()->id;
                $orderItem->save();
            }
            $subtotal = OrderItem::where('order_id', $order->id)->get()->reduce(function ($total, $item) {
                return $total + ($item->quantity * $item->price);
            });

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
                $payment = Payment::create([
                    'paymenDate' => Carbon::now(),
                    'paymentType' => $order->paymentype->name,
                    'orderID' => $order->id,
                ]);
            } else {
                $InvoiceId = null;
                if ($order->paymentype_id == 1 && $order->shippingtype_id == 5) {
                    
                    // $payment = Payment::create([
                    //     'paymenDate' => Carbon::now(),
                    //     'paymentType' => $order->paymentype->name,
                    //     'orderID' => $order->id,
                    //     'store_id'=>$store_domain
                    // ]);
                    // $cart->delete();
                    // $account = Account::where('store_id',$store_domain)->first();
                    // $customer = User::where('id', $order->user_id)->where('is_deleted', 0)->first();
                    // $paymenttype = Paymenttype::where('id', $order->paymentype_id)->first();
                    // $deduction = $order->total_pric * 0.01 + 1;
                    // $price_after_deduction = $order->total_price - $deduction;
                    // $payment->update([
                    //     'deduction'=>$deduction,
                    //     'price_after_deduction'=> $price_after_deduction 
                    // ]);
                    // $supplierdata = [
                    //     "SupplierCode" => $account->supplierCode,
                    //     "ProposedShare" => $price_after_deduction,
                    //     "InvoiceShare" => $order->total_price,
                    // ];
                    // $supplierobject = (object) ($supplierdata);
                    // $data = [
                    //     "PaymentMethodId" => $paymenttype->paymentMethodId,
                    //     "CustomerName" => $customer->name,
                    //     "InvoiceValue" => $order->total_price, // total_price
                    //     "CustomerEmail" => $customer->email,
                    //     "CallBackUrl" => 'https://backend.atlbha.com/api/callback?order=' . $order->id,
                    //     "ErrorUrl" => 'https://template.atlbha.com/' . $domain . '/shop/products',
                    //     "Language" => 'ar',
                    //     "DisplayCurrencyIso" => 'SAR',
                    //     "Suppliers" => [
                    //         $supplierobject,
                    //     ],
                    // ];

                    // $supplier = new FatoorahServices();
                    // $response = $supplier->executePayment('v2/ExecutePayment', $data);

                    // if (isset($response['IsSuccess'])) {
                    //     if ($response['IsSuccess'] == true) {

                    //         $InvoiceId = $response['Data']['InvoiceId']; // save this id with your order table
                    //         $success['payment'] = $response;

                    //     }
                    // }
                    // else{
                    // $success['payment'] = $response;
                    // }
                    // $success['status'] = 200;

                    // return $this->sendResponse($success, 'تم ارسال الطلب بنجاح', 'order send successfully');

                }
                $payment = Payment::create([
                    'paymenDate' => Carbon::now(),
                    'paymentType' => $order->paymentype->name,
                    'orderID' => $order->id,
                ]);

                $cart->delete();
            }

            $success['order'] = new OrderResource($order);

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم ارسال الطلب بنجاح', 'order send successfully');

        }
    }
    public function callback(Request $request)
    {
        $order = order::where('id', $request->order)->first();
        $paymenttype = Paymenttype::where('id', $order->paymentype_id)->first();
        $payment = Payment::where('orderID', $order->id)->first();

        $apiKey = env("fatoora_token", "WVjz6cCoT9N7ZlqmZBMPANjkh30UvH9uwB20g-xGJi8tOjADM9NWiKaasfQCwkGLBy0ZNzoRXYCwMoLzaNwNgvY9H_3pO0bfTfwcEBCHe4s7FDQ0oGYFdMj8UwECAoiV4_3buuymrCmvdzc6QmZZuPsNfFCPg0vtwanErRHxM975FhaReP5QZsp6cU5bE8zupH5qOL7y8Hb9kSTW4u4ffx5V0WqUTrL2GmBWmAhx4eZBqoppO-jxG93E_FU7drhRA8SxiH__pNDDj3RJXBFqbLjzLjQ0iLtvR4s-c7X_dcLWXCj0X5lCBxVPFquo7Fsosbv5-NHJ-8nygVqy-HhwqnN3CV5HRD005E34zf32K8Y0eC526P2wZer5U-jr275rPEtfotn2wFFuDcWWnuT5f37p7oLDOgb2BclrmSj5crn5BxtkbG7awbxR7yVXoW-q19oE6-mcQWoNX8vdSbbdJ8arugLsR6qKW15juYZ8LztWfwnq65rRPZdh_JuE3KO96_rQR72a90FXy0mxjuXWmQU94Nek-2X_9DyecBqPxANjFwotZRNybG353CZchyvyJ60WjhVlfmxLMCdTD6wsBB5Ew5xKNru_jdG5TsshtNUgiogmf4FvJ0M8R3Xlxv98z5VXkqYytzBEtk2rbCwFopar9Ejj2Dwun5YaT5xyllcXrJltQ4UwofJ9j-bislP57_wQZCSachFOs2BaXqnRJEMb8sf6QeRm06TV-F4x7-iGJ-z7");
        $postFields = [
            'Key' => $request->paymentId,
            'KeyType' => 'paymentId',
        ];
        $supplier = new FatoorahServices();
        $response = $supplier->callAPI("https://apitest.myfatoorah.com/v2/getPaymentStatus", $apiKey, $postFields);
        $response = json_decode($response);
        if (!isset($response->Data->InvoiceId)) {
            return response()->json(["error" => 'error', 'status' => false], 404);
        }

        $InvoiceId = $response->Data->InvoiceId; // get your order by payment_id
        if ($response->IsSuccess == true) {
            if ($response->Data->InvoiceStatus == "Paid") //||$response->Data->InvoiceStatus=='Pending'
            {
                $order->update([
                    'payment_status' => "paid",
                ]);
                $payment->update([
                    'paymentTransectionID' => $InvoiceId,

                ]);
            }

        }
        $success['order'] = new OrderResource($order);

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الطلب بنجاح', 'order show successfully');
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
            ->select('shippingtypes.*', 'shippingtypes_stores.price', 'shippingtypes_stores.time')
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
            if ($total >= $coupon->total_price) {
                $coupon->users()->attach(auth()->user()->id);
                $user = User::where('id', auth()->user()->id)->first();

                $useCouponUser = coupons_users::where('user_id', auth()->user()->id)->where('coupon_id', $coupon->id)->get();
                $useCouponAll = coupons_users::where('coupon_id', $coupon->id)->get();
                if ($coupon->user_redemptions >= count($useCouponUser) && $coupon->total_redemptions >= count($useCouponAll)) {
                    if ($coupon->coupon_apply == 'all') {
                        if ($coupon->free_shipping == 1) {
                            $cart->update([
                                'free_shipping' => 1,
                                'shipping_price' => 0,
                                'total' => $cart->total - $cart->shipping_price,
                            ]);

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

                    return $this->sendResponse($success, 'الكود غير صالح', 'The coupon is invalid');

                }
            } else {
                $success['status'] = 200;

                return $this->sendResponse($success, 'المشتريات اقل من الحد الادنى لكود الخصم', 'The coupon is invalid');

            }
            $success['cart'] = new CartResource($cart);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تفعيل الكود بنجاح', 'coupon updated successfully');

        } else {
            $success['status'] = 200;

            return $this->sendResponse($success, 'كود الخصم غير صالح', 'The coupon is invalid');

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
