<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\MadfuLoginRequest;
use App\Models\MadfuLog;
use App\Models\Order;
use App\Services\Madfu;
use Illuminate\Http\Request;

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
            return $this->sendResponse(['token' => $login_request->token,
                'data' => $login_request], 'عملية ناجحة', 'Success process');
        } else {
            return $this->sendError('خطأ في العملية', 'process failed');
        }
    }

    public function createOrder(CreateOrderRequest $request)
    {

        $create_order = (new Madfu())->createOrder($request->token, json_decode($request->guest_order_data), json_decode($request->order), json_decode($request->order_details), $request->url);
        return $create_order;
        if ($create_order->getStatusCode() == 200) {
            $create_order = json_decode($create_order->getBody()->getContents());
            if (!$create_order->status) {
                return $this->sendError('', $create_order->message);
            }
            return $this->sendResponse(['token' => $create_order->token,
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
                $order->payment_status = "paid";
                $order->is_archive =0;
                $order->save();
            }
        }
    }
}
