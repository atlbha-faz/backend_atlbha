<?php

namespace App\Http\Controllers\api\storeTemplate;

use App\Helpers\StoreHelper;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Importproduct;
use App\Models\Option;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Models\Value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartTemplateController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function show(Request $request, $id)
    {
        $store = StoreHelper::check_store_existing($id);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }

        $success['domain'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('domain')->first();

        $cart = Cart::where('user_id', auth()->user()->id)->where('is_deleted', 0)->where('store_id', $store->id)->first();

        if (is_null($cart)) {
            return $this->sendError("السلة غير موجودة", "cart is't exists");
        }
        if ($request->has('delete')) {
            $cart->delete();
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف السلة بنجاح', 'Cart deleted successfully');
        } else {
            $success['cart'] = new CartResource($cart);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم عرض  السلة بنجاح', 'Cart Showed successfully');
        }
        // }
    }
    public function addToCart(Request $request, $domain)
    {

        $store_domain = Store::where('is_deleted', 0)->where('domain', $domain)->pluck('id')->first();
        if ($store_domain == null) {
            $success['status'] = 200;

            return $this->sendResponse($success, ' المتجر غير موجود', 'store is not exist');

        } else { $input = $request->all();
            $validator = Validator::make($input, [
                'is_service' => 'nullable|in:0,1',
                'data' => 'nullable|array',
                'data.*.id' => [
                    'required',
                ],
                'data.*.item' => 'nullable|numeric',
                'data.*.price' => 'required|numeric',
                'data.*.qty' => 'required|numeric',
                'data.*.option_id' => 'nullable|exists:options,id',
                'data.*.value' => 'nullable|array',

            ]);
            if ($validator->fails()) {
                return $this->sendError(null, $validator->errors());
            }

            $store = Store::where('domain', $domain)->first();
            $store_id = $store->id;

            if (!is_null($request->data)) {

                $cart = Cart::updateOrCreate([
                    'user_id' => auth()->user()->id,
                    'store_id' => $store_id,
                    'is_service' => $request->is_service,
                ], [
                    'total' => 0,
                    'count' => 0,
                    'shipping_price' => 0,
                    'tax' => 0,
                ]);
                $cartid = $cart->id;
                $options = array();

                foreach ($request->data as $data) {
                    if ($request->is_service == 1) {
                        $service = Product::where('id', $data['id'])->where('store_id', $store_id)->first();
                        if ($service->is_service == 0) {
                            return $this->sendError("لا يمكن الاضافة لأن هذا المنتج ليس خدمة", "can't add");
                        }
                    } else {
                        $product = Product::where('id', $data['id'])->where('store_id', $store_id)->first();
                        if ($product->is_service == 1) {
                            return $this->sendError("لا يمكن الاضافة لأن هذا المنتج  خدمة", "Can't add");
                        }
                    }
                    if (isset($data['option_id']) && !is_null($data['option_id'])) {
                        $q = Option::where('id', $data['option_id'])->where('product_id', $data['id'])->first();
                        if (is_null($q)) {
                            $product_quantity = Product::where('id', $data['id'])->where('store_id', $store_id)->pluck('stock')->first();
                        } else {
                            $q = Option::where('id', $data['option_id'])->where('product_id', $data['id'])->first();
                            $array = explode(',', $q->name['ar']);
                            $product_quantity = $q->quantity;

                        }
                    } else {
                        $data['option_id'] = null;
                        $product_quantity = Product::where('id', $data['id'])->where('store_id', $store_id)->pluck('stock')->first();
                        //if product is service
                        if ($request->is_service == 1) {
                            if (isset($data['value']) && !is_null($data['value'])) {
                                $service_product = Product::where('id', $data['id'])->where('store_id', $store_id)->first();
                                $price = $service_product->selling_price;
                                $discount_price = $service_product->discount_price;
                                $period = $service_product->period;
                                $names = [];
                                foreach ($data['value'] as $value) {

                                    $data_value = Value::where('id', $value)->first();
                                    $data_value = explode(',', $data_value->value);
                                    array_push($names, $data_value[0]);
                                    $name = [
                                        "ar" => implode(',', $names),
                                    ];
                                    $price +=(int)$data_value[1] !=""? $data_value[1] : 0;
                                    $discount_price +=(int) $data_value[2] !=""? $data_value[2] : 0;
                                    $period +=(int) $data_value[3] !=""? $data_value[3] : 0;
                                }
                                $option = new Option([
                                    'price' => $price,
                                    'discount_price' => $discount_price,
                                    'quantity' => 1,
                                    'period' => $period,
                                    'name' => $name,
                                    'product_id' => $data['id'],
                                    'default_option' => 0,

                                ]);

                                $option->save();
                                $data['option_id'] = $option->id;
                            }
                        }

                    }
                    if ($product_quantity == null) {
                        $product_quantity = Importproduct::where('product_id', $data['id'])->where('store_id', $store_id)->pluck('qty')->first();
                    }
                    if ($product_quantity >= $data['qty']) {
                        if (!isset($data['item'])) {
                            $data['item'] = null;
                        }
                        if ($data['item'] !== null) {
                            $cartDetail = CartDetail::where('cart_id', $cartid)->where('id', $data['item'])->first();
                            //if product is service
                            if ($request->is_service == 1) {
                                $previous_option_id = $cartDetail->option_id;
                                if (!is_null($previous_option_id)) {
                                    Option::where('id', $previous_option_id)->delete();
                                }
                            }
                        } else {

                            $cartDetail = CartDetail::where('cart_id', $cartid)->where('option_id', $data['option_id'])->where('product_id', $data['id'])->first();
                            $cartDetails = CartDetail::where('cart_id', $cartid)->where('option_id', $data['option_id'])->where('product_id', $data['id'])->count();
                            if ($cartDetails >= 1) {
                                $data['qty'] = $cartDetail->qty + $data['qty'];
                            }

                        }
                        if ($cartDetail) {
                            $cartDetail->update([
                                'qty' => $data['qty'],
                                'price' => $data['price'],
                                'option_id' => $data['option_id'],
                            ]);
                        } else {
                            $cartDetail = CartDetail::create([
                                'cart_id' => $cartid,
                                'product_id' => $data['id'],
                                'qty' => $data['qty'],
                                'price' => $data['price'],
                                'option_id' => $data['option_id'],
                            ]);
                        }

                    } else {
                        $success['status'] = 200;

                        return $this->sendResponse($success, ' الكمية المطلوبة غير متوفرة', 'quanity more than avaliable');
                    }
                }

                $subtotal = CartDetail::where('cart_id', $cartid)->get()->reduce(function ($total, $item) {
                    return $total + ($item->qty * $item->price);
                });
                $weight = CartDetail::where('cart_id', $cartid)->get()->reduce(function ($total, $item) {
                    return $total + ($item->qty * $item->product->weight);
                });
                $totalCount = CartDetail::where('cart_id', $cartid)->get()->reduce(function ($total, $item) {
                    return $total + ($item->qty);
                });
                $tax = $subtotal * 0.15;
                if ($weight > 15) {
                    $extra_shipping_price = 0;
                } else {
                    $extra_shipping_price = 0;
                }

                $total = $subtotal + Cart::where('id', $cartid)->value('shipping_price');

                $cart->update([
                    'total' => $total + $extra_shipping_price,
                    'count' => CartDetail::where('cart_id', $cartid)->count(),
                    'subtotal' => $subtotal - $tax,
                    'totalCount' => $totalCount,
                    'overweight_price' => $extra_shipping_price,
                    'tax' => $tax,
                    'weight' => $weight,
                    'discount_type' => null,
                    'discount_value' => null,
                    'discount_total' => null,
                    'discount_expire_date' => null,
                    'free_shipping' => 0,
                ]);

                $success = new CartResource($cart);
                $success['status'] = 200;
                return $this->sendResponse($success, 'تم إضافة  السلة بنجاح', 'Cart Added successfully');

            }}
    }

    public function delete($domain, $id)
    {

        $store = Store::where('domain', $domain)->first();
        $cart_id = Cart::where('user_id', auth()->user()->id)->where('store_id', $store->id)->pluck('id')->first();
        $cart = CartDetail::where('id', $id)->first();

        if (is_null($cart)) {
            return $this->sendError("المنتج غير موجودة", " product is't exists");
        }
        $cart->delete();
        $newCart = Cart::where('id', $cart_id)->first();
        $newCart->update([
            'total' => CartDetail::where('cart_id', $cart_id)->get()->reduce(function ($total, $item) {
                return $total + ($item->qty * $item->price);
            }),
            'count' => CartDetail::where('cart_id', $cart_id)->count(),
            'weight' => CartDetail::where('cart_id', $cart_id)->get()->reduce(function ($total, $item) {
                return $total + ($item->qty * $item->product->weight);
            }),
            'totalCount' => CartDetail::where('cart_id', $cart_id)->get()->reduce(function ($total, $item) {
                return $total + ($item->qty);
            }),

        ]);
        if ($newCart->weight > 15) {
            $extra_shipping_price = 0;
        } else {
            $extra_shipping_price = 0;
        }
        $newCart->update([
            'tax' => $newCart->total * 0.15,
            'shipping_price' => $newCart->shipping_price,
        ]);

        $newCart->update([
            'subtotal' => $newCart->total - $newCart->tax,
            'total' => $newCart->total + $newCart->shipping_price + $extra_shipping_price,
            'discount_type' => null,
            'discount_value' => null,
            'discount_total' => null,
            'discount_expire_date' => null,
            'free_shipping' => 0,
        ]);

        if ($newCart->count == 0) {
            $newCart->delete();
        } else {
            $success = new CartResource(Cart::where('user_id', auth()->user()->id)->where('store_id', $store->id)->first());
        }
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف المنتج بنجاح', 'product deleted successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */

}
