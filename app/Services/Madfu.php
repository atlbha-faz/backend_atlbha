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
            'PlatformTypeId' => '1',
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
        return $token;
    }
}