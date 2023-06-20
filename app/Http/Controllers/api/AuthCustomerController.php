<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\UserResource;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        $store = Store::where('domain', $request->domain)->first();
        $id = $store->id;

        if (!auth()->guard()->attempt(['email' => $request->email, 'user_type' => 'customer', 'store_id' => $id])) {
            $user = User::create([
                'email' => $request->email,
                'user_type' => "customer",
            ]);
            $user->generateVerifyCode();
            $request->code = $user->verify_code;
            $request->email = $user->email;
            Mail::to($request->email)->send(new \App\Mail\VerifyEmail ($request->code));

        } else {
            $user = User::where('email', $request->email)->where('store_id', $id)->first();

            $user->generateVerifyCode();
            $request->code = $user->verify_code;
            //  $request->phonenumber = $user->phonenumber;
            Mail::to($request->email)->send(new \App\Mail\VerifyEmail ($request->code));

        }
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
}
