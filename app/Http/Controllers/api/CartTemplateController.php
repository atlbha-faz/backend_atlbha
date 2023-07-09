<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
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

    public function show($id)
    {
        $store = Store::where('domain', $id)->where('verification_status', 'accept')->whereDate('end_at', '>', Carbon::now())->first();
        if (is_null($store) || $store->is_deleted == 1) {
            return $this->sendError("المتجر غير موجودة", "Store is't exists");
        }
        if ($store->maintenance != null) {
            if ($store->maintenance->status == 'active') {
                $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);

                $success['status'] = 200;

                return $this->sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');

            }

        }

        $id = $store->id;

        $success['domain'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('domain')->first();

        $cart = Cart::where('user_id', auth()->user()->id)->where('is_deleted', 0)->where('store_id', $id)->first();

        if (is_null($cart)) {
            return $this->sendError("السلة غير موجودة", "cart is't exists");
        }

        $success['cart'] = new CartResource($cart);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض  السلة بنجاح', 'Cart Showed successfully');

    }

    public function addToCart(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'data' => 'nullable|array',
            'data.*.id' => [
                'required',
                //  Rule::exists('products')->where(function($query){

                //             $query->where('store_id', auth()->user()->store_id)
                //                   ->where('id', request()->id);
                //  })
            ],

            'data.*.price' => 'required|numeric',
            'data.*.qty' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $product_quantity = Product::where('id', $request->id)->pluck('quantity')->first();

        if ($product_quantity >= $request->qty) {
            $cart = Cart::updateOrCreate([
                'user_id' => auth()->user()->id,
                'store_id' => auth()->user()->store_id,
            ], [
                'total' => 0,
                'count' => 0,
            ]);
            $cartid = $cart->id;
            if (!is_null($request->data)) {
                foreach ($request->data as $data) {
                    $cartDetail = CartDetail::updateOrCreate([
                        'cart_id' => $cartid,
                        'product_id' => $data['id'],
                    ], [
                        'qty' => $data['qty'],
                        'price' => $data['price'],
                        //  'option'=>$data['option'],
                    ]);
                }
            }
            $cart->update([
                'total' => CartDetail::where('cart_id', $cartid)->get()->reduce(function ($total, $item) {
                    return $total + ($item->qty * $item->price);
                }),
                'count' => CartDetail::where('cart_id', $cartid)->count(),
            ]);

            $success = new CartResource($cart);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم إضافة  السلة بنجاح', 'Cart Added successfully');
        } else {
            $success['status'] = 200;

            return $this->sendResponse($success, ' الطلب اكبر من الكمية المتوفرة ', 'quanity more than avaliable');
        }
    }
    public function delete($id)
    {
        $cart_id = Cart::where('user_id', auth()->user()->id)->pluck('id')->first();
        $cart = CartDetail::where('product_id', $id)->where('cart_id', $cart_id)->first();

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
        ]);

        $success = new CartResource(Cart::where('user_id', auth()->user()->id)->first());
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
