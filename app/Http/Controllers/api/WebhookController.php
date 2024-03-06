<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends BaseController
{

    public function validateSignature($body, $secret, $MyFatoorah_Signature)
    {

        if ($body['Event'] == 'RefundStatusChanged') {
            unset($body['Data']['GatewayReference']);
        }

        if ($body['Event'] == 'SupplierStatusChanged') {
            unset($body['Data']['KycFeedback']);
        }

        $data = $body['Data'];

        //1- Order all data properties in alphabetic and case insensitive.
        uksort($data, 'strcasecmp');

        //2- Create one string from the data after ordering it to be like that key=value,key2=value2 ...
        $orderedData = implode(',',
            array_map(function ($v, $k) {
                return sprintf("%s=%s", $k, $v);
            },
                $data,
                array_keys($data)
            ));

        //4- Encrypt the string using HMAC SHA-256 with the secret key from the portal in binary mode.
        //Generate hash string
        $result = hash_hmac('sha256', $orderedData, $secret, true);

        //5- Encode the result from the previous point with base64.
        $hash = base64_encode($result);

        error_log(PHP_EOL . date('d.m.Y h:i:s') . ' - Generated Signature  - ' . $hash, 3, './webhook.log');
        error_log(PHP_EOL . date('d.m.Y h:i:s') . ' - MyFatoorah-Signature - ' . $MyFatoorah_Signature, 3, './webhook.log');

        //6- Compare the signature header with the encrypted hash string. If they are equal, then the request is valid and from the MyFatoorah side.
        if ($MyFatoorah_Signature === $hash) {
            error_log(PHP_EOL . date('d.m.Y h:i:s') . ' - Signature is valid ', 3, './webhook.log');
            return true;
        } else {
            error_log(PHP_EOL . date('d.m.Y h:i:s') . ' - Signature is not valid ', 3, './webhook.log');
            exit;
        }
    }
    public function handleWebhook(Request $request)
    {
        $allData = $request->input('Data');
        if ($allData != null) {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json; charset=UTF-8");
            header("Access-Control-Allow-Methods: POST");
            header("Access-Control-Max-Age: 3600");
            header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

            //get MyFatoorah-Signature from request headers
            $request_headers = apache_request_headers();
            $MyFatoorah_Signature = $request_headers['MyFatoorah-Signature'];
            $secret = "snLLm1lSOhrSDobmSGrALBIjNapQA2/C7P9rKcHHijzbb38GHsgWu3mGUpyvH+mVhDdT7GHetfd7bRskIUcUvA==";
            $signature = $request->header('MyFatoorah-Signature');
            $body = (file_get_contents("php://input"));
            $data = json_decode($body, true);
            if (!($this->validateSignature($data, $secret,  $MyFatoorah_Signature))) {
                Log::debug('Webhook payload:', $MyFatoorah_Signature);
                return;
            }
            $event = $request->input('EventType');
            Log::debug('Webhook payload:', $event);
            if ($event == 1) {
                $payment = Payment::where('paymentTransectionID', $request->input('Data.InvoiceId'))->first();
                $order = Order::where('id', $payment->orderID)->first();
                if ($request->input('Data.TransactionStatus') == "SUCCESS") {
                    $order->update([
                        'payment_status' => "Paid",
                    ]);

                    // Log::debug('Webhook payload:', $event);
                }

            }
        }

    }

}
