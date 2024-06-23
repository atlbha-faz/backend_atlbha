<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FatoorahServices;
use Illuminate\Support\Str;
use App\Http\Controllers\api\BaseController as BaseController;

class FatoorahSessionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    } 
     public function initiateSession()
    {
        $payment = new FatoorahServices();
        $data=[
            'CustomerIdentifier'=>(String)Str::uuid(),
            'SaveToken'=> true
        ];
        $data = json_encode($data);
        $response = $payment->buildRequest('v2/InitiateSession', 'POST', $data);

        if (isset($response['IsSuccess'])) {
            if ($response['IsSuccess'] == true) {

                $success['respone'] = $response;
            }
        }
         $success['status'] = 200;

     return $this->sendResponse($success, 'تم تهيئة الدفع بنجاح', 'initiateSession successfully');
    }
    public function updateSession(Request $request)
    {
        $payment = new FatoorahServices();
        $data=[
                'SessionId'=>$request->SessionId,
                'Token'=>$request->Token,
                'TokenType'=> "mftoken",
                'SecurityCode'=>$request->SecurityCode,
              
        ];
        $data = json_encode($data);
        $response = $payment->buildRequest('v2/UpdateSession', 'POST', $data);

        if (isset($response['IsSuccess'])) {
            if ($response['IsSuccess'] == true) {

                $success['respone'] = $response;
            }
        }
         $success['status'] = 200;

     return $this->sendResponse($success, 'تم  التعديل بنجاح', 'updateSession successfully');
    }
}
