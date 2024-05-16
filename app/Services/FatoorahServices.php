<?php
namespace App\Services;

use CURLFile;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

class FatoorahServices
{
    private $base_url;
    private $token;
    private $headers;

    public function __construct()
    {

        $this->base_url = env('fatoora_base_url');
        $this->token = env("fatoora_token");
        $this->headers = [
            "Content-Type" => 'application/json',
            "authorization" => 'Bearer ' . env("fatoora_token"),
        ];
    }

    public function buildRequest($url, $mothod, $data = [],$logo=null)
    {
        $client = new Client();
        if (empty($data)) {
            $request = new Request($mothod, $this->base_url . $url, $this->headers);
        } else {
            if($logo != null)
            {
         
              $options = [
                'multipart' => [
                  [
                    'name' => 'SupplierName',
                    'contents' => $data['SupplierName']
                  ],
                  [
                    'name' => 'Mobile',
                    'contents' => $data['Mobile']
                  ],
                  [
                    'name' => 'Email',
                    'contents' => $data['Email']
                  ],
                  [
                    'name' => 'DepositTerms',
                    'contents' =>  $data['DepositTerms']
                  ],
                  [
                    'name' => 'BankId',
                    'contents' =>  $data['BankId']
                  ],
                  [
                    'name' => 'BankAccountHolderName',
                    'contents' =>  $data['BankAccountHolderName']
                  ],
                  [
                    'name' => 'BankAccount',
                    'contents' =>  $data['BankAccount']
                  ],
                  [
                    'name' => 'logoFile',
                    'contents' => Utils::tryFopen( $data['logo'], 'r'),
                    'filename' =>  $data['logo'],
                    'headers'  => [
                      'Content-Type' => '<Content-type header>'
                    ]
                  ],
                  [
                    'name' => 'BusinessName',
                    'contents' =>  $data['BusinessName']
                  ]
              ]];
              $request = new Request($mothod, $this->base_url . $url, $this->headers);
              $res = $client->sendAsync($request, $options)->wait();
              $response = json_decode($res->getBody(), true);
              return $response;
              
            }
            else{
                $request = new Request($mothod, $this->base_url . $url, $this->headers, $data);
            }
        }
        $response = $client->sendAsync($request)->wait();
        if ($response->getStatusCode() != 200) {
            return false;
        }

        $response = json_decode($response->getBody(), true);
        return $response;
    }
  
    public function uploadSupplierDocument($url, $data)
    {
        $endpointURL = $this->base_url . $url;
        $curl = curl_init($endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => array('FileUpload' => new CURLFILE($data['FileUpload']), 'FileType' => $data['FileType'], 'SupplierCode' => $data['SupplierCode']),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->token,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);

    }
    public function refund( $url,$mothod, $data=[] ){
        try {
        $client = new Client(); 
        $response = $client->post( $this->base_url.$url, [
            'headers' => $this->headers,
            'json' => $data,
        ]);
          if ($response->getStatusCode() != 200)
              return false;
          $response = json_decode ($response->getBody (),true);
          return $response;
        } catch (\Exception $e) {
         
            return false;
        }
      }

}
