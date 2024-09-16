<?php

namespace App\Http\Controllers\api\homePages;

use App\Models\Service;
use App\Models\Paymenttype;
use App\Models\Websiteorder;
use App\Services\FatoorahServices;
use App\Http\Controllers\api\BaseController;
use App\Http\Requests\EtlobhaServiceRequest;

class ServiceController extends BaseController
{
    public function checkout(EtlobhaServiceRequest $request)
    {
        $number = $this->generateOrderNumber();
        $totalPrice = $this->calculateTotalPrice($request->service_id);
        $websiteorder = Websiteorder::create([
            'type' => 'service',
            'order_number' => str_pad($number, 4, '0', STR_PAD_LEFT),
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'total_price' => $totalPrice,

        ]); 
        $websiteorder->services()->attach($request->service_id);
        $paymentype = Paymenttype::where('id', $request->paymentype_id)->first();
        if (in_array($request->paymentype_id, [1, 2])) {

            $processingDetails = [
                "AutoCapture" => true,
                "Bypass3DS" => false,
            ];
            $processingDetailsobject = (object) ($processingDetails);
        
            if ($totalPrice == 0) {
                return $this->sendError("يجب ان يكون المبلغ اكبر من الصفر", "price must be more than zero");
            }
            $data = [
                "PaymentMethodId" => $paymentype->paymentMethodId,
                "CustomerName" => $websiteorder->name,
                "InvoiceValue" => $websiteorder->total_price, // total_price
                "CustomerEmail" => $websiteorder->email,
                "CustomerMobile"=>substr($websiteorder->phone_number, 4),
                "CallBackUrl" => 'https://store.atlbha.com/checkout-packages/success',
                "ErrorUrl" => 'https://store.atlbha.com/checkout-packages/failed',
                "Language" => 'AR',
                "DisplayCurrencyIso" => 'SAR',
                "ProcessingDetails" => $processingDetailsobject,
            ];
            $data = json_encode($data);
            $payment_process = new FatoorahServices();
            $response = $payment_process->buildRequest('v2/ExecutePayment', 'POST', $data);

            if (isset($response['IsSuccess'])) {
                if ($response['IsSuccess'] == true) {

                    $InvoiceId = $response['Data']['InvoiceId']; // save this id with your order table
                    $success['payment'] = $response;
                    $websiteorder->update([
                        'payment_method' => $paymentype->name,
                        'paymentTransectionID' => $InvoiceId,
                    ]);

                } else {
                    $success['payment'] = $response;
                }
            } else {
                $success['payment'] = $response;
            }
        } else {

            $websiteorder->update([
                'payment_method' => $paymentype->name,
                'paymentTransectionID' => $request->service_reference,
            ]);
        }
        $success['websiteorder'] = new WebsiteorderResource($websiteorder);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم ارسال الطلب بنجاح', 'order send successfully');

        }
    
    private function generateOrderNumber()
    {
        $order_number = Websiteorder::orderBy('id', 'desc')->first();
        if (is_null($order_number)) {
            $number = 0001;
        } else {

            $number = $order_number->order_number;
            $number = ((int) $number) + 1;
        }
        return $number;
    }
    private function calculateTotalPrice(array $serviceIds)
{
 
    return Service::whereIn('id', $serviceIds)->sum('price');
}
}
