<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use App\Http\Resources\UserResource;
use App\Http\Controllers\API\BaseController as BaseController;
use Str;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    protected $code;



    public function login(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input, [
            'user_name' => 'string|required',
            'password' => 'string|required',
            'device_token' => 'string|required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }


        if (
            !auth()->guard()->attempt(['email' => $request->user_name, 'password' => $request->password])
            && !auth()->guard()->attempt(['user_name' => $request->user_name, 'password' => $request->password])
        ) {
            return $this->sendError('خطأ في اسم المستخدم أو كلمة المرور', 'Invalid Credentials');
        } elseif (
            !auth()->guard()->attempt(['email' => $request->user_name, 'password' => $request->password, 'verified' => 1])
            && !auth()->guard()->attempt(['user_name' => $request->user_name, 'password' => $request->password, 'verified' => 1])
        ) {
            $user = User::where('user_name', $request->user_name)->orWhere('email', $request->user_name)->first();

            if ($user) {

                $user->generateVerifyCode();
                $request->code = $user->verify_code;
                $request->phonenumber = $user->phonenumber;
                $this->sendSms($request); // send and return its response
            }

            return $this->sendError('الحساب غير محقق', 'User not verified');
        }


        $user = auth()->user();

        $user->update(['device_token' => $request->device_token]);

        $success['user'] = new UserResource($user);
        $success['token'] = $user->createToken('authToken')->accessToken;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح', 'Login Successfully');
    }


    public function logout()
    {
        $user = auth("api")->user()->token();
        $user->revoke();

        $success['status'] = 200;
        return $this->sendResponse($success, 'تم تسجيل الخروج بنجاح', 'User logout Successfully');
    }



    public function store_verify_message(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input, [
            'user_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError($validator->errors());
        }

        $user = User::where('user_name', $request->user_name)->orWhere('email', $request->user_name)->first();
        if (!$user) {
            return $this->sendError('المستخدم غير موجود', 'We cant find a user with that user_name.');
        }


        if ($user) {
            $user->generateVerifyCode();

            $request->code = $user->verify_code;
            $request->phonenumber = $user->phonenumber;
            $this->sendSms($request); // send and return its response

        }

        $success['status'] = 200;
        $success['user'] = new UserResource($user);
        return $this->sendResponse($success, 'تم ارسال الرسالة بنجاح', 'Email send successfully');
    }

    public function verifyUser(Request $request)
    {

        $input = $request->all();
        $validator =  Validator::make($input, [
            'user_name' => 'required|string',
            'code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }


        $user = User::where('user_name', $request->user_name)->orWhere('email', $request->user_name)->latest()->first();

        if ($request->code == $user->verify_code) {
            $user->resetVerifyCode();
            $user->verified = 1;
            $user->save();
            $success['status'] = 200;
            $success['user'] = new UserResource($user);
            $success['token'] = $user->createToken('authToken')->accessToken;
            return $this->sendResponse($success, 'تم التحقق', 'verified');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'لم يتم التحقق', 'not verified');
        }
    }






    /////////////////////////////////////////////////// SMS


    public function sendSms($request)
    {

        try {
            $ch = curl_init('https://el.cloud.unifonic.com/rest/SMS/messages?AppSid=hDRvlqGVdSQwsCLPk0bDRJIqs9Vvdi&SenderID=MASHAHER&Body=' . $request->code . '&Recipient=' . $request->phonenumber);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Accept: application/json',
                    'Content-Type: application/x-www-form-urlencoded'
                )
            );
            $result = curl_exec($ch);
            $decoded = json_decode($result);
            if ($decoded->success == "true") {
                return true;
            }

            return $this->sendError("فشل ارسال الرسالة", "Failed Send Message");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }


    public function social_mobile(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input, [
            'user_name' => 'string|required',
            'device_token' => 'string|required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        $user = User::where('email', $request->user_name)->first();
        if (is_null($user)) {
            return $this->sendError('خطأ في اسم المستخدم ', 'Invalid Credentials');
        } elseif ($user->verified == 0) {
            $user = User::where('email', $request->user_name)->first();

            if ($user) {
                $user->generateVerifyCode();
                $request->code = $user->verify_code;
                $request->phonenumber = $user->phonenumber;

                $this->sendSms($request); // send and return its response
            }

            return $this->sendError('الحساب غير محقق', 'User not verified');
        }

        $user = Auth::guard('web')->loginUsingId($user->id, true);
        $user->update(['device_token' => $request->device_token]);

        $success['user'] = new UserResource($user);
        $success['token'] = $user->createToken('authToken')->accessToken;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح', 'Login Successfully');
    }
}
