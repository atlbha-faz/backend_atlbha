<?php

namespace App\Services;

class Madfu
{
    private $base_url;

    public function __construct()
    {
        $this->base_url = env('MADFU_BASE_URL');
    }

    private function makeRequest($url, $body, $header = [], $method = 'POST')
    {
        $client = new \GuzzleHttp\Client();
        $header = array_merge($header, [
            'APIKey' => ' b55dd64-dc765-12c5-bcd5-4',
            'Appcode' => 'Atlbha',
            'Authorization' => 'Basic QXRsYmhhOlFVMU5UQVVOUzFOWFNTRQ==',
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

    private function initToken($uuid)
    {
        $url = $this->base_url . 'merchants/token/init';
        $body = ["uuid" => $uuid, "systemInfo" => "web"];
        $result = $this->makeRequest($url, $body);
        return $result;
    }

    public function login($username, $password, $uuid)
    {
        $token = $this->initToken($uuid);
        $url = $this->base_url . 'Merchants/sign-in';
        $body = ["userName" => $username, "password" => $password];
        $login = $this->makeRequest($url, $body, ['token' => json_decode($token->getBody()->getContents())->token]);
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
            "MerchantUrls" => ["Success" => $cancel_url.'/success', "Failure" => $cancel_url.'/failed', "Cancel" => $cancel_url]
        ];

        return $this->makeRequest($url, $body, ['token' => $token]);
    }
}