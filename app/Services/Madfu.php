<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Store;
use \Illuminate\Support\Str;
use Illuminate\Http\Request;

class Madfu
{
    private $base_url;
    private $username;
    private $password;

    public function __construct()
    {
        $this->base_url = env('MADFU_BASE_URL');
        $this->username = env('MADFU_USERNAME');
        $this->password = env('MADFU_PASSWORD');
    }

    private function makeRequest($url, $body,$api_key, $app_code,$authorization, $header = [], $method = 'POST')
    {
        $client = new \GuzzleHttp\Client();
        $header = array_merge($header, [
            'APIKey' => $api_key,
            'Appcode' =>$app_code,
            'Authorization' =>$authorization,
            'PlatformTypeId' => '5',
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ]);
        $response = $client->request($method, $url, [
            'body' => json_encode($body),
            'headers' => $header,
        ]);

        return $response;

    }

    private function initToken($api_key, $app_code,$authorization,$uuid)
    {
        $url = $this->base_url . 'merchants/token/init';
        $body = ["uuid" => $uuid, "systemInfo" => "web"];
        $result = $this->makeRequest($url,$api_key, $app_code,$authorization, $body);
        return $result;
    }

    public function login($username, $password,$api_key, $app_code,$authorization, $uuid)
    {
        $token = $this->initToken($username, $password,$api_key, $app_code,$authorization,$uuid);
        $url = $this->base_url . 'Merchants/sign-in';
        $body = ["userName" => $username, "password" => $password];
        $login = $this->makeRequest($url, $body,$api_key, $app_code,$authorization, ['token' => json_decode($token->getBody()->getContents())->token]);
        return $login;
    }

    public function createOrder($token, $guest_order_data, $order, $order_details, $cancel_url)
    {
//        ["GuestOrderData"=>{"CustomerMobile":"50XXXXXXX","CustomerName":"Your Customer Name","Lang":"ar"},
//        "Order"=>{"Taxes":1.5,"ActualValue":12,"Amount":10.5,"MerchantReference":"15648-AAA"},
//        "OrderDetails"=>[{"productName":"Product Name","SKU":"Stock keeping unit","productImage":"product image url","count":5,"totalAmount":200}]];
        $url = $this->base_url . 'Merchants/Checkout/CreateOrder';

        $body = ["GuestOrderData" => $guest_order_data,
            "Order" => $order,
            "OrderDetails" => $order_details,
            "MerchantUrls" => ["Success" => $cancel_url . '/success', "Failure" => $cancel_url . '/failed', "Cancel" => $cancel_url]
        ];
        $order_db = Order::where('order_number', $order->MerchantReference)->first();
        $store=Store::where('id', $order_db->store_id)->first();
        $username =($store && $store->madfu_username) ?  $store->madfu_username :'wesam@faz-it.net';
        $password = ($store && $store->madfu_password) ? $store->madfu_password:'Welcome@123';
        $api_key=($store && $store->madfu_api_key) ? $store->madfu_api_key:'b55dd64-dc765-12c5-bcd5-4';
        $app_code=($store && $store->madfu_app_code) ? $store->madfu_app_code:'Atlbha';
        $authorization=($store && $store->madfu_authorization) ? $store->madfu_authorization:'Basic QXRsYmhhOlFVMU5UQVVOUzFOWFNTRQ==';
        
        return $this->makeRequest($url, $body,$api_key, $app_code,$authorization, ['token' => $token]);
    }

    public function calculateFees($orderid, $refundAmount)
    {
        $order = Order::where('payment_id',$orderid)->first();
        $store=Store::where('id', $order->store_id)->first();

        $username =($store && $store->madfu_username) ?  $store->madfu_username :'wesam@faz-it.net';
        $password = ($store && $store->madfu_password) ? $store->madfu_password:'Welcome@123';
        $api_key=($store && $store->madfu_api_key) ? $store->madfu_api_key:'b55dd64-dc765-12c5-bcd5-4';
        $app_code=($store && $store->madfu_app_code) ? $store->madfu_app_code:'Atlbha';
        $authorization=($store && $store->madfu_authorization) ? $store->madfu_authorization:'Basic QXRsYmhhOlFVMU5UQVVOUzFOWFNTRQ==';

        $token = json_decode($this->login($username, $password,$api_key, $app_code,$authorization, Str::random(8))->getBody()->getContents())->token;
        $url = $this->base_url . 'api/Refund/RefundFee/Calculate';
        $body = ["orderid" => $order->payment_id, "refundAmount" => $refundAmount];
        return $this->makeRequest($url,$api_key, $app_code,$authorization, $body, ['token' => $token]);
    }
    public function refund($orderid, $refundAmount,$refundFees)
    {
        $order = Order::find($orderid);
        $store=Store::where('id', $order->store_id)->first();
        $username =($store && $store->madfu_username) ?  $store->madfu_username :'wesam@faz-it.net';
        $password = ($store && $store->madfu_password) ? $store->madfu_password:'Welcome@123';
        $api_key=($store && $store->madfu_api_key) ? $store->madfu_api_key:'b55dd64-dc765-12c5-bcd5-4';
        $app_code=($store && $store->madfu_app_code) ? $store->madfu_app_code:'Atlbha';
        $authorization=($store && $store->madfu_authorization) ? $store->madfu_authorization:'Basic QXRsYmhhOlFVMU5UQVVOUzFOWFNTRQ==';

        $token = json_decode($this->login($username, $password,$api_key, $app_code,$authorization, Str::random(8))->getBody()->getContents())->token;
        $url = $this->base_url . 'api/Refund/Create';
        $body = ["orderid" => $order->payment_id, "refundAmount" => $refundAmount,'refundFees'=>$refundFees,'merchantReference'=>$order->order_number];
        return $this->makeRequest($url,$api_key, $app_code,$authorization, $body, ['token' => $token]);
    }
}