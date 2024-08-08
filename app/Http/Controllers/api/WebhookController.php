<?php

namespace App\Http\Controllers\api;

use Exception;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Store;
use GuzzleHttp\Client;
use App\Mail\SendMail2;
use App\Models\Account;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\MyfatoorahLog;
use App\Models\Package_store;
use Illuminate\Support\Facades\Mail;
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
            $package_store = Package_store::where('paymentTransectionID', $request->input('Data.InvoiceId'))->first();
            $payment = Payment::where('paymentTransectionID', $request->input('Data.InvoiceId'))->first();
            $order = Order::where('id', $payment->orderID)->first();
            $cart = Cart::where('order_id', $payment->orderID)->first();
            switch ($request->input('Data.TransactionStatus')) {
                case "SUCCESS":
                    if ($package_store) {
                        $package_store->update([
                            'payment_status' => "paid",
                        ]);
                        $this->sendEmail($package_store->id);
                        $this->updatePackage($package_store->id);
                    } else {
                        $order->update([
                            'payment_status' => "paid",
                        ]);
                        $payment->update([
                            'paymentCardID' => $request->input('Data.PaymentId'),
                        ]);
                        $cart->delete();
                        if ($order->store_id !== null) {
                            $store = Store::where('id', $order->store_id)->first();
                            $data = [
                                'subject' => "طلب جديد",
                                'message' => "تم وصول طلب جديد برقم " . $order->order_number . " لدى متجركم",
                                'store_id' => $store->store_name,
                                'store_email' => $store->store_email,
                            ];
                            Mail::to($store->store_email)->send(new SendMail2($data));
                        }
                    }
                    break;
                case "FAILED":
                    if ($package) {
                        $package->update([
                            'payment_status' => "failed",
                        ]);
                    } else {
                        $order->update([
                            'payment_status' => "failed",
                        ]);
                    }
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
                        'comment' => null,
                    ]);
                    break;
                case "Active":
                    $account->update([
                        'status' => "APPROVED",
                        'comment' => null,
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
    public function sendEmail($id)
    {
        $package_store = Package_store::where('id', $id)->first();
        $store = Store::where('id', $package_store->store_id)->first();
        $package = Package::where('id', $package_store->package_id)->first();
        $data = array(
            'name' => $store->owner_name,
            'email' => $store->store_email,
            'phonenumber' => $store->phonenumber,
            'package' => $package->name,
            'country' => $store->country->name,
            'nationality' => $store->country->name,
            'area' => $store->city->name,
            'specialization' => $store->categories->first()->name,
            'type' => $store->package_id == 1 ? 'china' : 'dubai',
        );
        $client = new Client();
        $response = $client->post('https://api.fayezbinsaleh.me/api/sendEmail', [
            'headers' => [
                "Content-Type" => 'application/json'],
            'json' => $data,
        ]);
        try
        {
            $response = json_decode($response->getBody(), true);
        } catch (Exception $e) {
            return ("Error: " . $e->getMessage());
        }

    }
    public function updatePackage($id)
    {
        $package_store = Package_store::where('id', $id)->first();
        $store = Store::where('id', $package_store->store_id)->first();
        $end_at = Carbon::now()->addYear()->format('Y-m-d H:i:s');
        $store->update([
            'package_id' => $package_store->package_id,
            'periodtype' => 'year',
            'start_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_at' => $end_at
        ]);
        
        $package_store->update([
            'start_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_at' => $end_at]);
            

    }

}
