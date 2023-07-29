<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CartResource;
use App\Mail\SendOfferCart;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CartController extends BaseController
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

    public function admin()
    {
        $success['cart'] = CartResource::collection(Cart::where('store_id', auth()->user()->store_id)->whereNot('count',0)->whereDate('updated_at', '<=', Carbon::now()->subHours(24)->format('Y-m-d'))->get());
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم عرض  السلة بنجاح', 'Cart Showed successfully');

    }

    public function show($id)
    {

        $cart = Cart::where('id', $id)->where('store_id', auth()->user()->store_id)->first();
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

            'id' => [
                'required',
                Rule::exists('products')->where(function ($query) {

                    $query->where('store_id', auth()->user()->store_id)
                        ->where('id', request()->id);
                }),
            ],
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

            $cartDetail = CartDetail::updateOrCreate([
                'cart_id' => $cartid,
                'product_id' => $request->id,
            ], [
                'qty' => $request->qty,
                'price' => $request->price,
                'option' => $request->option,
            ]);

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
        }

        return $this->sendResponse($success, ' الطلب اكبر من الكمية المتوفرة ', 'quanity more than avaliable');
    }

    public function delete(Request $request)
    {

        $carts = Cart::whereIn('id', $request->id)->where('store_id', auth()->user()->store_id)->get();

        foreach ($carts as $cart) {

            $cart->delete();
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

    public function sendOffer($id, Request $request)
    {

        $cart = Cart::where('id', $id)->whereDate('updated_at', '<=', Carbon::now()->subHours(24)->format('Y-m-d'))->first();
        if (is_null($cart)) {
            return $this->sendError("السلة غير موجودة", "cart is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            //'id'=>'required|exists:carts,id',
            'message' => 'required|string',
            //'discount_total' =>"required_if:discount_type,fixed,percent",
            'discount_value' => "required_if:discount_type,fixed,percent",
            'discount_expire_date' => "required_if:discount_type,fixed,percent",
            'free_shipping' => 'in:0,1',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $cart = Cart::where('id', $request->id)->first();
        $discount_total = $request->discount_value;
        if ($request->discount_type == "percent") {
            $discount_total = $cart->total * ($request->discount_value / 100);
        }
        if($cart->discount_total==null){
            $cart->discount_total=0;
        }
        $discount_total1=$cart->discount_total+$discount_total;
        $total=$cart->total- $discount_total;
        $cart->message = $request->message;
        $cart->free_shipping = $request->free_shipping;
        $cart->discount_type = $request->discount_type;
        $cart->discount_value = $request->discount_value;
        $cart->discount_total = $discount_total1;
        $cart->total =  $total;
        $cart->discount_expire_date = $request->discount_expire_date;
         $cart->timestamps = false;
        $cart->save();

        $data = [
            'subject' => "cart offer",
            'message' => $request->message,
            'store_id' => $cart->store_id,
        ];

        $user = User::where('id', $cart->user_id)->first();
        //  Notification::send($user , new emailNotification($data));
        Mail::to($user->email)->send(new SendOfferCart($data));
        $success = new CartResource($cart);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم إرسال العرض بنجاح', 'Offer Cart Send successfully');

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
