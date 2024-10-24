<?php

namespace App\Http\Controllers\api\storeTemplate;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PaymenttypeResource;
use App\Http\Resources\ShippingtypeTemplateResource;
use App\Mail\SendMail2;
use App\Models\Account;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Coupon;
use App\Models\coupons_products;
use App\Models\coupons_users;
use App\Models\Importproduct;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Paymenttype;
use App\Models\Product;
use App\Models\shippingtype_store;
use App\Models\Store;
use App\Models\User;
use App\Services\FatoorahServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
            $request->is_service = $cart->is_service==null ? 0 : $cart->is_service;
            $validator = Validator::make($input, [
                'city' => 'required_if:is_service,0|string|max:255',
                'street_address' => 'required_if:is_service,0|string',
                'district' => 'required_if:is_service,0|string',
                'postal_code' => 'nullable|string',
                'default_address' => 'required_if:is_service,0',
                'paymentype_id' => 'required|exists:paymenttypes,id',
                'shippingtype_id' => 'nullable|exists:shippingtypes,id',
                'cod' => 'nullable',
                'SessionId' => 'required_if:paymentype_id,1,2',
                'description' => 'nullable|string',

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
            $order->is_service = ($cart->is_service == null ? 0 : $cart->is_service);
            $order->overweight_price = $cart->overweight_price;
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
            $order->shipping_price = $cart->shipping_price;
            $order->description = $request->description;
            // Save the order to the database
            $order->save();

            $shipping_type_object = shippingtype_store::where('shippingtype_id', $order->shippingtype_id)->where('store_id', $store_domain)->first();
            if ($shipping_type_object == null) {
                $shipping_price = 0;
                $extraprice = 0;
                if ($order->weight > 15) {
                    $extra_shipping_price = 0;
                } else {
                    $extra_shipping_price = 0;
                }
            } else {
                $extraprice = $shipping_type_object->overprice;
                $shipping_price = $shipping_type_object->price;
                if ($order->weight > 15) {
                    $extra_shipping_price = ($order->weight - 15) * $extraprice;
                } else {
                    $extra_shipping_price = 0;
                }
            }

            if ($cart->free_shipping == 1) {
                $order->update([
                    'shipping_price' => $cart->shipping_price,
                    'overweight_price' => 0,
                ]);
            } else {
                $order->update([
                    'shipping_price' => $shipping_price,
                    'total_price' => (($order->total_price - $order->shipping_price) - $order->overweight_price) + $shipping_price + $extra_shipping_price,
                    'overweight_price' => $extra_shipping_price,
                ]);

            }
            if ($order->paymentype_id == 4 && $order->shippingtype_id == 1 && $order->total_price >= 3740) {
                $order->delete();
                return $this->sendError("لايمكن ارسال الطلب لانه المبلغ تجاوز الحد الاقصى في الدفع عند الاستلام لدى شركة ارامكس", "order cannot be sent due to the amount exceeding the maximum limit when paying at delivery");

            }

            // Loop through the cart items and associate them with the
            if ($cart->is_service == 0) {
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
                        if ($cartItem->option_id != null) {
                            if ($importProduct == null) {
                                $importProduct = null;
                            } else {
                                $importProduct = $importProduct->id;
                            }
                            $optionQty = Option::where('id', $cartItem->option_id)->where('importproduct_id', $importProduct)->first();
                            if ($optionQty != null) {
                                $optionQty->update([
                                    'quantity' => $optionQty->quantity - $cartItem->qty,
                                ]);
                            }
                        }
                    }
                }
            }
            foreach ($cart->cartDetails as $cartItem) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id; // Associate the order with the order item
                $orderItem->product_id = $cartItem->product_id;
                $orderItem->quantity = $cartItem->qty;
                $orderItem->price = $cartItem->price;
                $orderItem->total_price = ($cartItem->price * $cartItem->qty);
                $orderItem->option_id = $cartItem->option_id;
                $orderItem->store_id = $store_domain;
                $orderItem->user_id = auth()->user()->id;
                $orderItem->save();
            }
            $subtotal = OrderItem::where('order_id', $order->id)->get()->reduce(function ($total, $item) {
                return $total + ($item->quantity * $item->price);
            });

            if ($cart->is_service == 0) {
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
                    'deduction' => 0,
                    'price_after_deduction' => $order->total_price,
                ]);
                if ($order->store_id !== null) {
                    $store = Store::where('id', $order->store_id)->first();
                    $data = [
                        'subject' => "طلب جديد",
                        'message' => "تم وصول طلب جديد برقم " . $order->order_number . " لدى متجركم",
                        'store_id' => $store->store_name,
                        'store_email' => $store->store_email,
                    ];
                    Mail::to($store->store_email)->send(new SendMail2($data));
                }
            } else {
                $InvoiceId = null;
                if (in_array($order->paymentype_id, [1, 2]) && ($order->shippingtype_id == 5 || $order->shippingtype_id == null)) {
                    $account = Account::where('store_id', $store_domain)->first();
                    $customer = User::where('id', $order->user_id)->where('is_deleted', 0)->first();
                    $paymenttype = Paymenttype::where('id', $order->paymentype_id)->first();
                    $commission = (0.009 * $order->total_price) + 1;
                    $vat = $commission * 0.15;
                    $result = $order->total_price - ($order->shipping_price) - ($order->overweight_price) - $commission - $vat;
                    $atlbha = $result * 0.001;
                    $deduction = $commission + $vat + $atlbha;
                    $price_after_deduction = $order->total_price - $deduction;

                    $supplierdata = [
                        "SupplierCode" => $account->supplierCode,
                        "ProposedShare" => $price_after_deduction,
                        "InvoiceShare" => $order->total_price,
                    ];
                    $processingDetails = [
                        "AutoCapture" => true,
                        "Bypass3DS" => false,
                    ];
                    $processingDetailsobject = (object) ($processingDetails);
                    $supplierobject = (object) ($supplierdata);
                    $data = [
                        "SessionId" => $request->SessionId,
                        "CustomerName" => $customer->name,
                        "CustomerMobile" => substr($customer->phonenumber, 4),
                        "InvoiceValue" => $order->total_price, // total_price
                        "CustomerEmail" => $customer->email,
                        "CallBackUrl" => 'https://' . $domain . '/shop/checkout/success',
                        "ErrorUrl" => 'https://' . $domain . '/shop/checkout/failed',
                        "Language" => 'ar',
                        "DisplayCurrencyIso" => 'SAR',
                        "ProcessingDetails" => $processingDetailsobject,
                        "Suppliers" => [
                            $supplierobject,
                        ],
                    ];
                    $data = json_encode($data);
                    $supplier = new FatoorahServices();
                    $response = $supplier->buildRequest('v2/ExecutePayment', 'POST', $data);

                    if (isset($response['IsSuccess'])) {
                        if ($response['IsSuccess'] == true) {

                            $InvoiceId = $response['Data']['InvoiceId']; // save this id with your order table
                            $success['payment'] = $response;
                            $payment = Payment::create([
                                'paymenDate' => Carbon::now(),
                                'paymentType' => $order->paymentype->name,
                                'orderID' => $order->id,
                                'store_id' => $store_domain,
                                'deduction' => $deduction,
                                'price_after_deduction' => $price_after_deduction,
                                'paymentTransectionID' => $InvoiceId,

                            ]);

                        } else {
                            $success['payment'] = $response;
                        }
                    } else {
                        $success['payment'] = $response;
                    }
                    $cart->update(['order_id' => $order->id]);
                    $success['status'] = 200;

                    return $this->sendResponse($success, 'تم ارسال الطلب بنجاح', 'order send successfully');

                } elseif (in_array($order->paymentype_id, [1, 2]) && $order->shippingtype_id == 1) {

                    $account = Account::where('store_id', $store_domain)->first();
                    if (!$account) {
                        return $this->sendError("لا يوجد حساب بنكي", "account is not exsit");
                    }
                    $customer = User::where('id', $order->user_id)->where('is_deleted', 0)->first();
                    $paymenttype = Paymenttype::where('id', $order->paymentype_id)->first();

                    $commission = 0.009 * $order->total_price + 1;
                    $vat = $commission * 0.15;
                    $result = $order->total_price - ($order->shipping_price) - ($order->overweight_price) - $commission - $vat;
                    $atlbha = $result * 0.001;
                    $deduction = $commission + $vat + $atlbha;
                    $price_after_deduction = $order->total_price - ($order->shipping_price) - ($order->overweight_price) - $deduction;
                    $supplierdata = [
                        "SupplierCode" => $account->supplierCode,
                        "ProposedShare" => $price_after_deduction,
                        "InvoiceShare" => $order->total_price,
                    ];
                    $processingDetails = [
                        "AutoCapture" => true,
                        "Bypass3DS" => false,
                    ];
                    $processingDetailsobject = (object) ($processingDetails);
                    $supplierobject = (object) ($supplierdata);
                    $data = [
                        "SessionId" => $request->SessionId,
                        "CustomerName" => $customer->name,
                        "CustomerMobile" => substr($customer->phonenumber, 4),
                        "InvoiceValue" => $order->total_price, // total_price
                        "CustomerEmail" => $customer->email,
                        "CallBackUrl" => 'https://' . $domain . '/shop/checkout/success',
                        "ErrorUrl" => 'https://' . $domain . '/shop/checkout/failed',
                        "Language" => 'ar',
                        "DisplayCurrencyIso" => 'SAR',
                        "ProcessingDetails" => $processingDetailsobject,
                        "Suppliers" => [
                            $supplierobject,
                        ],
                    ];
                    $data = json_encode($data);
                    $supplier = new FatoorahServices();
                    $response = $supplier->buildRequest('v2/ExecutePayment', 'POST', $data);

                    if (isset($response['IsSuccess'])) {
                        if ($response['IsSuccess'] == true) {

                            $InvoiceId = $response['Data']['InvoiceId']; // save this id with your order table
                            $success['payment'] = $response;
                            $payment = Payment::create([
                                'paymenDate' => Carbon::now(),
                                'paymentType' => $order->paymentype->name,
                                'orderID' => $order->id,
                                'store_id' => $store_domain,
                                'deduction' => $deduction,
                                'price_after_deduction' => $price_after_deduction,
                                'paymentTransectionID' => $InvoiceId,

                            ]);

                        } else {
                            $success['payment'] = $response;
                        }
                    } else {
                        $success['payment'] = $response;
                    }
                    $cart->update(['order_id' => $order->id]);
                    $success['status'] = 200;

                    return $this->sendResponse($success, 'تم ارسال الطلب بنجاح', 'order send successfully');

                }

            }

            $success['order'] = new OrderResource($order);

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم ارسال الطلب بنجاح', 'order send successfully');

        }
    }

    public function paymentMethods($domain)
    {
        $store = Store::where('is_deleted', 0)->where('domain', $domain)->first();
        $is_madfu = ($store) ? ($store->madfu_username != null && $store->madfu_password != null) : false;
        if ($is_madfu) {
            $success['payment_types'] = PaymenttypeResource::collection($store->paymenttypes);
        } else {
            $success['payment_types'] = PaymenttypeResource::collection($store->paymenttypes->whereNotIn('id', [5]));
        }
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع طرق الدفع بنجاح', 'Payment Types return successfully');
    }

    public function shippingCompany($domain)
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
        $coupon = Coupon::where('code', $request->code)->where('is_deleted', 0)->where('store_id', $store_domain)->first();

        if ($coupon != null && $coupon->status == 'active') {

            $cart = Cart::with([])->where('id', $cart_id)->where('store_id', $store_domain)->first();
            if ($cart->coupon_id == $coupon->id) {
                $success['status'] = 200;
                return $this->sendResponse($success, 'الكوبون مستخدم بالفعل', 'The coupon is already used');
            } else {
                $cart = $this->restCart($cart->id);
            }
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
                                'coupon_id' => $coupon->id,
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
                                'coupon_id' => $coupon->id,

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
                                        'coupon_id' => $coupon->id,

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
                                        'coupon_id' => $coupon->id,

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
            $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
            $orders = Order::where('user_id', auth()->user()->id)->where('store_id', $store_domain)->where('is_archive', 0)
                ->where(function ($sub_query) {
                    $sub_query->where('paymentype_id', 4)->orWhere('payment_status', 'paid');

                })->orderByDesc('created_at');
            $orders = $orders->paginate($count);
            $success['page_count'] = $orders->lastPage();
            $success['current_page'] = $orders->currentPage();
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

    public function cancelOrder($id)
    {
        $order = Order::where('user_id', auth()->user()->id)->where('id', $id)->where('is_deleted', 0)->first();
        if (is_null($order)) {
            return $this->sendError("الطلب غير موجودة", "order is't exists");
        }
        if ($order->order_status == "new" || $order->order_status == "ready") {

            // if ($order->paymentype_id == 1 && $order->payment_status == "paid") {
            //     $payment = Payment::where('orderID', $order->id)->first();

            //     $data = [
            //         "Key" => $payment->paymentTransectionID,
            //         "KeyType" => "invoiceid",
            //         "RefundChargeOnCustomer" => false,
            //         "ServiceChargeOnCustomer" => false,
            //         "Amount" => $order->total_price,
            //         "Comment" => "refund to the customer",
            //         "AmountDeductedFromSupplier" => $payment->price_after_deduction,
            //         "CurrencyIso" => "SAR",
            //     ];

            //     $supplier = new FatoorahServices();

            //     $response = $supplier->refund('v2/MakeRefund', 'POST', $data);
            //     if ($response) {
            //         if ($response['IsSuccess'] == false) {
            //             return $this->sendError("خطأ في الارجاع", $supplierCode->ValidationErrors[0]->Error);
            //         } else {
            //             $success['message'] = $response;
            //         }
            //     } else {
            //         return $this->sendError("خطأ في الارجاع المالي", 'error');
            //     }
            // }
            $order->update([
                'order_status' => 'canceled',
            ]);
            foreach ($order->items as $orderItem) {
                $orderItem->update([
                    'order_status' => 'canceled',
                ]);
                if ($order->payment_status == "paid") {
                    $product = \App\Models\Product::where('id', $orderItem->product_id)->where('store_id', $orderItem->store_id)->first();
                    if ($product) {
                        $product->stock = $product->stock + $orderItem->quantity;
                        $product->save();
                    } else {
                        $import_product = \App\Models\Importproduct::where('product_id', $orderItem->product_id)->where('store_id', $orderItem->store_id)->first();
                        if ($import_product) {
                            $import_product->qty = $import_product->qty + $orderItem->quantity;
                            $import_product->save();
                        }

                    }
                    // $order->is_archive = 1;
                    // $order->save();
                }
            }

            $success['orders'] = new OrderResource($order);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم التعديل بنجاح', 'Order updated successfully');

        } else {
            $success['orders'] = new OrderResource($order);
            $success['status'] = 200;
            return $this->sendError('حالة الطلب لاتقبل الالغاء', 'Order can not cancel');

        }

    }
    public function searchOrder(Request $request, $id)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $orders = Order::with('items')->where(function ($main_query) use ($query) {
            $main_query->whereHas('items', function ($itemQuery) use ($query) {
                $itemQuery->whereHas('product', function ($productQuery) use ($query) {
                    $productQuery->Where('name', 'like', "%$query%");
                });
            })->orWhere('order_number', 'like', "%$query%");
        })->where('is_deleted', 0)->where('store_id', $id)->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')->paginate($count);

        $success['query'] = $query;

        $success['total_result'] = $orders->total();
        $success['page_count'] = $orders->lastPage();
        $success['current_page'] = $orders->currentPage();
        $success['orders'] = OrderResource::collection($orders);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الطلبات  بنجاح', 'orders Information returned successfully');

    }
    private function restCart($id)
    {
        $cart = Cart::with([])->where('id', $id)->first();
        $coupon = Coupon::where('id', $cart->coupon_id)->first();

        $cart->update([
            'total' => $cart->total + $cart->discount_total,
            'discount_type' => null,
            'discount_value' => null,
            'discount_total' => 0,
            'coupon_id' => null,
        ]);
        return $cart->refresh();
    }
    public function shippingCalculation($store_id, $shipping_id)
    {
        $store_domain = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('id')->first();
        if ($store_domain == null) {
            $success['status'] = 200;

            return $this->sendResponse($success, ' المتجر غير موجود', 'store is not exist');

        } else {
            $cart = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_domain)->first();

            if ($cart == null) {
                $success['status'] = 200;

                return $this->sendResponse($success, ' سلة فارغة', 'cart is not exist');

            }
            $shipping_object = shippingtype_store::where('shippingtype_id', $shipping_id)->where('store_id', $store_domain)->first();
            if ($shipping_object != null) {
                $shipping_price = $shipping_object->price;
                if ($cart->weight > 15) {
                    $extra_shipping_price = ($cart->weight - 15) * $shipping_object->overprice;
                } else {
                    $extra_shipping_price = 0;
                }
            } else {
                $shipping_price = 30;
                if ($cart->weight > 15) {
                    $extra_shipping_price = ($cart->weight - 15) * 3;
                } else {
                    $extra_shipping_price = 0;
                }
            }
            if ($cart->free_shipping == 1) {
                $cart->update([
                    'shipping_price' => $cart->shipping_price,
                    'overweight_price' => 0,
                ]);
            } else {
                $cart->update([
                    'shipping_price' => $shipping_price,
                    'total' => (($cart->total - $cart->shipping_price) - $cart->overweight_price) + $shipping_price + $extra_shipping_price,
                    'overweight_price' => $extra_shipping_price,
                ]);

            }

            $success['cart'] = new CartResource($cart);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم ارجاع السلة بنجاح', 'cart return successfully');
        }
    }

}
