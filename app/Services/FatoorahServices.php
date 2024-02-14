<?php
namespace App\Services;

use CURLFile;

// use App\ModelTax;
// use GuzzleHttp\Client;

// use GuzzleHttp\Psr7\Request;
class FatoorahServices
{
    private $base_url;
    private $token;
    private $headers;

    public function __construct()
    {

        $this->base_url = env('fatoora_base_url', 'https://apitest.myfatoorah.com/');
        $this->token = env("fatoora_token", "rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL");
        $this->headers = [
            "Content-Type" => 'application/json',
            "authorization" => 'Bearer ' . env("fatoora_token", "rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL"),
        ];
    }

    // public function buildRequest($url,$mothod, $data =[]){
    //     $request = new Request($mothod , $this->base_url.$url, $this->headers);
    //     if (!$data)
    //         return false;
    //     $response = $this->request_client->send($request, ['json'=>$data]);
    //     if ($response->getStatusCode() != 200)
    //         return false;
    //     $response = json_decode ($response->getBody (),true);
    //     return $response;
    // }

    // public function sendPayment($data){
    //     $response  = $this->buildRequest('v2/SendPayment','POST', $data);
    //     return $response;
    // }
    // public function getPaymentStatus($data){
    //     $response  = $this->buildRequest('v2/getPaymentStatus','POST', $data);
    //     return $response;
    // }

    public function callAPI($endpointURL, $apiKey, $postFields = [])
    {
        $curl = curl_init($endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postFields),
            CURLOPT_HTTPHEADER => array("Authorization: Bearer $apiKey", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));
        $response = curl_exec($curl);
        $curlErr = curl_error($curl);
        curl_close($curl);
        return $response;
    }
    public function getBank($url)
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
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array("Authorization: Bearer $this->token", 'Content-Type: application/json'),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);

    }
    public function createSupplier($url, $data)
    {
         $endpointURL=$this->base_url.$url;
        $curl = curl_init($endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }
    public function executePayment($url, $data)
    {

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apitest.myfatoorah.com/v2/ExecutePayment',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($data),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL',
            'Cookie: ApplicationGatewayAffinity=61939aeb6b7c5f38617144d210b01e24; ApplicationGatewayAffinityCORS=61939aeb6b7c5f38617144d210b01e24'
          ),
        ));
        
        $response = curl_exec($curl);
        
   
        
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);

    }
    public function getSupplierDashboard($url)
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
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->token
            ),
                ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);

    }
    public function uploadSupplierDocument($url,$data){
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
          CURLOPT_POSTFIELDS => array('FileUpload'=>new CURLFILE($data['FileUpload']),'FileType' => $data['FileType'],'SupplierCode' =>$data['SupplierCode']),
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$this->token
        ),
        ));
        
        $response = curl_exec($curl); 
        curl_close($curl);
        return json_decode($response);

    }

}
