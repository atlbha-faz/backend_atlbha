<?php
namespace App\Helpers;


use Carbon\Carbon;
use App\Models\Store;
use App\Models\Package_store;
use App\Http\Resources\MaintenanceResource;

class StoreHelper 
{
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
    public static function check_store_existing($id)
    {
        $store = Store::where('domain', $id)->where('verification_status', 'accept')->whereNot('package_id', null)->whereDate('end_at', '>', Carbon::now())->first();

        if (!is_null($store)) {
            $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
        }
        if (is_null($store) || $store->is_deleted != 0 || is_null($store_package) || $store_package->status == "not_active") {
            return $this->sendError("المتجر غير موجود", "Store is't exists");
        }
        if ($store->maintenance != null) {
            if ($store->maintenance->status == 'active') {
                $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);

                $success['status'] = 200;

                return $this->sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
            }
        }
        if ($store != null) {
            return $store;
        } else {

            $success['status'] = 200;

            return $this->sendResponse($success, ' المتجر غير موجود', 'Store is not exists');
        }

    }
    public static function sendSms($request)
    {

        try {
            $data_string = json_encode($request);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://rest.gateway.sa/api/SendSMS',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
        "api_id":"' . env("GETWAY_API", null) . '",
        "api_password":"' . env("GETWAY_PASSWORD", null) . '",
        "sms_type": "T",
        "encoding":"T",
        "sender_id": "ATLBHA",
        "phonenumber": "' . $request->phonenumber . '",
        "textmessage":"' . $request->code . '",

                    "templateid": "1868",
                    "V1": "' . $request->code . '",
                    "V2": null,
                    "V3": null,
                    "V4": null,
                    "V5": null,
                "ValidityPeriodInSeconds": 60,
                "uid":"xyz",
                "callback_url":"https://xyz.com/",
                "pe_id":"xyz",
                "template_id":"1868"


                        }
                        ',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            ));

            $response = curl_exec($curl);

            $decoded = json_decode($response);

            if ($decoded->status == "S") {
                return true;
            }

            return $this->sendError("فشل ارسال الرسالة", "Failed Send Message");

        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

    }

    public static function unifonicSms($request)
    {

        $curl = curl_init();
        $data = array(
            'AppSid' => env('AppSid', '3x6ZYsW1gCpWwcCoMhT9a1Cj1a6JVz'),
            'Body' => $request->code,
            'Recipient' => $request->phonenumber);

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://el.cloud.unifonic.com/rest/SMS/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
        ));
        $response = curl_exec($curl);

        curl_close($curl);

        $responseData = json_decode($response);

        if (!is_null($responseData) && isset($responseData->success) && $responseData->success === true) {
            return true;
        } else {
            return false;
        }

    }

}
