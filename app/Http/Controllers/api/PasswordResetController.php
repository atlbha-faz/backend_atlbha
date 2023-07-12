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

        $user = User::where('user_name', $request->user_name)->orWhere('email', $request->user_name)->first();
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
             return $query->where('type', 'store')->orWhere('email', 'store_employee');
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
$ch = curl_init('https://el.cloud.unifonic.com/rest/SMS/messages?AppSid=hDRvlqGVdSQwsCLPk0bDRJIqs9Vvdi&SenderID=MASHAHER&Body='.$request->code.'&Recipient='.$request->phonenumber);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
 'Accept: application/json',
 'Content-Type: application/x-www-form-urlencoded')
);
$result = curl_exec($ch);
$decoded = json_decode($result);
if ($decoded->success =="true" ) {
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
