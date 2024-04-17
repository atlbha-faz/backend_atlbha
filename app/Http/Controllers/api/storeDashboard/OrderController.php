<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\OrderResource;
use App\Http\Resources\shippingResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\shippingtype_store;
use App\Services\FatoorahServices;
use App\Services\ShippingComanies\AramexCompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use in;

class OrderController extends BaseController
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
    public function index(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $success['new'] = Order::where('store_id', auth()->user()->store_id)->where('order_status', 'new')->count();
        $success['completed'] = Order::whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id)->where('order_status', 'completed');
        })->count();

        $success['not_completed'] = Order::where('store_id', auth()->user()->store_id)->where('order_status', 'not_completed')->count();
        $success['canceled'] = Order::whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id)->where('order_status', 'canceled');
        })->count();

        $success['all'] = Order::whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id);
        })->count();

        $data = Order::with(['user', 'shipping', 'shippingtype', 'items' => function ($query) {
            $query->select('id');
        }])->where('store_id', auth()->user()->store_id)->orderByDesc('id')->select(['id', 'user_id', 'shippingtype_id', 'total_price', 'quantity', 'order_status', 'payment_status', 'created_at']);
        if ($request->has('order_status')) {
            $data->where('order_status', $request->order_status);
        }
        $data = $data->paginate($count);
        $success['page_count'] = $data->lastPage();
        $success['current_page'] = $data->currentPage();

        $success['orders'] = OrderResource::collection($data);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الطلبات بنجاح', 'Orders return successfully');
    }

    public function show($order)
    {
        $order = Order::where('id', $order)->whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id);
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
            $q->where('store_id', auth()->user()->store_id);
        })->first();
        if (is_null($order)) {
            return $this->sendError("'الطلب غير موجود", "Order is't exists");
        }

        $shipping_companies = [
            1 => new AramexCompanyService(),
        ];
        $input = $request->all();
        $validator = Validator::make($input, [
            'status' => 'required|in:new,completed,delivery_in_progress,ready,refund,canceled',

            'city' => 'required_if:status,==,ready',
            'street_address' => 'required_if:status,==,ready',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        if ($request->status == "canceled") {

            $shipping = $shipping_companies[$order->shippingtype->id];
            return $shipping->cancelOrder($order->id);

        } else {
            $data = [
                "shipper_line1" => $request->street_address,
                "shipper_line2" => $request->street_address,
                "shipper_city" => $request->city,
                "shipper_district" => $request->district,
                "shipper_name" => auth()->user()->name,
                "shipper_comany" => auth()->user()->store->store_name,
                "shipper_name" => auth()->user()->name,
                "shipper_phonenumber" => auth()->user()->phonenumber,
                "shipper_email" => auth()->user()->email,
                "order_id" => $order->id,

            ];
            if ($request->status === "ready") {
                $shipping = $shipping_companies[$order->shippingtype->id];
                return $shipping->createOrder($data);

            }
            if ($request->status === "refund") {
                $shipping = $shipping_companies[$order->shippingtype->id];
                return $shipping->refundOrder($data);
                $payment = Payment::where('orderID', $order->id)->first();
                if ($payment != null) {
                    $shipping_price = shippingtype_store::where('shippingtype_id', $order->shippingtype_id)->where('store_id', auth()->user()->store_id)->first();
                    if ($shipping_price == null) {
                        $shipping_price = 35;
                        $extraprice = 2;
                    } else {
                        $overprice = $shipping_price->overprice;
                        $shipping_price = $shipping_price->price;
                        $extraprice = $overprice;
                    }if ($order->weight > 15) {
                        $default_extra_price = ($order->weight - 15) * 2;
                        $extra_shipping_price = ($order->weight - 15) * $extraprice;
                    } else {
                        $extra_shipping_price = 0;
                        $default_extra_price = 0;
                    }
                    $total_price_without_shipping = ($order->total_price) - ($shipping_price) - ($extra_shipping_price);
                    $data = [

                        "Key" => $payment->paymentTransectionID,
                        "KeyType" => "invoiceid",
                        "RefundChargeOnCustomer" => false,
                        "ServiceChargeOnCustomer" => false,
                        "Amount" => $total_price_without_shipping,
                        "Comment" => "refund to the customer",
                        "AmountDeductedFromSupplier" => 0,
                        "CurrencyIso" => "SA",
                    ];

                    $supplier = new FatoorahServices();
                    $supplierCode = $supplier->buildRequest('v2/MakeRefund', 'POST', $data);

                    if ($supplierCode->IsSuccess == false) {
                        return $this->sendError("خطأ في الارجاع", $supplierCode->ValidationErrors[0]->Error);
                    } else {
                        $success['test'] = $supplierCode;
                    }

                }
                $success['shipping'] = new shippingResource($shipping);

    
            }
        }
    }

    public function deleteAll(Request $request)
    {

        $orders = Order::whereIn('id', $request->id)->whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id);
        })->get();
        foreach ($orders as $order) {
            if (is_null($order)) {
                return $this->sendError("الطلب غير موجود", " Order is't exists");
            }
            $items = OrderItem::where('store_id', auth()->user()->store_id)->where('order_id', $order->id)->get();
            foreach ($items as $item) {
                $item->delete();
            }
        }
        $success['orders'] = OrderResource::collection($orders);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف الطلبات بنجاح', 'Order deleted successfully');
    }
    public function searchOrder(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $orders = Order::whereHas('user', function ($userQuery) use ($query) {
            $userQuery->where('name', 'like', "%$query%");
        })->orWhereHas('shippingtype', function ($shippingtypeQuery) use ($query) {
            $shippingtypeQuery->where('name', 'like', "%$query%");
        })->orWhereHas('shipping', function ($shippingQuery) use ($query) {
            $shippingQuery->where('track_id', 'like', "%$query%");
        })->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)
            ->orderBy('created_at', 'desc')->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $orders->total();
        $success['page_count'] = $orders->lastPage();
        $success['current_page'] = $orders->currentPage();
        $success['orders'] = OrderResource::collection($orders);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الطلبات بنجاح', 'orders Information returned successfully');

    }

}
