<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Cart;
use App\Models\Option;
use App\Models\Product;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class ImportCartController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {

        $cart = Cart::where('user_id', auth()->user()->id)->where('is_deleted', 0)->where('store_id', null)->first();

        if (is_null($cart)) {
            return $this->sendError("السلة غير موجودة", "cart is't exists");
        }
        if($request->has('delete')){
            $cart->delete();
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف السلة بنجاح', 'Cart deleted successfully');
         }
         else{
        $success['cart'] = new CartResource($cart);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض  السلة بنجاح', 'Cart Showed successfully');

        }
    }
    public function addToCart(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'data' => 'nullable|array',
            'data.*.id' => [
                'required',
            ],
            'data.*.price' => 'required|numeric',
            'data.*.item' => 'nullable|numeric',
            'data.*.qty' => 'required|numeric',
            'data.*.option_id' => 'nullable|exists:options,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }


        if (!is_null($request->data)) {
            $cart = Cart::updateOrCreate([
                'user_id' => auth()->user()->id,
                'store_id' => null,
            ], [
                'total' => 0,
                'count' => 0,
                'shipping_price' => 0,
                'tax' => 0,
            ]);
            $cartid = $cart->id;
            foreach ($request->data as $data) {
                if (isset($data['option_id']) && !is_null($data['option_id'])) {
                    $q = Option::where('id',$data['option_id'])->where('product_id', $data['id'])->first();
                    if (is_null($q)) {
                        $product_quantity = Product::where('id', $data['id'])->where('store_id',null)->pluck('stock')->first();
                    } else {
                        $q = Option::where('id',$data['option_id'])->where('product_id', $data['id'])->first();
                        $array = explode(',', $q->name['ar']);
                         $product_quantity = $q->quantity;
                        }
                   }
                   else{
                    $data['option_id']=null;
                    $product_quantity = Product::where('id', $data['id'])->where('store_id', null)->pluck('stock')->first();
                   }
                   if($data['option_id'] == null){
                $less_quantity = Product::where('id', $data['id'])->where('store_id',null)->pluck('less_qty')->first();
                   } 
                   else{
                    $less_quantity = Option::where('id',$data['option_id'])->where('product_id', $data['id'])->pluck('less_qty')->first();

                   }
                if ($data['qty']< $less_quantity) {
                    $success['status'] = 200;
                    return $this->sendResponse($success, ' اقل كمية للطلب هي '.$less_quantity, 'less quqntity');
                }
                if ($product_quantity >= $data['qty']) {
                    if (!isset($data['item'])){
                        $data['item']=null;
                    }
                    if( $data['item'] !==null){
                    $cartDetail=CartDetail::where( 'cart_id', $cartid)->where('id',$data['item'])->first();
                    }
                    else{
                        $cartDetail=CartDetail::where('cart_id', $cartid)->where('option_id', $data['option_id'])->where('product_id', $data['id'])->first();
                    }
                     if( $cartDetail){
                        $cartDetail->update([
                            'qty' => $data['qty'],
                            'price' => $data['price'],
                            'option_id' =>  $data['option_id']
                        ]);
                     }
                     else{
                    $cartDetail = CartDetail::create([
                        'cart_id' => $cartid,
                        'product_id' => $data['id'],
                        'qty' => $data['qty'],
                        'price' => $data['price'],
                       'option_id' =>  $data['option_id']
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
                'subtotal' => $subtotal- $tax,
                'totalCount' => $totalCount,
                'overweight_price' => $extra_shipping_price,
                'tax' => $tax,
                'weight' => $weight,
            ]);

            $success['data'] = new CartResource($cart);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم اضافة الطلب في السلة', 'Cart Added successfully');

        }

    }
    public function delete($id)
    {
        $cart_id = Cart::where('user_id', auth()->user()->id)->where('store_id', null)->pluck('id')->first();
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
        ]);

        if ($newCart->count == 0) {
            $newCart->delete();
        } else {
            $success = new CartResource(Cart::where('user_id', auth()->user()->id)->where('store_id',null)->first());
        }
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف المنتج بنجاح', 'product deleted successfully');
    }

}
