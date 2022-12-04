<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class BaseController extends Controller
{
   public function sendResponse($result , $message, $message_en)
   {
    $response = [
        'success' =>true ,
        'data'=>$result,
        'message'=>['en' => $message_en, 'ar' => $message]

    ];


    return response()->json($response , 200);

   }

   public function sendError($error ,$error_en , $errorMessages=[], $code=200)
   {
    $response = [
        'success' =>false ,
        'message'=>['en' => $error_en, 'ar' => $error]

    ];

    if (!empty($errorMessages)) {
        # code...
        $response['data']= $errorMessages;
    }else{
        $response['data']= null;
    }

        return response()->json($response,$code);

   }





}
