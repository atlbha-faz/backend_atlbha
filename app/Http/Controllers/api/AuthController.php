<?php

namespace App\Http\Controllers\api;

use App\Events\VerificationEvent;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\UserResource;
use App\Models\Marketer;
use App\Models\Setting;
use App\Models\Store;
use App\Models\User;
use App\Models\Websiteorder;
use App\Notifications\verificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends BaseController
{
    protected $code;

    public function register(Request $request)
    {
        $setting = Setting::orderBy('id', 'desc')->first();
        if ($setting->registration_status == "stop_registration") {
            return $this->sendError('stop_registration', 'تم ايقاف التسجيل');

        } else {
            $request->package_id =1;
            if ($request->user_type == 'store') {
             
                $input = $request->all();
                $validator = Validator::make($input, [
                    'checkbox_field' => 'required|in:1',
                    'user_type' => 'required|in:store,marketer',
                    // 'name'=>'required|string|max:255',
                    'user_name' => 'required|string|max:255|unique:users',
                    //'store_name'=>'required_if:user_type,store|string|max:255',

                    //'store_email'=>'required_if:user_type,store|email|unique:stores',
                    'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@]).*$/',
                    //'domain'=>'required_if:user_type,store|unique:stores',

                    //'phonenumber' =>['required_if:user_type,store','numeric','regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
                    //'activity_id' =>'required_if:user_type,store|array|exists:activities,id',
                    //'package_id' => 'required_if:user_type,store|exists:packages,id',
                    //'country_id'=>'required_if:user_type,store|exists:countries,id',
                    'city_id' => 'required_if:user_type,marketer|exists:cities,id',
                    //'periodtype' => 'required_if:user_type,store|in:6months,year',
                    //'periodtype' => 'nullable|required_unless:package_id,1|in:6months,year',
                   'periodtype' => 'required|in:6months,year',
                    'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                        return $query->whereIn('user_type', ['store', 'store_employee']);
                    })],
                    'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) {
                        return $query->whereIn('user_type', ['store', 'store_employee']);
                    })],

                ]);
                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors());
                }
            } else {
                if ($setting->registration_marketer =="not_active") {

         
                    return $this->sendError('stop_registration_markter', 'لايمكن تسجيل مندوب');
             
            }else {
            
                $input = $request->all();
                $validator = Validator::make($input, [
                    'checkbox_field' => 'required|in:1',
                    'user_type' => 'required|in:store,marketer',
                    // 'name'=>'required|string|max:255',
                    'user_name' => 'required|string|max:255|unique:users',
                    //'store_name'=>'required_if:user_type,store|string|max:255',

                    //'store_email'=>'required_if:user_type,store|email|unique:stores',
                    'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@]).*$/',
                    //'domain'=>'required_if:user_type,store|unique:stores',
                    //'phonenumber' =>['required_if:user_type,store','numeric','regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
                    //'activity_id' =>'required_if:user_type,store|array|exists:activities,id',
                    //'package_id' => 'required_if:user_type,store|exists:packages,id',
                    //'country_id'=>'required_if:user_type,store|exists:countries,id',
                    'city_id' => 'required_if:user_type,marketer|exists:cities,id',
                    //'periodtype' => 'required_if:user_type,store|in:6months,year',
                    'email' => 'nullable', 'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                        return $query->whereIn('user_type', ['marketer']);
                    }),
                    ],
                    'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) {
                        return $query->whereIn('user_type', ['marketer']);
                    }),
                    ],
                    'name' => 'required|string|max:255',
                ]);

            }
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
        }
            if ($request->user_type == "store") {

                $user = User::create([
                    //'name' => $request->name,
                    'email' => $request->email,
                    'user_name' => $request->user_name,
                    'user_type' => "store",
                    'password' => $request->password,
                    'phonenumber' => $request->phonenumber,

                ]);

                $userid = $user->id;

                $store = Store::create([
                    // 'store_name' => $request->store_name,
                    // 'store_email'=>$request->store_email,
                    // 'domain' =>$request->domain,
                    // 'phonenumber' => $request->phonenumber,
                    'package_id' => $request->package_id,
                    'user_id' => $userid,
                    'periodtype' => $request->periodtype,
                    //'country_id' => $request->country_id,
                    //  'city_id' => $request->city_id,

                ]);

                $user->update([
                    'store_id' => $store->id]);
                $user->assignRole("المالك");

                if ($request->periodtype == "6months") {
                    $end_at = date('Y-m-d', strtotime("+ 6 months", strtotime($store->created_at)));
                    $store->update([
                        'start_at' => $store->created_at,
                        'end_at' => $end_at]);
                } elseif ($request->periodtype == "year") {
                    $end_at = date('Y-m-d', strtotime("+ 1 years", strtotime($store->created_at)));
                    $store->update([
                        'start_at' => $store->created_at,
                        'end_at' => $end_at]);

                } else {
                    $end_at = date('Y-m-d', strtotime("+ 2 weeks", strtotime($store->created_at)));
                    $store->update([
                        'start_at' => $store->created_at,
                        'end_at' => $end_at]);
                }
                // $store->activities()->attach($request->activity_id);
                $store->packages()->attach($request->package_id, ['start_at' => $store->created_at, 'end_at' => $end_at, 'periodtype' => $request->periodtype, 'packagecoupon_id' => $request->packagecoupon]);
                $user->generateVerifyCode();
                $request->code = $user->verify_code;
                $request->phonenumber = $user->phonenumber;
                $this->sendSms($request);
            

            $success['user'] = new UserResource($user);
            $success['token'] = $user->createToken('authToken')->accessToken;
            $success['status'] = 200;

            } else {

                if ($setting->registration_marketer === "active") {
                    $user = User::create([

                        'email' => $request->email,
                        'user_name' => $request->user_name,
                        'password' => $request->password,
                        'phonenumber' => $request->phonenumber,
                        'user_type' => "marketer",
                        'name' => $request->name,
                        'city_id' => $request->city_id,

                    ]);

                    $user->update([
                        'user_type' => "marketer",
                        'name' => $request->name,
                        'city_id' => $request->city_id,
                    ]);
                    $marketer = Marketer::create([
                        'user_id' => $user->id,
                    ]);
                    if ($setting->status_marketer === "not_active") {
                        $user->update(['status' => "not_active"]);
                    }
                    $user->generateVerifyCode();
                    $request->code = $user->verify_code;
                    $request->phonenumber = $user->phonenumber;
                    $this->sendSms($request);
                
    
                $success['user'] = new UserResource($user);
                $success['status'] = 200;
                } 

            }

          
            return $this->sendResponse($success, 'تم التسجيل بنجاح', 'Register Successfully');
        }
    }
    public function login_admin(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_name' => 'string|required',
            'password' => 'string|required',
            //'device_token' => 'string|required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        //dd(Hash::make($request->password));

        if (
            !auth()->guard()->attempt(['email' => $request->user_name, 'password' => $request->password, 'is_deleted' => 0,'status' => 'active', 'user_type' => function ($query) {
       $query->whereIn('user_type',  ['admin','admin_employee']);
  }
  ])
            && !auth()->guard()->attempt(['user_name' => $request->user_name, 'password' => $request->password, 'is_deleted' => 0,'status' => 'active', 'user_type' => function ($query) {
       $query->whereIn('user_type',  ['admin','admin_employee']);
  }])
        )  {
            return $this->sendError('خطأ في اسم المستخدم أو كلمة المرور', 'Invalid Credentials');
        } /* elseif (
            !auth()->guard()->attempt(['email' => $request->user_name, 'password' => $request->password, 'is_deleted' => 0, 'user_type' => function ($query) {
       $query->whereIn('user_type',  ['admin','admin_employee']);
  }, 'verified' => 1])
            && !auth()->guard()->attempt(['user_name' => $request->user_name, 'password' => $request->password, 'is_deleted' => 0, 'user_type' => function ($query) {
       $query->whereIn('user_type',  ['admin','admin_employee']);
  }, 'verified' => 1])
        ) {
           $user_name =$request->user_name;
            $user = User::whereIn('user_type',  ['admin','admin_employee']) ->where(function($query) use ($user_name) {
                    $query->where('user_name', $user_name)->orWhere('email', $user_name);
                })
                ->first();

            if ($user) {

                $user->generateVerifyCode();
                $request->code = $user->verify_code;
                $request->phonenumber = $user->phonenumber;
                $this->sendSms($request); // send and return its response
            }

            return $this->sendError('الحساب غير محقق', 'User not verified');
        }
        */
        $remember = request('remember');
        if (auth()->guard()->attempt(['email' => $request->user_name, 'password' => $request->password, 'is_deleted' => 0,'status' => 'active', 'user_type' => function ($query) {
       $query->whereIn('user_type',  ['admin','admin_employee']);
  }, /*'verified' => 1 */]) || auth()->guard()->attempt(['user_name' => $request->user_name, 'password' => $request->password, 'is_deleted' => 0,'status' => 'active', 'user_type' => function ($query) {
       $query->whereIn('user_type',  ['admin','admin_employee']);
  }, /*'verified' => 1 */])) {
            $user = auth()->user();
        }

        // $user->update(['device_token' => $request->device_token]);

        $success['user'] = new UserResource($user);
        $success['token'] = $user->createToken('authToken')->accessToken;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح', 'Login Successfully');

    }

    public function login(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_name' => 'string|required',
            'password' => 'string|required',
            //'device_token' => 'string|required',
        ]);

        if ($validator->fails()) {
           
           
            return $this->sendError(null, $validator->errors());
        }
        
        if (
            !auth()->guard()->attempt(['email' => $request->user_name, 'password' => $request->password, 'is_deleted' => 0,'status' => 'active', 'user_type' => function ($query) {
       $query->whereIn('user_type',  ['store','store_employee']);
  }
  ])
            && !auth()->guard()->attempt(['user_name' => $request->user_name, 'password' => $request->password, 'is_deleted' => 0,'status' => 'active', 'user_type' => function ($query) {
       $query->whereIn('user_type',  ['store','store_employee']);
  }])
        ) {
            return $this->sendError('خطأ في اسم المستخدم أو كلمة المرور', 'Invalid Credentials');
        } elseif (
            !auth()->guard()->attempt(['email' => $request->user_name, 'password' => $request->password, 'is_deleted' => 0,'status' => 'active', 'user_type' => function ($query) {
       $query->whereIn('user_type',  ['store','store_employee']);
  }, 'verified' => 1])
            && !auth()->guard()->attempt(['user_name' => $request->user_name, 'password' => $request->password, 'is_deleted' => 0,'status' => 'active', 'user_type' => function ($query) {
       $query->whereIn('user_type',  ['store','store_employee']);
  }, 'verified' => 1])
        ) {
            $user_name =$request->user_name;
            $user = User::whereIn('user_type',  ['store','store_employee']) ->where(function($query) use ($user_name) {
                    $query->where('user_name', $user_name)->orWhere('email', $user_name);
                })
                ->first();

            if ($user) {

                $user->generateVerifyCode();
                $request->code = $user->verify_code;
                $request->phonenumber = $user->phonenumber;
                $this->sendSms($request); // send and return its response
            }

            return $this->sendError('الحساب غير محقق', 'User not verified');
        }
        // $remember = request('remember');
        
        

        if (auth()->guard()->attempt(['email' => $request->user_name, 'password' => $request->password, 'is_deleted' => 0,'status' => 'active', 'user_type' => function ($query) {
       $query->whereIn('user_type',  ['store','store_employee']);
  }, 'verified' => 1]) || auth()->guard()->attempt(['user_name' => $request->user_name, 'password' => $request->password, 'is_deleted' => 0,'status' => 'active', 'user_type' => function ($query) {
       $query->whereIn('user_type',  ['store','store_employee']);
  }, 'verified' => 1])) {
            $user = auth()->user();

        }

        //$user->update(['device_token' => $request->device_token]);

        $success['user'] = new UserResource($user);
        $success['token'] = $user->createToken('authToken')->accessToken;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح', 'Login Successfully');
        // }
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
        $validator = Validator::make($input, [
            'user_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
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
        $validator = Validator::make($input, [
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
            if ($decoded->status == "S") {
                return true;
            }

            return $this->sendError("فشل ارسال الرسالة", "Failed Send Message");

        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

    }

// test
    public function sendMessagePost()
    {
        $curl = curl_init();

        // public function sendMessage()
        // {
        //     $curl = curl_init();

        //     curl_setopt_array($curl, array(
        //       CURLOPT_URL => 'http://REST.GATEWAY.SA/api/SendSMS?api_id=API72154753454&api_password=Fazit@123&sms_type=T&encoding=T&sender_id=MASHAHIR&phonenumber=966550295508&textmessage=test&uid=xyz',
        //       CURLOPT_RETURNTRANSFER => true,
        //       CURLOPT_ENCODING => '',
        //       CURLOPT_MAXREDIRS => 10,
        //       CURLOPT_TIMEOUT => 0,
        //       CURLOPT_FOLLOWLOCATION => true,
        //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //       CURLOPT_CUSTOMREQUEST => 'GET',
        //     ));

        //     $response = curl_exec($curl);

        //     curl_close($curl);
        //     echo $response;

        // }
        // public function sendMessagePost()
        // {
        //     $curl = curl_init();

        //     curl_setopt_array($curl, array(
        //       CURLOPT_URL => 'https://rest.gateway.sa/api/SendSMS',
        //       CURLOPT_RETURNTRANSFER => true,
        //       CURLOPT_ENCODING => '',
        //       CURLOPT_MAXREDIRS => 10,
        //       CURLOPT_TIMEOUT => 0,
        //       CURLOPT_FOLLOWLOCATION => true,
        //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //       CURLOPT_CUSTOMREQUEST => 'POST',
        //       CURLOPT_POSTFIELDS =>'{
        //     "api_id":"API72154753454",
        //     "api_password":"Gateway@123",
        //     "sms_type": "T",
        //     "encoding":"T",
        //     "sender_id": "MASHAHIR",
        //     "phonenumber": "966507717470",
        //     "textmessage":"test message",
        //     "uid":"xyz"

        //     }
        //     ',
        //       CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json'
        //       ),
        //     ));

        //     $response = curl_exec($curl);

        //     curl_close($curl);
        //     echo $response;

    }

    public function social_mobile(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
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
