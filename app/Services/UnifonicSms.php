<?php
namespace App\Services;

use CURLFile;

use App\ModelTax;
use GuzzleHttp\Client;

use GuzzleHttp\Psr7\Request;
class UnifonicSms
{
    private $base_url;
    private $AppSid;
    public function __construct()
    {

        $this->base_url = env('unifonic_base_url', 'https://el.cloud.unifonic.com/rest/SMS/messages');
        $this->AppSid = env("AppSid", "3x6ZYsW1gCpWwcCoMhT9a1Cj1a6JVz");
    
    }
    public function buildRequest($mothod, $data=[] ){
        $client = new Client(); 
        $response = $client->post('https://el.cloud.unifonic.com/rest/SMS/messages', [
            'form_params' => $data,
        ]);
          if ($response->getStatusCode() != 200)
              return false;
          $response = json_decode ($response->getBody (),true);
          return $response;
      }
    
   
}