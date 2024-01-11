<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\Product;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Models\Importproduct;
use App\Models\OrderOrderAddress;
use App\Http\Resources\OrderResource;
use App\Http\Resources\shippingResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class AdminOrderController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $success['new'] = Order::where('store_id', null)->where('order_status', 'new')->count();
        $success['completed'] = Order::where('store_id',null)->where('order_status', 'completed')->count();

        $success['not_completed'] = Order::where('store_id',null)->where('order_status', 'not_completed')->count();
        $success['canceled'] = Order::whereHas('items', function ($q) {
            $q->where('store_id',null)->where('order_status', 'canceled');
        })->count();

        $success['all'] = Order::whereHas('items', function ($q) {
            $q->where('store_id',null);
        })->count();

   

        $data = OrderResource::collection(Order::with(['user', 'shipping', 'items' => function ($query) {
            $query->select('id');
        }])->where('store_id',null)->orderByDesc('id')->get(['id', 'user_id', 'order_number', 'total_price', 'quantity', 'order_status']));

        $success['orders'] = $data;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الطلبات بنجاح', 'Orders return successfully');
    }
    public function show($order)
    {
        $order = Order::where('id', $order)->whereHas('items', function ($q) {
            $q->where('store_id', null);
        })->first();
        if (is_null($order)) {
            return $this->sendError("'الطلب غير موجود", "Order is't exists");
        }

        $success['orders'] = new OrderResource($order);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الطلب بنجاح', 'Order showed successfully');
    }

    public function update(Request $request, $order)
    {
        $order = Order::where('id', $order)->whereHas('items', function ($q) {
            $q->where('store_id', null);
        })->first();
        if (is_null($order)) {
            return $this->sendError("'الطلب غير موجود", "Order is't exists");
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'status' => 'required|in:new,completed,delivery_in_progress,ready,canceled',

            'city' => 'required_if:status,==,ready',
            'street_address' => 'required_if:status,==,ready',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        if ($request->input('status') !== "completed") {

            $order->update([
                'order_status' => $request->input('status'),
            ]);
            foreach ($order->items as $orderItem) {
                $orderItem->update([
                    'order_status' => $request->input('status'),
                ]);
            }
        
            $success['orders'] = new OrderResource($order);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم التعديل بنجاح', 'Order updated successfully');
        }else{
            if ($request->status === "completed") {
                $storeAdmain = User::whereIn('user_type', ['store','store_employee'])->where('id', $order->user_id)->first();
                  
              if($storeAdmain != null)  {
                $storeid = Store::where('id',$storeAdmain->store_id)->first();
               
              }
                foreach ($order->items as $orderItem) {

                $product = Product::where('id', $orderItem->product_id)->first();
                if ($orderItem->quantity > $product->stock) {
                    return $this->sendError(' الكمية المطلوبة غير متوفرة', 'quanity more than avaliable');
                } 
                $importOrder = Importproduct::where('product_id', $orderItem->product_id)->where('store_id', $storeid->id)->first();
                if($importOrder == null){
                    $importproduct = Importproduct::create([
                        'product_id' => $orderItem->product_id,
                        'store_id' => $storeid->id,
                        'price' => $orderItem->price,
                        'qty' => $orderItem->quantity,
                    ]);
                   }
                   else{
                    $qty=$importOrder->qty;
                    $importOrder->update([
                        'price' => $orderItem->price,
                        'qty' => $qty+$orderItem->quantity,
                    ]);
                   }

                    $newStock = $product->stock - $orderItem->quantity;
                    $product->update([
                        'stock' => $newStock,
                    ]);
                    //إستيراد الى متجر اطلبها
                    $atlbha_id = Store::where('is_deleted', 0)->where('domain', 'atlbha')->pluck('id')->first();
                    $importAtlbha = Importproduct::where('product_id', $orderItem->product_id)->where('store_id', $atlbha_id)->first();
                    if($importAtlbha == null){
                        $importAtlbha = Importproduct::create([
                            'product_id' => $orderItem->product_id,
                            'store_id' => $atlbha_id,
                            'price' => $product->selling_price,
                            'qty' => $product->stock,
                        ]); 
                    }
                    else{
                    $importAtlbha->update([
                        'product_id' => $product->id,
                        'store_id' => $atlbha_id,
                        'qty' => $product->stock,
                    ]);
                    }


                }

                $order->update([
                    'order_status' => $request->input('status'),
                ]);
                foreach ($order->items as $orderItem) {
                    $orderItem->update([
                        'order_status' => $request->input('status'),
                    ]);
                }






                    $shipping = Shipping::create([
                        'shipping_id' => $order->order_number,
                        'track_id' => null,
                        'description' => $order->description,
                        'quantity' => $order->quantity,
                        'price' => $order->total_price,
                        'weight' => $order->weight,
                        'district' => $request->district,
                        'city' => $request->city,
                        'streetaddress' => $request->street_address,
                        'customer_id' => $order->user_id,
                        'shippingtype_id' => null,
                        'order_id' => $order->id,
                        'shipping_status' => $order->order_status,
                        'store_id' => null,
                        'cashondelivery' => $order->cashondelivery,
                    ]);
                    $success['shipping'] = new shippingResource($shipping);
                }
                $success['orders'] = new OrderResource($order);
                $success['status'] = 200;

                return $this->sendResponse($success, 'تم التعديل بنجاح', 'Order updated successfully');
            }
        }
        
    
}
