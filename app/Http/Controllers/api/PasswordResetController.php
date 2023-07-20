<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PasswordReset;
use App\Models\User;
use Validator;
use Illuminate\Support\Str;

use App\Mail\SendCode;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserResource;
use App\Http\Controllers\api\BaseController as BaseController;

class PasswordResetController extends BaseController
{

    protected $code, $smsVerifcation;
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'user_name' => 'required|string',
        ]);

        if ($validator->fails())
        {
            # code...
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user_name = $request->user_name;
        $user = User::where(
           function($query) {
             return $query->where('user_type', 'store')->orWhere('user_type', 'store_employee');
            })->where(
           function($query )use ($user_name) {
             return $query->where('user_name', $user_name)->orWhere('email', $user_name);
            })
            ->first();
        if (!$user){
            return $this->sendError('المستخدم غير موجود','We cant find a user with that username.');
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
             ]
        );

        if ($user && $passwordReset){
                $user->generateCode();
                 $data = array(
                'code'   =>   $user->code,
            );

          /*   try{
                 Mail::to($user->email)->send(new SendCode($data));
            }catch(\Exception $e){
            return $this->sendError('صيغة البريد الالكتروني غير صحيحة','The email format is incorrect.');
            }*/



          $request->code = $user->code;
            $request->phonenumber =$user->phonenumber;

        $this->sendSms($request); // send and return its response


        }

        $success['status']= 200;
        $success['user']= new UserResource($user);
        return $this->sendResponse($success,'تم ارسال الرسالة بنجاح','massege send successfully');
    }

       public function create_by_email(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'user_name' => 'required|string',
        ]);

        if ($validator->fails())
        {
            # code...
            return $this->sendError('Validation Error.', $validator->errors());
        }
$user_name = $request->user_name;
        $user = User::where(
           function($query) {
             return $query->where('user_type', 'store')->orWhere('user_type', 'store_employee');
            })->where(
           function($query )use ($user_name) {
             return $query->where('user_name', $user_name)->orWhere('email', $user_name);
            })
            ->first();
        if (!$user){
            return $this->sendError('المستخدم غير موجود','We cant find a user with that username.');
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
             ]
        );

        if ($user && $passwordReset){
                $user->generateCode();
                 $data = array(
                'code'   =>   $user->code,
            );

           try{
                 Mail::to($user->email)->send(new SendCode($data));
            }catch(\Exception $e){
            return $this->sendError('صيغة البريد الالكتروني غير صحيحة','The email format is incorrect.');
            }



         /* $request->code = $user->code;
            $request->phonenumber =$user->phonenumber;

        $this->sendSms($request); // send and return its response
*/

        }

        $success['status']= 200;
        $success['user']= new UserResource($user);
        return $this->sendResponse($success,'تم ارسال الرسالة بنجاح','massege send successfully');
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset){
            return $this->sendError(' خطأ في توكين استعادة كلمة المرور','This password reset token is invalid.');
        }
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return $this->sendError('خطأ في توكين استعادة كلمة المرور','This password reset token is invalid.');
        }

        $success['status']= 200;
        $success['passowrdReset']= $passwordReset;
        return $this->sendResponse($success,'توكين استعادة كلمة المرور صحيح','This password reset token is valid.');
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input ,[
            //'email' => 'required|string|email',
            'user_name' => 'required|string',
            'password' => ['required','confirmed','string','min:6', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@]).*$/'],
            'token' => 'required|string',
        ]);

        if ($validator->fails())
        {
            # code...
           return $this->sendError('Validation Error.', $validator->errors());
        }


        $user = User::where('user_name', $request->user_name)->orWhere('email', $request->user_name)->first();
        if (!$user){
            return $this->sendError('المستخدم غير موجود','We cant find a user with that username.');
        }

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $user->email]
        ])->first();
        if (!$passwordReset)
            return $this->sendError('خطأ في توكين استعادة كلمة المرور','This password reset token is invalid.');


        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return $this->sendError('المستخدم غير موجود','We cant find a user with that e-mail address.');

        $user->password = $request->password;
        $user->save();
        $passwordReset->delete();


        $success['status']= 200;
        $success['user']= new UserResource($user);
        return $this->sendResponse($success,'تم استعادة كلمة المرور بنجاح','The password reset success.');
    }

    /////////////////////////////////////////////////// SMS
    public function verifyContact(Request $request)
    {

        $input = $request->all();
        $validator =  Validator::make($input ,[
            'user_name' => 'required|string',
            'code' => 'required|numeric',
        ]);

        if ($validator->fails())
        {
            # code...
           return $this->sendError('Validation Error.', $validator->errors());
        }


        $user = User::where('user_name', $request->user_name)->orWhere('email', $request->user_name)->latest()->first();

        if($request->code == $user->code)
        {
            $passwordReset = PasswordReset::where('email',$user->email)->first();
            $user->resetCode();
            $success['status']= 200;
            $success['user']= new UserResource($user);
            $success['token']= $passwordReset->token;
            return $this->sendResponse($success,'تم التحقق','verified');
        }
        else
        {
            $success['status']= 200;
            return $this->sendResponse($success,'لم يتم التحقق','not verified');
        }
    }



    public function sendSms($request)
    {

      try
        {
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
          CURLOPT_POSTFIELDS =>'{ 
        "api_id":"'.env("GETWAY_API", null).'", 
        "api_password":"'.env("GETWAY_PASSWORD", null).'", 
        "sms_type": "T", 
        "encoding":"T", 
        "sender_id": "ATLBHA", 
        "phonenumber": "'.$request->phonenumber.'", 
        "textmessage":"'.$request->code.'", 

  	"templateid": "1868", 
  	"V1": "'.$request->code.'", 
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
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        
        
        /*  curl_close($curl);
      echo $response;



            
            $ch = curl_init('http://REST.GATEWAY.SA/api/SendSMS?api_id=API71257826714&api_password=FAZ@102030@123&sms_type=P&encoding=T&sender_id=MASHAHER&phonenumber='.$request->phonenumber.'&textmessage='.$request->code.'&uid=xyz&callback_url=null'); 

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");             
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
 'Accept: application/json', 
 'Content-Type: application/x-www-form-urlencoded',                                                                              
        'Content-Length: ' . strlen($data_string)) 
); 
$result = curl_exec($ch);*/
$decoded = json_decode($response);

//dd($decoded);
if ($decoded->status =="S" ) {
    return true;
}
    
            return $this->sendError("فشل ارسال الرسالة","Failed Send Message");


            
        }
        catch (Exception $e)
        {
            return $this->sendError($e->getMessage());
        }
        

    }


}
