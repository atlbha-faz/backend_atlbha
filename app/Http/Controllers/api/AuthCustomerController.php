<?php
namespace App\Http\Controllers\api;

use App\Models\User;
use App\Mail\SendCode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class AuthCustomerController extends BaseController
{
    //login store template
//login store with phoneNumber template
    public function login_customer(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) {
                return $query->where('user_type', 'customer');
            }),
            ],

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        if (!auth()->guard()->attempt(['phonenumber' => $request->phonenumber, 'user_type' => 'customer'])) {
            $user = User::create([
                'phonenumber' => $request->phonenumber,
                'user_type' => "customer",
            ]);

            $user->generateVerifyCode();
            $request->code = $user->verify_code;
            $request->phonenumber = $user->phonenumber;
            $this->sendSms($request);

        } else {
            $user = User::where('phonenumber', $request->phonenumber)->where('is_deleted', 0)->first();
            $user->generateVerifyCode();
            $request->code = $user->verify_code;
            $request->phonenumber = $user->phonenumber;
            $this->sendSms($request); // send and return its response

        }
        $success['user'] = new UserResource($user);
        $success['token'] = $user->createToken('authToken')->accessToken;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح', 'Login Successfully');

    }

    //login store with email template
    public function login_customer_email(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                return $query->where('user_type', 'customer');
            }),
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        if (!auth()->guard()->attempt(['email' => $request->email, 'user_type' => 'customer'])) {
            $user = User::create([
                'email' => $request->email,
                'user_type' => "customer",
            ]);
            $user->generateVerifyCode();
            $request->code = $user->verify_code;
            $request->email = $user->email;
            $data = array(
                'code' =>  $request->code ,
            );

            Mail::to($user->email)->send(new SendCode($data));

        } else {
            $user = User::where('email', $request->email)->first();

            $user->generateVerifyCode();
            $request->code = $user->verify_code;
            $data = array(
                'code' =>  $request->code ,
            );

            //  $request->phonenumber = $user->phonenumber;
            Mail::to($user->email)->send(new SendCode($data));

        }
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

    public function verifyUser(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'phonenumber' => 'required|string',
            'code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $user = User::where('phonenumber', $request->phonenumber)->orWhere('email', $request->phonenumber)->latest()->first();

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
    
    public function registerUser(Request $request, $id)
    {
        $user = User::where('id', $id)->where('is_deleted', 0)->first();
        if ($user->phonenumber != null) {
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required|string',
                'user_name' => 'required|string',
                'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                    return $query->where('user_type', 'customer');
                }),
                ],
            ]);
            if ($validator->fails()) {
                # code...
                return $this->sendError(null, $validator->errors());
            }
            $user->update([
                'name' => $request->name,
                'user_name' => $request->user_name,
                'email' => $request->email,

            ]);
        } else {
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required|string',
                'user_name' => 'required|string',
                'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) {
                    return $query->where('user_type', 'customer');
                }),
                ],

            ]);

            if ($validator->fails()) {
                # code...
                return $this->sendError(null, $validator->errors());
            }
            $user->update([
                'name' => $request->name,
                'user_name' => $request->user_name,
                'phonenumber' => $request->phonenumber,

            ]);
        }
        $success['status'] = 200;
        $success['user'] = new UserResource($user);
        $success['token'] = $user->createToken('authToken')->accessToken;
        return $this->sendResponse($success, 'تم التسجيل', 'regsiter');
    }
    
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
                    'Content-Type: application/x-www-form-urlencoded',
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
}
