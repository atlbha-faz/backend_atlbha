<?php
namespace App\Services;

use CURLFile;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class FatoorahServices
{
    private $base_url;
    private $token;
    private $headers;

    public function __construct()
    {

        $this->base_url = env('fatoora_base_url', 'https://apitest.myfatoorah.com/');
        $this->token = env("fatoora_token", "WVjz6cCoT9N7ZlqmZBMPANjkh30UvH9uwB20g-xGJi8tOjADM9NWiKaasfQCwkGLBy0ZNzoRXYCwMoLzaNwNgvY9H_3pO0bfTfwcEBCHe4s7FDQ0oGYFdMj8UwECAoiV4_3buuymrCmvdzc6QmZZuPsNfFCPg0vtwanErRHxM975FhaReP5QZsp6cU5bE8zupH5qOL7y8Hb9kSTW4u4ffx5V0WqUTrL2GmBWmAhx4eZBqoppO-jxG93E_FU7drhRA8SxiH__pNDDj3RJXBFqbLjzLjQ0iLtvR4s-c7X_dcLWXCj0X5lCBxVPFquo7Fsosbv5-NHJ-8nygVqy-HhwqnN3CV5HRD005E34zf32K8Y0eC526P2wZer5U-jr275rPEtfotn2wFFuDcWWnuT5f37p7oLDOgb2BclrmSj5crn5BxtkbG7awbxR7yVXoW-q19oE6-mcQWoNX8vdSbbdJ8arugLsR6qKW15juYZ8LztWfwnq65rRPZdh_JuE3KO96_rQR72a90FXy0mxjuXWmQU94Nek-2X_9DyecBqPxANjFwotZRNybG353CZchyvyJ60WjhVlfmxLMCdTD6wsBB5Ew5xKNru_jdG5TsshtNUgiogmf4FvJ0M8R3Xlxv98z5VXkqYytzBEtk2rbCwFopar9Ejj2Dwun5YaT5xyllcXrJltQ4UwofJ9j-bislP57_wQZCSachFOs2BaXqnRJEMb8sf6QeRm06TV-F4x7-iGJ-z7");
        $this->headers = [
            "Content-Type" => 'application/json',
            "authorization" => 'Bearer ' . env("fatoora_token", "WVjz6cCoT9N7ZlqmZBMPANjkh30UvH9uwB20g-xGJi8tOjADM9NWiKaasfQCwkGLBy0ZNzoRXYCwMoLzaNwNgvY9H_3pO0bfTfwcEBCHe4s7FDQ0oGYFdMj8UwECAoiV4_3buuymrCmvdzc6QmZZuPsNfFCPg0vtwanErRHxM975FhaReP5QZsp6cU5bE8zupH5qOL7y8Hb9kSTW4u4ffx5V0WqUTrL2GmBWmAhx4eZBqoppO-jxG93E_FU7drhRA8SxiH__pNDDj3RJXBFqbLjzLjQ0iLtvR4s-c7X_dcLWXCj0X5lCBxVPFquo7Fsosbv5-NHJ-8nygVqy-HhwqnN3CV5HRD005E34zf32K8Y0eC526P2wZer5U-jr275rPEtfotn2wFFuDcWWnuT5f37p7oLDOgb2BclrmSj5crn5BxtkbG7awbxR7yVXoW-q19oE6-mcQWoNX8vdSbbdJ8arugLsR6qKW15juYZ8LztWfwnq65rRPZdh_JuE3KO96_rQR72a90FXy0mxjuXWmQU94Nek-2X_9DyecBqPxANjFwotZRNybG353CZchyvyJ60WjhVlfmxLMCdTD6wsBB5Ew5xKNru_jdG5TsshtNUgiogmf4FvJ0M8R3Xlxv98z5VXkqYytzBEtk2rbCwFopar9Ejj2Dwun5YaT5xyllcXrJltQ4UwofJ9j-bislP57_wQZCSachFOs2BaXqnRJEMb8sf6QeRm06TV-F4x7-iGJ-z7"),
        ];
    }

    public function buildRequest($url, $mothod, $data = [])
    {
        $client = new Client();
        if (empty($data)) {
            $request = new Request($mothod, $this->base_url . $url, $this->headers);
        } else {
            $request = new Request($mothod, $this->base_url . $url, $this->headers, $data);
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
