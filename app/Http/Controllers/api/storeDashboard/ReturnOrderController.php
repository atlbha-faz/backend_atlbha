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
    public function index($id)
    {
        $success['ReturnOrders'] = ReturnOrderResource::collection(Order::with('returnOrders')->whereHas('items', function ($q) use($id) {
            $q->where('store_id', $id)->where('is_return', 1);
        })->where('user_id', auth()->user()->id)->where('store_id', $id)->get());
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
    public function update(Request $request, returnOrder $returnOrder)
    {
        if ($request->status === "refund") {
            if ($order->shippingtype->id == 5) {
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
             else {
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
                $success['orders'] = new OrderResource($order);
                $success['status'] = 200;
                return $this->sendResponse($success, 'تم تعديل الطلب', 'order update successfully');
            }
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
