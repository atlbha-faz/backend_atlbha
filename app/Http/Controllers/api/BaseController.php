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
   public function sendFCM($token,$id, $title, $body, $count)
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $api_key = 'AAAAPxppiDg:APA91bGMZTr8pworvvQSqw7RCh9e_3KbmhZZ7YsS6FAM4xajttQp45-wmWhA0HFVdWjFVGSpT4YafXfCVq1Sh1RflBfzzifdHpEJ7yHNgJSoBi_BEN0oR1r0nuxy4h_VoAB_TeDaa-MH';

        $headers = array
            (
            'Authorization: key=' . $api_key,
            'Content-Type: application/json;charset=UTF-8',
        );

        $fields = [
            'to' => $token,
            'content_available' => true,
            'priority' => 'high',
            'apns-priority' => 5,
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'id' => $id,
                'body' => $body,
                'title' => $title,
                'type' => 'notification',
                'count_of_notifications'=>$count,
                'sound' => 'default',
            ],
            'android'=>[
                "priority"=>"high"  // dont chang it
            ]
              ,
        'apns'=>[
                "headers"=>[
                  "apns-priority"=>"5" // dont chang it
                ]
                ]
        ];
        $content = json_encode($fields);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);
        file_put_contents(public_path('fcm.txt'), json_encode($result) . "\r\n\rToken: " . $token . "\r\n\r", FILE_APPEND);

        return $result;

        $arr = array();
        $arr = json_decode($result, true);
        return true;
    }





}
