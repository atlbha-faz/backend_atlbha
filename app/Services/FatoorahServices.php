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

        $this->base_url = env('fatoora_base_url', 'https://api-sa.myfatoorah.com/');
        $this->token = env("fatoora_token", "-VPCuFhmYQ8TsTR26lm9sMFThPxz8fLm7yuc_1XJlV9tAVwzD-M0PCDgmQ9-NdBpVMIEF6vpzfKFnPlrfQVAdqUVzCvSLV1yCeVaIwTFzvHq6eatdSnidxZY8P_K9fddQS0izedOmpv9Tm4ghOWywfQ8vyrTpJwDspwm5tU9nkND_1CNt7Ljr54tRRhYMESeIQPckHBzXgTvbgHG49HrzKtd32ILNDiWJDeSHepR7dFq9PM4hI76iCy7U7sNxY79wv-JlJBNclKq-pVMypZNXzxVzlJb40bN3K6kZAd_qO0gMXXzAHG9Yy7SUSzd3mYpNaqgtzp30Qaq5IjjqNom6y6_v8cajlI6xcuv78PpJnfJ8gVWkAgm5FAXuFDxT3MJ-k2rWEsRZV_ji4IWMHEofJEEdxc5NPcLb6ZEe2FxpogP0wvu_RZGCPr30bP4VeF0_CYUUUJg2GISv6eSBon2p-c-hn0K8hrvya5fu7oveVlq1uCW_czSjG5g9vnIZpP1NWKFv_nDSJRJN68o5hcimkoQVifl3EkQ08kkcQjoFVgAfYketKWuRmeHRGkFdHRNfi9Gi0RH2Yq9Sya_T-mgDzd23ka2NHE-6f5tb1LYw6Aig753DDtKhFNQGb34Ffyg7npnFV-7HJAnBYO2KK2iRT_JltS6oHPaHgyhK-mfydM-tc60Qif3K9L3R_jzvpKfZlo7wtkUOxLvyztE-tv_cR0BAhddfO0kYQ11ltY19Phx9-ap");
        $this->headers = [
            "Content-Type" => 'application/json',
            "authorization" => 'Bearer ' . env("fatoora_token", "-VPCuFhmYQ8TsTR26lm9sMFThPxz8fLm7yuc_1XJlV9tAVwzD-M0PCDgmQ9-NdBpVMIEF6vpzfKFnPlrfQVAdqUVzCvSLV1yCeVaIwTFzvHq6eatdSnidxZY8P_K9fddQS0izedOmpv9Tm4ghOWywfQ8vyrTpJwDspwm5tU9nkND_1CNt7Ljr54tRRhYMESeIQPckHBzXgTvbgHG49HrzKtd32ILNDiWJDeSHepR7dFq9PM4hI76iCy7U7sNxY79wv-JlJBNclKq-pVMypZNXzxVzlJb40bN3K6kZAd_qO0gMXXzAHG9Yy7SUSzd3mYpNaqgtzp30Qaq5IjjqNom6y6_v8cajlI6xcuv78PpJnfJ8gVWkAgm5FAXuFDxT3MJ-k2rWEsRZV_ji4IWMHEofJEEdxc5NPcLb6ZEe2FxpogP0wvu_RZGCPr30bP4VeF0_CYUUUJg2GISv6eSBon2p-c-hn0K8hrvya5fu7oveVlq1uCW_czSjG5g9vnIZpP1NWKFv_nDSJRJN68o5hcimkoQVifl3EkQ08kkcQjoFVgAfYketKWuRmeHRGkFdHRNfi9Gi0RH2Yq9Sya_T-mgDzd23ka2NHE-6f5tb1LYw6Aig753DDtKhFNQGb34Ffyg7npnFV-7HJAnBYO2KK2iRT_JltS6oHPaHgyhK-mfydM-tc60Qif3K9L3R_jzvpKfZlo7wtkUOxLvyztE-tv_cR0BAhddfO0kYQ11ltY19Phx9-ap"),
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
          CURLOPT_URL => 'https://api-sa.myfatoorah.com/v2/ExecutePayment',
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
            'Authorization: Bearer -VPCuFhmYQ8TsTR26lm9sMFThPxz8fLm7yuc_1XJlV9tAVwzD-M0PCDgmQ9-NdBpVMIEF6vpzfKFnPlrfQVAdqUVzCvSLV1yCeVaIwTFzvHq6eatdSnidxZY8P_K9fddQS0izedOmpv9Tm4ghOWywfQ8vyrTpJwDspwm5tU9nkND_1CNt7Ljr54tRRhYMESeIQPckHBzXgTvbgHG49HrzKtd32ILNDiWJDeSHepR7dFq9PM4hI76iCy7U7sNxY79wv-JlJBNclKq-pVMypZNXzxVzlJb40bN3K6kZAd_qO0gMXXzAHG9Yy7SUSzd3mYpNaqgtzp30Qaq5IjjqNom6y6_v8cajlI6xcuv78PpJnfJ8gVWkAgm5FAXuFDxT3MJ-k2rWEsRZV_ji4IWMHEofJEEdxc5NPcLb6ZEe2FxpogP0wvu_RZGCPr30bP4VeF0_CYUUUJg2GISv6eSBon2p-c-hn0K8hrvya5fu7oveVlq1uCW_czSjG5g9vnIZpP1NWKFv_nDSJRJN68o5hcimkoQVifl3EkQ08kkcQjoFVgAfYketKWuRmeHRGkFdHRNfi9Gi0RH2Yq9Sya_T-mgDzd23ka2NHE-6f5tb1LYw6Aig753DDtKhFNQGb34Ffyg7npnFV-7HJAnBYO2KK2iRT_JltS6oHPaHgyhK-mfydM-tc60Qif3K9L3R_jzvpKfZlo7wtkUOxLvyztE-tv_cR0BAhddfO0kYQ11ltY19Phx9-ap',
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
