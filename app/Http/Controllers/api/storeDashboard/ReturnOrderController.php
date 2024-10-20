<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\ReturnOrderResource;
use App\Mail\SendMail2;
use App\Models\Account;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ReturnOrder;
use App\Models\Store;
use App\Models\User;
use App\Services\FatoorahServices;
use App\Services\ShippingComanies\AramexCompanyService;
use App\Services\ShippingComanies\OtherCompanyService;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
        })->where('store_id', auth()->user()->store_id)->select(['id', 'user_id', 'order_number', 'total_price', 'quantity', 'order_status', 'payment_status', 'created_at'])->orderByDesc('id');
        if ($request->has('status')) {
            $data = $data->whereHas('returnOrders', function ($q) use ($request) {
                $q->where('return_status', $request->status);
            });
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
        if ($return_first->return_status == "accept" || $return_first->return_status == "reject") {
            return $this->sendError("تم التعديل مسبقا", "return is't exists");

        }
        foreach ($returns as $return) {

            $return->return_status = $request->status;
            $return->save();
        }
        if ($request->status == 'accept') {
            $response = $shipping->refundOrder($order_id);
            if ($response) {
                $response=$shipping->createReversePickup($order_id);
            }
            $success['order'] = $response;
            $store = Store::where('id', $order->store_id)->first();
            $user = User::where('id', $order->user_id)->first();
            $data = [
                'subject' => "قبول طلب الارجاع ",
                'message' => "تم قبول طلب الارجاع",
                'store_id' => $store->store_name,
                'store_email' => $store->store_email,
            ];
            Mail::to($user->email)->send(new SendMail2($data));
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم  قبول طلب الارجاع', 'order  return accept successfully');
        } else {
            $store = Store::where('id', $order->store_id)->first();
            $user = User::where('id', $order->user_id)->first();
            $data = [
                'subject' => " رفض طلب الارجاع ",
                'message' => "تم رفض طلب الارجاع",
                'store_id' => $store->store_name,
                'store_email' => $store->store_email,
            ];
            Mail::to($user->email)->send(new SendMail2($data));
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
        $account = Account::where('store_id', auth()->user()->store_id)->first();
        $prices = 0;
        foreach ($returns as $return) {
            $prices = $prices + ($return->qty * $return->orderItem->price);
        }
        if ($order->payment_status == "paid" && in_array($order->paymentype_id, [1, 2])) {
            $return_status = ReturnOrder::where('order_id', $order->id)->first();
            if ($payment != null) {
                $final_price = $prices;

                $data = [
                    "Key" => $payment->paymentTransectionID,
                    "KeyType" => "invoiceid",
                    "ServiceChargeOnCustomer" => false,
                    "Amount" => round(($final_price), 1),
                    "Comment" => "refund to the customer",
                    "AmountDeductedFromSupplier" => $final_price,
                    "CurrencyIso" => "SAR",
                ];
                $supplier = new FatoorahServices();
                try {
                    $response = $supplier->refund('v2/MakeRefund', 'POST', $data);
                } catch (ClientException $e) {
                    if ($return_status->refund_status == 1) {
                        return $this->sendError("تم الارجاع مسبقا", 'Message: ' . $e->getMessage());
                    } else {
                        return $this->sendError("لايوجد لديك رصيد كافي", 'Message: ' . $e->getMessage());
                    }
                } catch (Exception $e) {
                    return $this->sendError("An unexpected error occurred:", 'Message: ' . $e->getMessage());
                }

                if ($response['IsSuccess'] == false) {
                    return $this->sendError("خطأ في الارجاع", $response->ValidationErrors[0]->Error);
                } else {
                    $success['payment'] = $response;
                    $returns = ReturnOrder::where('order_id', $order->id)->get();
                    foreach ($returns as $return) {
                        $return->update([
                            'refund_status' => 1,
                        ]);
                    }
                }
            }
        }
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم ارجاع الطلبات بنجاح', 'orders Information returned successfully');
    }

}
