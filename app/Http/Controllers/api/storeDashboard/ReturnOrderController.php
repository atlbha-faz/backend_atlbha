<?php

namespace App\Http\Controllers\api\storeTemplate;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\ReturnOrderResource;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ReturnOrder;
use App\Services\FatoorahServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReturnOrderController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $success['ReturnOrders'] = ReturnOrderResource::collection(Order::with('returnOrders')->whereHas('items', function ($q) use ($id) {
            $q->where('store_id', auth()->user()->store_id)->where('is_return', 1);
        })->where('store_id', auth()->user()->store_id)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'ReturnOrders showed successfully');
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
    public function show(returnOrder $returnOrder)
    {
        //
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
        if ($request->status === "accept") {

            $shipping = $shipping_companies[$order->shippingtype->id];

            $payment = Payment::where('orderID', $order->id)->first();
            $returns = ReturnOrder::where('order_id', $order->id)->get();
            $prices=0;
            foreach ($returns as $return) {
                $prices=$prices+($return->qty*$return->orderItem->price);
                
            }
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
            
            $success['shipping'] = $shipping->refundOrder($order_id);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم تعديل الطلب', 'order update successfully');

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
}
