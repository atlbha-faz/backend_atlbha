<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Order;
use App\Models\Payment;
use App\Models\ReturnOrder;
use Illuminate\Http\Request;
use App\Services\FatoorahServices;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReturnOrderResource;
use App\Services\ShippingComanies\OtherCompanyService;
use App\Services\ShippingComanies\AramexCompanyService;
use App\Http\Controllers\api\BaseController as BaseController;

class ReturnOrderController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $return = Order::with('returnOrders')->whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id)->where('is_return', 1);
        })->where('store_id', auth()->user()->store_id)->first();
        if (is_null($return)) {
            return $this->sendError("لا يوجد طلبات مسترجعة", "return is't exists");
        }
        $data = Order::with('returnOrders')->whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id)->where('is_return', 1);
        })->where('store_id', auth()->user()->store_id)->orderByDesc('id');
        if ($request->has('status')) {
            $data = Order::with('returnOrders')->whereHas('items', function ($q) {
                $q->where('store_id', auth()->user()->store_id)->where('is_return', 1);
            })->whereHas('returnOrders', function ($q) use ($request) {
                $q->where('return_status', $request->status);
            })->where('store_id', auth()->user()->store_id)->orderByDesc('id');
        }
        $data = $data->paginate($count);
        $success['ReturnOrders'] = ReturnOrderResource::collection($data);
        $success['page_count'] = $data->lastPage();
        $success['current_page'] = $data->currentPage();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض الطلبات المسترجعة بنجاح', 'ReturnOrders showed successfully');
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\returnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function show($returnOrder)
    {
        $return = Order::with('returnOrders')->where('id', $returnOrder)->whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id)->where('is_return', 1);
        })->where('store_id', auth()->user()->store_id)->first();
        if (is_null($return)) {
            return $this->sendError("لا يوجد طلب مسترجع", "return is't exists");
        }
        $success['ReturnOrder'] = new ReturnOrderResource(
            Order::with('returnOrders')->where('id', $returnOrder)->whereHas('items', function ($q) {
                $q->where('store_id', auth()->user()->store_id)->where('is_return', 1);
            })->where('store_id', auth()->user()->store_id)->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض الطلب المسترجع بنجاح', 'ReturnOrders showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\returnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(returnOrder $returnOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\returnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $order_id)
    {

        $order = Order::where('id', $order_id)->whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id)->where('is_return', 1);
        })->first();
        if (is_null($order)) {
            return $this->sendError("'الطلب غير موجود", "Order is't exists");
        }

        $shipping_companies = [
            1 => new AramexCompanyService(),
            5 => new OtherCompanyService(),
        ];
        $input = $request->all();
        $validator = Validator::make($input, [
            'status' => 'required|in:accept,reject',

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $shipping = $shipping_companies[$order->shippingtype->id];

        $returns = ReturnOrder::where('order_id', $order->id)->get();
        $return_first = ReturnOrder::where('order_id', $order->id)->first();
        if( $return_first->return_status == "accept" || $return_first->return_status == "reject")
        {
            return $this->sendError("تم التعديل مسبقا", "return is't exists");

        }
        foreach ($returns as $return) {
           
            $return->return_status=$request->status;
            $return->save();
        }
        if ($request->status == 'accept') {
            $success['order'] = $shipping->refundOrder($order_id);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم  قبول طلب الارجاع', 'order  return accept successfully');
        }
        else{
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم رفض  طلب الارجاع', 'order return reject successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\returnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(returnOrder $returnOrder)
    {
        //
    }
    public function searchReturnOrder(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $orders = Order::with('returnOrders')->whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id)->where('is_return', 1);
        })->where(function ($main_query) use ($query) {
            $main_query->whereHas('user', function ($userQuery) use ($query) {
                $userQuery->where('name', 'like', "%$query%");
            })->orWhere('order_number', 'like', "%$query%");
        })->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)
            ->orderBy('created_at', 'desc')->paginate($count);

        $success['query'] = $query;

        $success['total_result'] = $orders->total();
        $success['page_count'] = $orders->lastPage();
        $success['current_page'] = $orders->currentPage();
        $success['ReturnOrders'] = ReturnOrderResource::collection($orders);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الطلبات بنجاح', 'orders Information returned successfully');

    }
    public function refundReturnOrder($order_id)
    {
        $order = Order::where('id', $order_id)->whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id)->where('is_return', 1);
        })->first();
        if (is_null($order)) {
            return $this->sendError("'الطلب غير موجود", "Order is't exists");
        }
        $payment = Payment::where('orderID', $order->id)->first();
        $returns = ReturnOrder::where('order_id', $order->id)->get();
        $prices = 0;
        foreach ($returns as $return) {
            $prices = $prices + ($return->qty * $return->orderItem->price);         
        }
        if ($order->payment_status == "paid" && $order->paymentype_id == 1) {
            if ($payment != null) {

                $data = [
                    "Key" => $payment->paymentTransectionID,
                    "KeyType" => "invoiceid",
                    "RefundChargeOnCustomer" => false,
                    "ServiceChargeOnCustomer" => false,
                    "Amount" => $prices,
                    "Comment" => "refund to the customer",
                    "AmountDeductedFromSupplier" => $prices,
                    "CurrencyIso" => "SA",
                ];

                $supplier = new FatoorahServices();
                $supplierCode = $supplier->buildRequest('v2/MakeRefund', 'POST', $data);

                if ($supplierCode->IsSuccess == false) {
                    return $this->sendError("خطأ في الارجاع", $supplierCode->ValidationErrors[0]->Error);
                } else {
                    $success['payment'] = $supplierCode;
                }
            }
        }
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم ارجاع الطلبات بنجاح', 'orders Information returned successfully');
    }
}
