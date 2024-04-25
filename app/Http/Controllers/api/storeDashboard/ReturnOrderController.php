<?php

namespace App\Http\Controllers\api\storeTemplate;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderItem;
use App\Models\ReturnOrder;
use App\Models\shippingtype_store;
use Illuminate\Http\Request;
use App\Services\FatoorahServices;
use App\Http\Resources\OrderResource;
use App\Http\Resources\shippingResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReturnOrderResource;
use App\Http\Controllers\api\BaseController as BaseController;

class ReturnOrderController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $success['ReturnOrders'] = ReturnOrderResource::collection(Order::with('returnOrders')->whereHas('items', function ($q) use($id) {
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
    public function update(Request $request, $id)
    {
        $return=ReturnOrder::where('id', $id)->first();
        if (is_null($return)) {
            return $this->sendError("'طلب الاسترجاع غير موجود", "ReturnOrder is't exists");
        }
        $order= $return->order_id;
        $order = Order::where('id', $order)->whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id);
        })->first();
     

        $shipping_companies = [
            1 => new AramexCompanyService(),
            5=> new OtherCompanyService()
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
                $success['shipping'] = $shipping->refundOrder( $order);
                $payment = Payment::where('orderID', $order->id)->first();
               
                foreach($returns as $returns)
                if ($payment != null) {
                   
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
                        $success['payment'] = $supplierCode;
                    }

                }
              
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
