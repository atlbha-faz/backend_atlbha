<?php

namespace App\Http\Controllers\api;

use App\Models\Order;
use App\Models\Store;
use GuzzleHttp\Client;
use App\Services\Madfu;
use App\Models\MadfuLog;
use App\Mail\StoreInfoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreInfoRequest;
use App\Http\Requests\MadfuLoginRequest;
use App\Http\Requests\CreateOrderRequest;

class MadfuController extends BaseController
{
    public function login(MadfuLoginRequest $request)
    {
        $username = 'wesam@faz-it.net';
        $password = 'Welcome@123';
        $login_request = (new Madfu())->login($username, $password, $request->uuid);
        if ($login_request->getStatusCode() == 200) {
            $login_request = json_decode($login_request->getBody()->getContents());
            if (!$login_request->status) {
                return $this->sendError('', $login_request->message);
            }
            return $this->sendResponse(['status' => 200,
                'data' => $login_request], 'عملية ناجحة', 'Success process');
        } else {
            return $this->sendError('خطأ في العملية', 'process failed');
        }
    }

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
                    $client = new Client();
                    $response = $client->post('https://api.fayezbinsaleh.me/api/webhook', [
                        'json' => json_encode($request->all()),
                    ]);
                }
                $order->payment_status = "paid";
                $order->payment_id = $request->orderId;
                $order->save();
            }
        }
    }
    public function sendStoresInfo(StoreInfoRequest $request)
    {
        $store = Store::where('id', $request->store_id)->first();
        $data = [
            'Contact_name' => $request->name,
            'phonenumber' => $request->phonenumber,
            'email' =>$request->email,
            'store_name' => $request->store_name,
            'store_url' =>$request->store_url,
        ];
        Mail::mailer('stores_info')
            ->to('rawaa.faz.it@gmail.com')
            ->send(new StoreInfoMail($data));
           $store->update(['is_send'=>1]); 
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
}
