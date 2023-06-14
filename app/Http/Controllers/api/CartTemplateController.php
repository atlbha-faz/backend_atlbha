<?php

namespace App\Http\Controllers\api;
use Session;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOfferCart;
use App\Http\Resources\CartResource;
use App\Notifications\emailNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\AbandonedCartResource;
use App\Http\Controllers\api\BaseController as BaseController;

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
    public function index(){


    }

  

    public function show($id){

        $cart = Cart::where('user_id',auth()->user()->id)->where('store_id',$id)->first();
    
         if (is_null($cart)){
         return $this->sendError("السلة غير موجودة","cart is't exists");
         }

        $success['cart']=New CartResource($cart);
        $success['status']= 200;

        return $this->sendResponse($success,'تم عرض  السلة بنجاح','Cart Showed successfully');

      }

    public function addToCart(Request $request)
    {

        $input = $request->all();
                $validator =  Validator::make($input ,[
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
                if ($validator->fails())
                {
                    return $this->sendError(null,$validator->errors());
                }
        $product_quantity=Product::where('id',$request->id)->pluck('quantity')->first();

        if($product_quantity >= $request->qty)
        {
                $cart =Cart::updateOrCreate([
                    'user_id' =>auth()->user()->id,
                    'store_id' =>auth()->user()->store_id,
                ],[
                    'total' =>0,
                    'count' => 0
                ]);
                $cartid =$cart->id;
                if (!is_null($request->data)) {
                    foreach ($request->data as $data) {
         $cartDetail = CartDetail::updateOrCreate([
            'cart_id' => $cartid,
            'product_id' => $data['id'],
          ], [
            'qty' =>$data['qty'],
             'price'=>$data['price'],
            //  'option'=>$data['option'],
          ]);
        }
    }
          $cart->update([
            'total' => CartDetail::where('cart_id',$cartid)->get()->reduce(function ($total, $item) {
                    return $total + ($item->qty * $item->price);
                              }),
             'count'=> CartDetail::where('cart_id',$cartid)->count()
            ]);

          $success=New CartResource($cart);
          $success['status']= 200;
           return $this->sendResponse($success,'تم إضافة  السلة بنجاح','Cart Added successfully');
            }
        else
         $success['status']= 200;
        return $this->sendResponse($success,' الطلب اكبر من الكمية المتوفرة ','quanity more than avaliable');
          }


    public function delete($id)
    {
        $cart_id=Cart::where('user_id',auth()->user()->id)->pluck('id')->first();
        
        $cart =CartDetail::where('product_id',$id)->where('cart_id',$cart_id)->first();
        // $cart =CartDetail::where('id',$id)->first();
          if (is_null($cart)){
                return $this->sendError("المنتج غير موجودة"," product is't exists");
    }
           $cart->delete();
           $newCart=Cart::where('id',$cart_id)->first();
           $newCart->update([
            'total' => CartDetail::where('cart_id',$cart_id)->get()->reduce(function ($total, $item) {
                    return $total + ($item->qty * $item->price);
                              }),
             'count'=> CartDetail::where('cart_id',$cart_id)->count()
            ]);
      
        $success=New CartResource(Cart::where('user_id',auth()->user()->id)->first());
        $success['status']= 200;
         return $this->sendResponse($success,'تم حذف المنتج بنجاح','product deleted successfully');
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
