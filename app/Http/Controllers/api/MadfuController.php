<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\MadfuLoginRequest;
use App\Http\Requests\StoreInfoRequest;
use App\Mail\StoreInfoMail;
use App\Models\MadfuLog;
use App\Models\Order;
use App\Models\Package;
use App\Models\Package_store;
use App\Models\Store;
use App\Services\Madfu;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MadfuController extends BaseController
{
    public function login(MadfuLoginRequest $request)
    {if ($request->store_id == "atlbhaPlatform") {
        $username = 'wesam@faz-it.net';
        $password = 'Welcome@123';
        $api_key = 'b55dd64-dc765-12c5-bcd5-4';
        $app_code = 'Atlbha';
        $authorization = 'Basic QXRsYmhhOlFVMU5UQVVOUzFOWFNTRQ==';
    } else {
        $store = Store::where('id', $request->store_id)->first();
        $username = ($store && $store->madfu_username) ? $store->madfu_username : 'wesam@faz-it.net';
        $password = ($store && $store->madfu_password) ? $store->madfu_password : 'Welcome@123';
        $api_key = ($store && $store->madfu_api_key) ? $store->madfu_api_key : 'b55dd64-dc765-12c5-bcd5-4';
        $app_code = ($store && $store->madfu_app_code) ? $store->madfu_app_code : 'Atlbha';
        $authorization = ($store && $store->madfu_authorization) ? $store->madfu_authorization : 'Basic QXRsYmhhOlFVMU5UQVVOUzFOWFNTRQ==';
    }
        $login_request = (new Madfu())->login($username, $password, $api_key, $app_code, $authorization, $request->uuid);
        if ($login_request->getStatusCode() == 200) {
            $login_request = json_decode($login_request->getBody()->getContents());
            if (!$login_request->status) {
                return $this->sendError('', $login_request->message);
            }
            return $this->sendResponse(['status' => 200,
                'data' => $login_request], 'عملية ناجحة', 'Success process');
        } else {
            return $this->sendError('خطأ في العملية', 'process failed');
        }}

    public function createOrder(CreateOrderRequest $request)
    {

        $create_order = (new Madfu())->createOrder($request->token, json_decode($request->guest_order_data), json_decode($request->order), json_decode($request->order_details), $request->url);
//        return $create_order;
        if ($create_order->getStatusCode() == 200) {
            $create_order = json_decode($create_order->getBody()->getContents());

            return $this->sendResponse(['status' => 200,
                'data' => $create_order], 'عملية ناجحة', 'Success process');
        } else {
            return $this->sendError('خطأ في العملية', 'process failed');
        }
    }

    public function webhook(Request $request)
    {
        MadfuLog::create(['request' => json_encode($request->all())]);
        if ($request->status) {
            if ($request->orderStatus == 125) {
                $order = Order::where('order_number', $request->MerchantReference)->first();
                if ($order == null) {
                    $payment = Package_store::where('paymentTransectionID', $request->MerchantReference)->orderBy('id', 'desc')->first();
                    if ($payment) {
                        $this->sendEmail($payment->id);
                        $this->updatePackage($payment->id);
                        $payment->payment_status = "paid";
                        $payment->save();
                    }
                } else {
                    $order->payment_status = "paid";
                    $order->payment_id = $request->orderId;
                    $order->save();
                }
            }
        }
    }
    public function sendStoresInfo(StoreInfoRequest $request)
    {
        $store = Store::where('id', $request->store_id)->first();
        $data = [
            'Contact_name' => $request->name,
            'phonenumber' => $request->phonenumber,
            'email' => $request->email,
            'store_name' => $request->store_name,
        ];
        Mail::mailer('stores_info')
            ->to('support@atlbha.sa')
            ->send(new StoreInfoMail($data));
        $store->update(['is_send' => 1]);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم الارسال بنجاح', 'send successfully');

    }

    public function refundFees(Request $request)
    {
        $fees = (new Madfu())->calculateFees($request->orderid, $request->refundAmount);
        if ($fees->getStatusCode() == 200) {
            return $this->sendResponse(['status' => 200,
                'data' => json_decode($fees->getBody()->getContents())], 'عملية ناجحة', 'Success process');
        } else {
            return $this->sendError('خطأ في العملية', 'process failed');
        }
    }

    public function refund(Request $request)
    {
        $refund = (new Madfu())->refund($request->orderid, $request->refundAmount, $request->refundFees);
        if ($refund->getStatusCode() == 200) {
            return $this->sendResponse(['status' => 200,
                'data' => json_decode($refund->getBody()->getContents())], 'عملية ناجحة', 'Success process');
        } else {
            return $this->sendError('خطأ في العملية', 'process failed');
        }

    }
    public function sendEmail($id)
    {
        $package_store = Package_store::where('id', $id)->first();
        $store = Store::where('id', $package_store->store_id)->first();
        $package = Package::where('id', $package_store->package_id)->first();
        $firstCategory = $store->categories->first();
        if ($firstCategory) {
            $categoryName = $firstCategory->name;
        } else {
            $categoryName = 'No category';
        }
        $data = array(
            'name' => $store->owner_name,
            'email' => $store->store_email,
            'phonenumber' => $store->phonenumber,
            'package' => $package->name,
            'country' => $store->country->name ?? "no data",
            'nationality' => $store->country->name ?? "no data",
            'area' => $store->city->name ?? "no data",
            'specialization' => $categoryName,
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
            'end_at' => $end_at,
        ]);

        $package_store->update([
            'start_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_at' => $end_at]);
        $subscriptions = Package_store::where('store_id', $store->id)->whereNot('payment_status','paid')->get();
        if ($subscriptions) {
            foreach ($subscriptions as $subscription) {
                $subscription->delete();
            }
        }

    }

}
