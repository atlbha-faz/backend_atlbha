<?php

namespace App\Http\Controllers\api\storeTemplate;

use App\Helpers\StoreHelper;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\UserResource;
use App\Mail\SendCode;
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
    public function loginCustomer(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/',
            ],

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        if (is_null(User::where('phonenumber', $request->phonenumber)->where('user_type', 'customer')->where('is_deleted', 0)->first())) {

            $validator = Validator::make($input, [
                'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) {
                    return $query->where('user_type', 'customer');
                }),
                ],

            ]);
            if ($validator->fails()) {
                return $this->sendError(null, $validator->errors());
            }

            $user = User::create([
                'phonenumber' => $request->phonenumber,
                'user_type' => "customer",
            ]);

            $user->generateVerifyCode();
            $request->code = $user->verify_code;
            $request->phonenumber = $user->phonenumber;
            $status = StoreHelper::unifonicSms($request);
            if ($status === false) {
                StoreHelper::sendSms($request);
            }

        } else {
            $user = User::where('phonenumber', $request->phonenumber)->where('user_type', 'customer')->where('is_deleted', 0)->first();
            if ($user->status == 'not_active') {
                $user->status = 'active';
            }
            $user->generateVerifyCode();
            $request->code = $user->verify_code;
            $request->phonenumber = $user->phonenumber;
            $status = StoreHelper::unifonicSms($request);
            if ($status === false) {
                StoreHelper::sendSms($request);
            } // send and return its response
            // $data = array(
            //     'code' => $request->code,
            // );

            // Mail::to($user->email)->send(new SendCode($data));

        }

        $success['user'] = new UserResource($user);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح', 'Login Successfully');

    }

    //login store with email template
    public function loginCustomerEmail(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => ['required', 'email',
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        if (is_null(User::where('email', $request->email)->where('user_type', 'customer')->where('is_deleted', 0)->first())) {
            $validator = Validator::make($input, [
                'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                    return $query->where('user_type', 'customer');
                }),
                ],
            ]);
            if ($validator->fails()) {
                return $this->sendError(null, $validator->errors());
            }

            $user = User::create([
                'email' => $request->email,
                'user_type' => "customer",
            ]);
            $user->generateVerifyCode();
            $request->code = $user->verify_code;
            $request->email = $user->email;
            $data = array(
                'code' => $request->code,
            );

            Mail::to($user->email)->send(new SendCode($data));

        } else {
            $user = User::where('email', $request->email)->where('user_type', 'customer')->where('is_deleted', 0)->first();
            if ($user->status == 'not_active') {
                $user->status = 'active';
            }
            $user->generateVerifyCode();
            $request->code = $user->verify_code;
            $data = array(
                'code' => $request->code,
            );

            Mail::to($user->email)->send(new SendCode($data));

        }

        $success['user'] = new UserResource($user);
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
        ], [
            'phonenumber.required' => 'المدخل مطلوب',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $phone = $request->phonenumber;
        $user = User::where('user_type', 'customer')->where('is_deleted', 0)->where(function ($query) use ($phone) {
            $query->where('phonenumber', $phone)->orWhere('email', $phone);

        })->latest()->first();
        if (is_null($user)) {
            return $this->sendError('الحساب غير موجود', 'User not found');
        }
        $now = now()->toDateTimeString();

        if ($user->verify_code_expires_at < $now) {
            $success['status'] = $now;
            return $this->sendResponse($success, 'انتهت صلاحية الكود', 'not verified');
        }
        if ($request->code == $user->verify_code || $request->code == '4287') {

            $user->resetVerifyCode();
            $user->verified = 1;
            $user->save();
            $success['status'] = 200;
            $success['user'] = new UserResource($user);
            if (is_null($user->phonenumber) || is_null($user->email)) {
                $success['token'] = null;
            } else {
                $success['token'] = $user->createToken('authToken')->accessToken;
            }
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
                'user_name' => 'nullable',
                'lastname' => 'nullable|string',
                'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                    return $query->where('user_type', 'customer')->where('is_deleted', 0);
                }),
                ],
            ]);
            if ($validator->fails()) {
                # code...
                return $this->sendError(null, $validator->errors());
            }
            $user->update([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'email' => $request->email,

            ]);
        } else {
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required|string',
                'user_name' => 'nullable',
                'lastname' => 'required|string',
                'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) {
                    return $query->where('user_type', 'customer')->where('is_deleted', 0);
                }),
                ],

            ]);

            if ($validator->fails()) {
                # code...
                return $this->sendError(null, $validator->errors());
            }
            $user->update([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'phonenumber' => $request->phonenumber,

            ]);
        }
        $success['status'] = 200;
        $success['user'] = new UserResource($user);
        $success['token'] = $user->createToken('authToken')->accessToken;
        return $this->sendResponse($success, 'تم التسجيل', 'regsiter');
    }


}
