<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\OrderResource;
use App\Http\Resources\shippingResource;
use App\Models\Importproduct;
use App\Models\Option;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminOrderController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $success['new'] = Order::where('store_id', null)->where('is_deleted', 0)->where('order_status', 'new')->count();
        $success['completed'] = Order::where('store_id', null)->where('is_deleted', 0)->where('order_status', 'completed')->count();

        $success['not_completed'] = Order::where('store_id', null)->where('is_deleted', 0)->where('order_status', 'not_completed')->count();
        $success['canceled'] = Order::whereHas('items', function ($q) {
            $q->where('store_id', null)->where('order_status', 'canceled');
        })->count();

        $success['all'] = Order::whereHas('items', function ($q) {
            $q->where('store_id', null);
        })->where('is_deleted', 0)->count();

        $data = OrderResource::collection(Order::with(['user' => function ($query) {
            $query->select('id', 'city_id');
        }, 'shipping', 'items' => function ($query) {
            $query->select('id');
        }])->where('store_id', null)->where('is_deleted', 0)->orderByDesc('id')->get(['id', 'user_id', 'order_number', 'total_price', 'quantity', 'created_at', 'order_status']));

        $success['orders'] = $data;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الطلبات بنجاح', 'Orders return successfully');
    }
    public function show($order)
    {
        $order = Order::where('id', $order)->where('is_deleted', 0)->whereHas('items', function ($q) {
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
        if ($order->order_status == "completed") {
            return $this->sendError("الطلب مكتمل", "Order is complete");
        }
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
        } else {
            if ($request->status === "completed") {

                $storeAdmain = User::whereIn('user_type', ['store', 'store_employee'])->where('id', $order->user_id)->first();

                if ($storeAdmain != null) {
                    $storeid = Store::where('id', $storeAdmain->store_id)->first();

                }
                foreach ($order->items as $orderItem) {
                    $product = Product::where('id', $orderItem->product_id)->where('store_id', null)->first();
                    $import_product_existing = Importproduct::where('product_id', $product->id)->where('store_id', $storeid->id)->first();

                    if ($import_product_existing == null) {
                        $import_product = Importproduct::create([
                            'product_id' => $product->id,
                            'store_id' => $storeid->id,
                            'price' => $orderItem->price,
                            'qty' => $orderItem->quantity,
                        ]);
                        $new_stock = $product->stock - $import_product->qty;
                        $product->update([
                            'stock' => $new_stock,
                        ]);

                        if ($orderItem->option_id != null) {
                            $option = Option::where('is_deleted', 0)->where('original_id', $orderItem->option_id)->where('importproduct_id', $import_product->id)->first();
                            if ($option == null) {
                                $orginal_option = Option::where('is_deleted', 0)->where('id', $orderItem->option_id)->where('importproduct_id', null)->where('original_id', null)->first();
                                $newOption = $orginal_option->replicate();
                                $newOption->product_id = null;
                                $newOption->original_id = $orginal_option->id;
                                $newOption->importproduct_id = $import_product->id;
                                $newOption->quantity = $orderItem->quantity;
                                $newOption->price = $orderItem->price;
                                $newOption->save();
                            } else {
                                $qty = $option->quantity;
                                $option->update([
                                    'quantity' => $qty + $orderItem->quantity,
                                ]);

                            }
                        }
                    } else {
                        $qty_product = $import_product_existing->qty;
                        $import_product_existing->update([
                            'qty' => $qty_product + $orderItem->quantity,
                        ]);
                        if ($orderItem->option_id != null) {

                            $option = Option::where('is_deleted', 0)->where('original_id', $orderItem->option_id)->where('importproduct_id', $import_product_existing->id)->first();
                            if ($option == null) {
                                $orginal_option = Option::where('is_deleted', 0)->where('id', $orderItem->option_id)->where('original_id', null)->first();

                                $newOption = $orginal_option->replicate();
                                $newOption->product_id = null;
                                $newOption->original_id = $orginal_option->id;
                                $newOption->importproduct_id = $import_product_existing->id;
                                $newOption->quantity = $orderItem->quantity;
                                $newOption->price = $orderItem->price;
                                $newOption->save();
                            } else {
                                $qty = $option->quantity;
                                $option->update([
                                    'quantity' => $qty + $orderItem->quantity,
                                ]);

                            }
                        }
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
    public function deleteAll(Request $request)
    {

        $orders = Order::whereIn('id', $request->id)->where('store_id', null)->where('is_deleted', 0)->get();
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $order->update(['is_deleted' => $order->id]);
                $success['orders'] = new OrderResource($order);

            }

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم حذف الطلب بنجاح', 'order deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير موجودة', 'id does not exit');
        }
    }
    public function searchName(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $orders = Order::where('is_deleted', 0)->where('store_id', null)->whereHas('user', function ($userQuery) use ($query) {
            $userQuery->whereHas('store', function ($storeQuery) use ($query) {
                $storeQuery->where('store_name', 'like', "%$query%");
            });
        })->orWhere('order_number', 'like', "%$query%")
            ->orderBy('created_at', 'desc')
            ->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $orders->total();
        $success['page_count'] = $orders->lastPage();
        $success['current_page'] = $orders->currentPage();
        $success['orders'] = OrderResource::collection($orders);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع السلات المتروكة بنجاح', 'orders Information returned successfully');

    }

}
