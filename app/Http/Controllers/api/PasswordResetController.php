<?php
namespace App\Http\Controllers\api;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\SendCode;
use Illuminate\Support\Str;
use App\Helpers\StoreHelper;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
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
        $validator = Validator::make($input, [
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],

        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $phonenumber = $request->phonenumber;
        $user = User::where(
            function ($query) {
                return $query->where('user_type', 'store')->orWhere('user_type', 'store_employee');
            })->where(
            function ($query) use ($phonenumber) {
                return $query->where('phonenumber', $phonenumber);
            })->where('is_deleted', 0)->first();
        if (!$user) {
            return $this->sendError('المستخدم غير موجود', 'We cant find a user with that username.');
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60),
            ]
        );

        if ($user && $passwordReset) {
            $user->generateCode();

            $request->code = $user->code;
            $request->phonenumber = $user->phonenumber;
            $status = StoreHelper::unifonicSms($request);
            if ($status === false) {
                StoreHelper::sendSms($request);
            }

        }

        $success['status'] = 200;
        $success['user'] = new UserResource($user);
        return $this->sendResponse($success, 'تم ارسال الرسالة بنجاح', 'massege send successfully');
    }

    public function create_by_email(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user_name = $request->user_name;
        $user = User::where(
            function ($query) {
                return $query->where('user_type', 'store')->orWhere('user_type', 'store_employee');
            })->where(
            function ($query) use ($user_name) {
                return $query->where('user_name', $user_name)->orWhere('email', $user_name);
            })
            ->where('is_deleted', 0)->first();
        if (!$user) {
            return $this->sendError('المستخدم غير موجود', 'We cant find a user with that username.');
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60),
            ]
        );

        if ($user && $passwordReset) {
            $user->generateCode();
            $data = array(
                'code' => $user->code,
            );

            try {
                Mail::to($user->email)->send(new SendCode($data));
            } catch (\Exception $e) {
                return $this->sendError('صيغة البريد الالكتروني غير صحيحة', 'The email format is incorrect.');
            }

        }

        $success['status'] = 200;
        $success['user'] = new UserResource($user);
        return $this->sendResponse($success, 'تم ارسال الرسالة بنجاح', 'massege send successfully');
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
        if (!$passwordReset) {
            return $this->sendError(' خطأ في توكين استعادة كلمة المرور', 'This password reset token is invalid.');
        }
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return $this->sendError('خطأ في توكين استعادة كلمة المرور', 'This password reset token is invalid.');
        }

        $success['status'] = 200;
        $success['passowrdReset'] = $passwordReset;
        return $this->sendResponse($success, 'توكين استعادة كلمة المرور صحيح', 'This password reset token is valid.');
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
        $validator = Validator::make($input, [
            //'email' => 'required|string|email',
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'password' => ['required', 'confirmed', 'string', 'min:8'],
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $phonenumber = $request->phonenumber;
        $user = User::where(
            function ($query) {
                return $query->where('user_type', 'store')->orWhere('user_type', 'store_employee');
            })->where(
            function ($query) use ($phonenumber) {
                return $query->where('phonenumber', $phonenumber)->orWhere('email', $phonenumber);
            })->where('is_deleted', 0)->first();
        if (!$user) {
            return $this->sendError('المستخدم غير موجود', 'We cant find a user with that username.');
        }

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $user->email],
        ])->first();
        if (!$passwordReset) {
            return $this->sendError('خطأ في توكين استعادة كلمة المرور', 'This password reset token is invalid.');
        }

        $user = User::where('email', $passwordReset->email)->first();
        if (!$user) {
            return $this->sendError('المستخدم غير موجود', 'We cant find a user with that e-mail address.');
        }

        $user->password = $request->password;
        $user->save();
        $passwordReset->delete();

        $success['status'] = 200;
        $success['user'] = new UserResource($user);
        $success['token'] = $user->createToken('authToken')->accessToken;
        return $this->sendResponse($success, 'تم استعادة كلمة المرور بنجاح', 'The password reset success.');
    }

    /////////////////////////////////////////////////// SMS
    public function verifyContact(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $phonenumber = $request->phonenumber;
        $user = User::where(
            function ($query) {
                return $query->where('user_type', 'store')->orWhere('user_type', 'store_employee');
            })->where(
            function ($query) use ($phonenumber) {
                return $query->where('phonenumber', $phonenumber);
            })->where('is_deleted', 0)->first();
        $a = now()->toDateTimeString();
        if ($user->code_expires_at < $a) {
            $success['status'] = $a;
            return $this->sendResponse($success, 'انتهت صلاحية الكود', 'not verified');
        }
        if ($request->code == $user->code) {
            $passwordReset = PasswordReset::where('email', $user->email)->first();
            $user->resetCode();
            $success['status'] = 200;
            $success['user'] = new UserResource($user);
            $success['token'] = $passwordReset->token;
            return $this->sendResponse($success, 'تم التحقق', 'verified');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'لم يتم التحقق', 'not verified');
        }
    }

   

}
