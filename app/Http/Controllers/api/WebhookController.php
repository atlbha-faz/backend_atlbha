<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
class WebhookController extends BaseController
{
   
    public function validateSignature($body, $secret, $MyFatoorah_Signature) {

            if ($body['Event'] == 'RefundStatusChanged') {
                unset($body['Data']['GatewayReference']);
            }
          
              if ($body['Event'] == 'SupplierStatusChanged') {
                unset($body['Data']['KycFeedback']);
            }
          
            $data = $body['Data'];
        
            //1- Order all data properties in alphabetic and case insensitive.
            uksort($data, 'strcasecmp');
        
        
            //2- Create one string from the data after ordering it to be like that key=value,key2=value2 ...
            $orderedData = implode(',',
                    array_map(function ($v, $k) {
                        return sprintf("%s=%s", $k, $v);
                    },
                            $data,
                            array_keys($data)
            ));
        
        
            //4- Encrypt the string using HMAC SHA-256 with the secret key from the portal in binary mode.
            //Generate hash string
            $result = hash_hmac('sha256', $orderedData, $secret, true);
        
        
            //5- Encode the result from the previous point with base64.
            $hash = base64_encode($result);
        
            error_log(PHP_EOL . date('d.m.Y h:i:s') . ' - Generated Signature  - ' . $hash, 3, './webhook.log');
            error_log(PHP_EOL . date('d.m.Y h:i:s') . ' - MyFatoorah-Signature - ' . $MyFatoorah_Signature, 3, './webhook.log');
        
        
            //6- Compare the signature header with the encrypted hash string. If they are equal, then the request is valid and from the MyFatoorah side.
            if ($MyFatoorah_Signature === $hash) {
                error_log(PHP_EOL . date('d.m.Y h:i:s') . ' - Signature is valid ', 3, './webhook.log');
                return true;
            } else {
                error_log(PHP_EOL . date('d.m.Y h:i:s') . ' - Signature is not valid ', 3, './webhook.log');
                exit;
            }
        }
        public function handleWebhook(Request $request)
        {
            $data=$request->get('Data');
            dd($data);
            $secret="WVjz6cCoT9N7ZlqmZBMPANjkh30UvH9uwB20g-xGJi8tOjADM9NWiKaasfQCwkGLBy0ZNzoRXYCwMoLzaNwNgvY9H_3pO0bfTfwcEBCHe4s7FDQ0oGYFdMj8UwECAoiV4_3buuymrCmvdzc6QmZZuPsNfFCPg0vtwanErRHxM975FhaReP5QZsp6cU5bE8zupH5qOL7y8Hb9kSTW4u4ffx5V0WqUTrL2GmBWmAhx4eZBqoppO-jxG93E_FU7drhRA8SxiH__pNDDj3RJXBFqbLjzLjQ0iLtvR4s-c7X_dcLWXCj0X5lCBxVPFquo7Fsosbv5-NHJ-8nygVqy-HhwqnN3CV5HRD005E34zf32K8Y0eC526P2wZer5U-jr275rPEtfotn2wFFuDcWWnuT5f37p7oLDOgb2BclrmSj5crn5BxtkbG7awbxR7yVXoW-q19oE6-mcQWoNX8vdSbbdJ8arugLsR6qKW15juYZ8LztWfwnq65rRPZdh_JuE3KO96_rQR72a90FXy0mxjuXWmQU94Nek-2X_9DyecBqPxANjFwotZRNybG353CZchyvyJ60WjhVlfmxLMCdTD6wsBB5Ew5xKNru_jdG5TsshtNUgiogmf4FvJ0M8R3Xlxv98z5VXkqYytzBEtk2rbCwFopar9Ejj2Dwun5YaT5xyllcXrJltQ4UwofJ9j-bislP57_wQZCSachFOs2BaXqnRJEMb8sf6QeRm06TV-F4x7-iGJ-z7";
            $signature = $request->header('MyFatoorah-Signature');
            if(!$this->validateSignature($data,$secret,$signature )){
                return;
            }
            $event= $request->input('EventType');
            // if( $event == 1){

            // }
            
        }
    
}
