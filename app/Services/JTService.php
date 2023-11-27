<?php
namespace App\Services;

class JTService{
    public function createOrder(array $data){

        $newdata=json_encode($data);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://dashboard.go-tex.net/gotex-co-test/jt/create-user-order',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $newdata,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5YTU5ZTU3Yy01ZDg4LTQxZWUtOWY0OC05ZDc1NDlmZDgyODQiLCJqdGkiOiJjZTQzZmFhYTQ5NDcwN2Y5MDI3N2FkNzAwODcwOGVjZjFlYzgwNmE2N2Q0Mjc2OWY2NzUzMDAzMjc5NGU2OGQ2ZGI3MzBmYTljOWRiY2VlOCIsImlhdCI6MTY5NzEwOTc1OC45OTgyMiwibmJmIjoxNjk3MTA5NzU4Ljk5ODIyNSwiZXhwIjoxNzI4NzMyMTU4Ljk4ODUyNCwic3ViIjoiNCIsInNjb3BlcyI6W119.DkIKwAkS628GQ5aGxYWP73mGkAUfycYSC_BF5LvEv0mNCOORyCnfwScWryw-hSGJ51jutlDNaSgfDt-cDkgoPT7RN9BUidRYlFynHqqcT8a2ceZksxZd7YGc60CHoOudvXQyruIXlMOLf_xbUcctOwz2WlyFy21N9cEr73x-r2HKHFMiNBcTFmCbRsNFQMQXSLnjrNTKzeqibgrDzh0PSg3UnWzyTHSHVH9Sebz9f-aPrQ_i8ccF0uw5iJkYfobFysUaey27nXKifiol5SjwNxbmQHJnESlZuoD_lHWIRqLLB6OC0byiY9votCD8GCBZfEAyRdWobtSq5vRHgVlIloFHiaTSc5biq9ggcN-TRx2k6rKR7_oM_17TwK7IADB-FUOsyjCoqKeS5pVjGyFE2HNwW0Fb6sFRUpuL1b4jIhhaLPEVSiGjaYq3fuA095wyaT_mtssaR374vPf7_1C9i6YiPWTaP8cj5TupJgOcJHJdf4wXIwm_cYVxtjC_Oac-knH9Vq8WfLOcGs-Imkg5m0oV22iU3iwqTZvqGHhOm9opUfKuPXc5i7KFqlqhA50yLCy1g4gNNy9uxnF9hN3m8-EDYcOhwxKY05AjBBqGVigbqJq57VBbUUXOVHOFVMCIFBioICMzNNeyDFHVmGThtdoue1FmzOVhQ_KB_XPAxiI'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return json_decode($response);

    }
}
    ?>
