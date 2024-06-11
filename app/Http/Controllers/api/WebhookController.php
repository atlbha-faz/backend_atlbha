<?php

namespace App\Http\Controllers\api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Account;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\MyfatoorahLog;
use App\Http\Controllers\api\BaseController as BaseController;

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

        //6- Compare the signature header with the encrypted hash string. If they are equal, then the request is valid and from the MyFatoorah side.
        if ($MyFatoorah_Signature === $hash) {

            return true;
        } else {

            exit;
        }
    }

    public function handleWebhook(Request $request)
    {
         $allData = $request->input('Data');
        // if ($allData != null) {

            //get MyFatoorah-Signature from request headers
            $MyFatoorah_Signature = $request->header('MyFatoorah-Signature');

            // $MyFatoorah_Signature = $request_headers['MyFatoorah-Signature'];
            $secret = env("secret");

            $body = $request->all();

            // if (!($this->validateSignature($body, $secret, $MyFatoorah_Signature))) {
            //     return;
            // }
            $myfatoorahLog = new MyfatoorahLog();
            $myfatoorahLog->request = json_encode($body);
            $myfatoorahLog->save();
            $event = $request->input('EventType');

            if ($event == 1) {
                $payment = Payment::where('paymentTransectionID', $request->input('Data.InvoiceId'))->first();
                $order = Order::where('id', $payment->orderID)->first();
                $cart = Cart::where('order_id', $payment->orderID)->first();
                switch ($request->input('Data.TransactionStatus')) {
                    case "SUCCESS":
                        $order->update([
                            'payment_status' => "paid",
                        ]);
                        $payment->update([
                            'paymentCardID' => $request->input('Data.PaymentId'),
                        ]);
                        $cart->delete();
                        break;
                    case "FAILED":
                        $order->update([
                            'payment_status' => "failed",
                        ]);
                        break;
                    case "CANCELED":
                        $order->update([
                            'payment_status' => "failed",
                        ]);
                        break;
                    default:
                        $order->update([
                            'payment_status' => "pending",
                        ]);
                }

            } elseif ($event == 4) {
                $account = Account::where('supplierCode', $request->input('Data.SupplierCode'))->first();
                switch ($request->input('Data.SupplierStatus')) {
                    case "APPROVED":
                        $account->update([
                            'status' => "APPROVED",
                            'comment'=>null
                        ]);
                        break;
                    case "Active":
                        $account->update([
                            'status' => "APPROVED",
                            'comment'=>null
                        ]);
                        break;
                    case "REJECTED":
                        $account->update([
                            'status' => "REJECTED",
                            'comment' => $request->input('Data.KycFeedback.Comments'),
                        ]);
                        break;
                    default:
                        $account->update([
                            'status' => "Pending",
                        ]);
                }
            }
        // }

    }

}

