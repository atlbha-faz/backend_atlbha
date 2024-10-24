<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Helpers\Helper;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Rules\ValidTimestamp;
use App\Services\ShippingComanies\AramexCompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminOrderController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $success['new'] = Order::where('store_id', null)->where('is_deleted', 0)->where('is_archive', 0)->where(function ($sub_query) {
            $sub_query->where('paymentype_id', null)->orWhere('payment_status', 'paid');

        })->where('order_status', 'new')->count();
        $success['completed'] = Order::where('store_id', null)->where('is_deleted', 0)->where('is_archive', 0)->where(function ($sub_query) {
            $sub_query->where('paymentype_id', null)->orWhere('payment_status', 'paid');

        })->where('order_status', 'completed')->count();

        $success['not_completed'] = Order::where('store_id', null)->where('is_deleted', 0)->where('is_archive', 0)->where(function ($sub_query) {
            $sub_query->where('paymentype_id', null)->orWhere('payment_status', 'paid');

        })->where('order_status', 'not_completed')->count();
        $success['canceled'] = Order::whereHas('items', function ($q) {
            $q->where('store_id', null)->where('order_status', 'canceled');
        })->where('is_archive', 0)->where(function ($sub_query) {
            $sub_query->where('paymentype_id', null)->orWhere('payment_status', 'paid');

        })->count();

        $success['all'] = Order::whereHas('items', function ($q) {
            $q->where('store_id', null);
        })->where('is_deleted', 0)->where('is_archive', 0)->where(function ($sub_query) {
            $sub_query->where('paymentype_id', null)->orWhere('payment_status', 'paid');

        })->count();
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $data = Order::with(['user' => function ($query) {
            $query->select('id', 'city_id');
        }, 'shippings', 'items' => function ($query) {
            $query->select('id');
        }])->where('store_id', null)->where('is_deleted', 0)->where('is_archive', 0)->where(function ($sub_query) {
            $sub_query->where('paymentype_id', null)->orWhere('payment_status', 'paid');

        })->orderByDesc('id')->select(['id', 'user_id', 'order_number', 'total_price', 'quantity', 'created_at', 'order_status']);
        $data = $data->paginate($count);
        $success['orders'] = OrderResource::collection($data);
        $success['page_count'] = $data->lastPage();
        $success['current_page'] = $data->currentPage();
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
            'pickup_date' => ['required_if:status,==,delivery_in_progress', 'required_if:status,ready', new ValidTimestamp],
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        if ($request->status === "completed") {
            Helper::orderProductShow($order->id);
        }
        $shipping_companies = [
            1 => new AramexCompanyService(),
        ];
        if ($request->status == "canceled") {

            $shipping = $shipping_companies[$order->shippingtype->id];
            $success['orders'] = $shipping->cancelOrder($order->id);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم إلغاء الطلب', 'order canceled successfully');

        } else {
            $data = [
                "shipper_line1" => $request->street_address,
                "shipper_line2" => $request->street_address,
                "shipper_city" => $request->city,
                "shipper_district" => $request->district,
                "shipper_name" => "atlbha",
                "shipper_comany" => "atlbha",
                "shipper_name" => "atlbha",
                "shipper_phonenumber" => "00966506340450",
                "shipper_email" => "help@atlbha.sa",
                "order_id" => $order->id,
                "pickup_date" => $request->pickup_date,

            ];
            if ($request->status === "ready") {

                $shipping = $shipping_companies[$order->shippingtype->id];
                $response = $shipping->createOrder($data);

                return $response;
            }
            else{
            if ($request->status === "delivery_in_progress") {

                $shipping = $shipping_companies[$order->shippingtype->id];

                $response = $shipping->createPickup($data);

                return $response;
            } else {
                $order->update([
                    'order_status' => $request->status,
                ]);
                foreach ($order->items as $orderItem) {
                    $orderItem->update([
                        'order_status' => $request->status,
                    ]);
                }
                $success['orders'] = new OrderResource($order);
                $success['status'] = 200;
                return $this->sendResponse($success, 'تم تعديل الطلب', 'order update successfully');
            }
               

            }
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
    public function searchOrder(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $orders = Order::where('is_deleted', 0)->where('store_id', null)->where(function ($q) use ($query) {
            $q->whereHas('user', function ($userQuery) use ($query) {
                $userQuery->whereHas('store', function ($storeQuery) use ($query) {
                    $storeQuery->where('store_name', 'like', "%$query%");
                });
            })->orWhere('order_number', 'like', "%$query%");
        })
            ->where('is_archive', 0)->where('payment_status', 'paid')->orderBy('created_at', 'desc')
            ->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $orders->total();
        $success['page_count'] = $orders->lastPage();
        $success['current_page'] = $orders->currentPage();
        $success['orders'] = OrderResource::collection($orders);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الطلبات بنجاح', 'orders Information returned successfully');

    }

}
