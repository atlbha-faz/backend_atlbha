<?php
namespace App\Helpers;
class Helper{
  public static function sendError($error ,$error_en , $errorMessages=[], $code=200)
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