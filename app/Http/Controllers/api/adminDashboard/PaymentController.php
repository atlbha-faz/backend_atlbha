<?php

namespace App\Http\Controllers\api\adminDashboard;

use Carbon\Carbon;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class PaymentController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    } 
    

    public function payment(Request $request)
    {
      $input = $request->all();
      $validator =  Validator::make($input ,[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'amount' => 'required|numeric',
            'order_id' => 'required',
            'phone' => 'required|numeric'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }

        $payment = [
          "amount" => round($request['amount'],2),
          "description" =>  'Hello '. $request['first_name'].' '.$request['last_name'].' Your order_id is '.$request['order_id'].' please pay and confirm your order Thanks For made order.',
          "currency" => $request['currency'],
          "receipt" => [
              "email" => true,
              "sms" => true
          ],
         
          "customer"=> [
              "first_name"=> $request['first_name'],
              "last_name"=> $request['last_name'],
              "email"=> $request['email'],
              "phone"=> [
                  "country_code" => '965',
                  "number" => $request['phone']
              ]
          ],
          "source"=> [
              "id"=> "src_card"
          ],
          "save_card"=>false,      
            "redirect"=> [
                "url"=>route('callback')
            ]
        ];


        $curl = curl_init();
      
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.tap.company/v2/charges",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($payment),
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer sk_test_jmXOB9J5foCdHli2G0zPAq1T",
            "content-type: application/json"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        //  $response=json_decode($response);
      
if ($err) {
  echo "cURL Error #:" . $err;
} 
 return redirect($response->transaction->url);
    // echo   $response;
    }

    
    
    public function callback(Request $request)
    {
        $input = $request->all();

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.tap.company/v2/charges/".$input['tap_id'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "{}",
        CURLOPT_HTTPHEADER => array(
                "authorization: Bearer sk_test_jmXOB9J5foCdHli2G0zPAq1T" // SECRET API KEY
            ),
        ));

        $err = curl_error($curl);
        $response = curl_exec($curl);
  

           curl_close($curl);

          if ($err) {
          echo "cURL Error #:" . $err;
            } else {
              $responseTap = json_decode($response);

              if ($responseTap->status == 'CAPTURED') {
                $payment = Payment::create([
                  'paymenDate'=>Carbon::now(),
                  'paymentType' =>$responseTap->source->payment_method,
                  'paymentTransectionID'=>$responseTap->id,
                  'paymentCardID'=>$responseTap->card->id
                ]);

                $success['payments']=New PaymentResource($payment);
                $success['status']= 200;
        
                 return $this->sendResponse($success,'تم الدفع بنجاح','payment successfully');
            }
              }
                  
        
}

    



function updateCharge(Request $request,$id){
  $input = $request->all();
  $validator =  Validator::make($input ,[
    'description' => 'String',
    'email' => 'boolean',
    'sms' => 'boolean',
    'udf2' => 'String',
   
]); 
if ($validator->fails())
{
    return $this->sendError(null,$validator->errors());
}

    $data['description']=$request['description'];
    $data['receipt']['email']=$request['email'];
    $data['receipt']['sms']=$request['sms'];
    $data['metadata']['udf2']=$request['udf2'];
 
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.tap.company/v2/charges/".$id,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "PUT",
  CURLOPT_POSTFIELDS => json_encode($data),
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer sk_test_jmXOB9J5foCdHli2G0zPAq1T",
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

}




function list(){
    
    $data['type']=1;

$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => "https://api.tap.company/v2/charges/list",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "POST",
CURLOPT_POSTFIELDS => json_encode($data),
CURLOPT_HTTPHEADER => array(
  "authorization: Bearer sk_test_jmXOB9J5foCdHli2G0zPAq1T",
  "content-type: application/json"
),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
echo "cURL Error #:" . $err;
} else {
echo $response;
}
  }


}
?>
