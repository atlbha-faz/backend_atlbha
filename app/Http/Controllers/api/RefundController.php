<?php

namespace App\Http\Controllers\api;

use App\Models\ReturnOrder;
use Illuminate\Http\Request;
use App\Services\FatoorahServices;
use App\Models\Payment;
use Exception;
use GuzzleHttp\Exception\ClientException;
use App\Http\Controllers\api\BaseController as BaseController;

class RefundController  extends BaseController
{
    public function refundCallback(Request $request)
    {
      
        $postFields = [
            'Key' => $request->paymentId,
            'KeyType' => 'paymentId',
        ];
        $payment = new FatoorahServices();
        try {
            $response = $payment->buildRequest('v2/GetPaymentStatus', 'POST', json_encode($postFields));
        } catch (ClientException $e) {
            return $this->sendError("حدث خطأ", 'Message: ' . $e->getMessage());
        }
        if ($response['IsSuccess'] == true) {
            if ($response['Data']['InvoiceStatus'] == "Paid") {
                $success['response'] = $response;
                $return = ReturnOrder::where('invoice_id', $response['Data']['InvoiceId'])->first();
                $refund_result= $this->refundOrder($return->order_id);
              if($refund_result){
                $success['$refund_status']= true;
              }
              else{
                $success['$refund_status']= false;
              }
                return $this->sendResponse($success, 'تم ارجاع الطلب ', 'returned successfully');
            }
        } else {
            return $this->sendError('خطأ في العملية', 'process failed');
        } 
    }
    public function refundOrder($id)
    {

        $returns = ReturnOrder::where('order_id', $id)->get();
        $prices=0;
        foreach ($returns as $return) {
            $prices = $prices + ($return->qty * $return->orderItem->price);
        }
        $payment = Payment::where('orderID', $id)->first();
        $data = [
            "Key" => $payment->paymentTransectionID,
            "KeyType" => "invoiceid",
            "RefundChargeOnCustomer" => false,
            "ServiceChargeOnCustomer" => false,
            "Amount" => $prices,
            "Comment" => "refund to the customer",
            "AmountDeductedFromSupplier" => 0,
            "CurrencyIso" => "SAR",
        ];

        $supplier = new FatoorahServices();

        $response = $supplier->refund('v2/MakeRefund', 'POST', $data);
        if ($response) {
            if ($response['IsSuccess'] == false) {
              return false;
            } else {
                $returns = ReturnOrder::where('order_id', $id)->get();
                foreach ($returns as $return) {
                    $return->update([
                        'refund_status' => 1,
                    ]);
                }
                return true;
            }
        } else {
            return false;
        }  

    }

}
