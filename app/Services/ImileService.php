<?php
namespace App\Services;

class ImileService{
    public function addClient(array $data){
        $newdata=json_encode($data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://dashboard.go-tex.net/gotex-co-test/imile/add-client',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>  $newdata,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
                     
              curl_close($curl);
              return json_decode($response);
    }

    ///////////////////////////////////////////////////////////////////
    public function createOrder(array $data){
      $newdata=json_encode($data);
      $curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://dashboard.go-tex.net/gotex-co-test/imile/create-user-order',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$newdata,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
 return json_decode($response);
    }

}
?>