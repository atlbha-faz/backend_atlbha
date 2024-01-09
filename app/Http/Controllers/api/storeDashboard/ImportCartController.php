<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Cart;
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
    public function index()
    {

        $cart = Cart::where('user_id', auth()->user()->id)->where('is_deleted', 0)->where('store_id', null)->first();

        if (is_null($cart)) {
            return $this->sendError("السلة غير موجودة", "cart is't exists");
        }

        $success['cart'] = new CartResource($cart);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض  السلة بنجاح', 'Cart Showed successfully');

        // }
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
            'data.*.qty' => 'required|numeric',
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
                'shipping_price' => 35,
                'tax' => 0,
            ]);
            $cartid = $cart->id;
            foreach ($request->data as $data) {
                $product_quantity = Product::where('id', $data['id'])->where('store_id',null)->pluck('stock')->first();
                $less_quantity = Product::where('id', $data['id'])->where('store_id',null)->pluck('less_qty')->first();
                if ($data['qty']< $less_quantity) {
                    return $this->sendResponse($success, ' اقل كمية للطلب هي '.$less_quantity, 'less quqntity');
                }
                if ($product_quantity >= $data['qty']) {
                    $cartDetail = CartDetail::updateOrCreate([
                        'cart_id' => $cartid,
                        'product_id' => $data['id'],
                    ], [
                        'qty' =>$data['qty'],
                        'price' => $data['price'],
                        //  'option'=>$data['option'],
                    ]);
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
                $extra_shipping_price = ($weight - 15) * 3;
            } else {
                $extra_shipping_price = 0;
            }

            $total = $subtotal + Cart::where('id', $cartid)->value('shipping_price');

            $cart->update([
                'total' => $total + $extra_shipping_price,
                'count' => CartDetail::where('cart_id', $cartid)->count(),
                'subtotal' => $subtotal,
                'totalCount' => $totalCount,
                'tax' => $tax,
                'weight' => $weight,
            ]);

            $success = new CartResource($cart);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم إضافة  السلة بنجاح', 'Cart Added successfully');

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
            $extra_shipping_price = ($newCart->weight - 15) * 3;
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
