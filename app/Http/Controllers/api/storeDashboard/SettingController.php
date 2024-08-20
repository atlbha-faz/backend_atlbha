<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Events\VerificationEvent;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\StoreResource;
use App\Models\Day_Store;
use App\Models\Homepage;
use App\Models\Store;
use App\Models\User;
use App\Models\Websiteorder;
use App\Notifications\verificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SettingController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function settingStoreShow()
    {
        // dd(auth()->user()->store_id);
        $success['setting_store'] = new StoreResource(Store::with(['categories' => function ($query) {
            $query->select('name');
        }])->where('is_deleted', 0)->where('id', auth()->user()->store_id)->first());

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الاعدادات بنجاح', 'registration_status shown successfully');
    }

    public function settingStoreUpdate(Request $request)
    {
        $store = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $input = $request->all();

        $validator = Validator::make($input, [
            'icon' => ['nullable'],
            'logo' => ['nullable'],
            'description' => 'required|string',
            'store_address' => 'nullable|string',
            'store_name' => 'required|string',
            'domain_type' => 'required|in:has_domain,pay_domain,later_time',
            'domain' => ['required_unless:domain_type,later_time', 'string', Rule::unique('stores')->where(function ($query) {
                return $query->where('is_deleted', 0)->where('id', '!=', auth()->user()->store_id);
            })],
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'store_email' => ['required', 'email', Rule::unique('stores')->where(function ($query) {
                return $query->where('is_deleted', 0)->where('id', '!=', auth()->user()->store_id);
            })],
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('stores')->where(function ($query) use ($store) {
                return $query->where('is_deleted', 0)->where('id', '!=', $store->id);
            })],
            'data' => 'nullable|array',
            'data.*.status' => 'in:active,not_active',
            'data.*.id' => 'required',
            'data.*.from' => 'required_if:status,active',
            'data.*.to' => 'required_if:status,active',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $user = User::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->where('user_type', 'store')->first();
        $validator2 = Validator::make($input, [
            'store_email' => ['required', 'email', Rule::unique('users', 'email')->where(function ($query) use ($user) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0)
                    ->where('id', '!=', $user->id);
            }),
            ],
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) use ($user) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0)
                    ->where('id', '!=', $user->id);
            }),
            ],
        ]);
        if ($validator2->fails()) {
            # code...
            return $this->sendError(null, $validator2->errors());
        }
        $settingStore = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $request->working_status = 'active';
        if (in_array($request->domain_type, ['pay_domain', 'has_domain'])) {
            if ($request->domain != $settingStore->domain) {
                if ($request->domain_type == "pay_domain") {
                    $this->addDomainRequest(18, 1);
                } else {
                    $this->addDomainRequest(19, 0);
                }
            }
        }
        $settingStore->update([
            'description' => $request->input('description'),
            'domain' => $request->input('domain'),
            'country_id' => $request->input('country_id'),
            'city_id' => $request->input('city_id'),
            'store_email' => $request->input('store_email'),
            'store_name' => $request->input('store_name'),
            'store_address' => \App\Models\Country::find($request->input('country_id'))->name . '-' . \App\Models\City::find($request->input('city_id'))->name,
            'phonenumber' => $request->input('phonenumber'),
            'working_status' => $request->working_status,
            'domain_type' => $request->domain_type,
        ]);
        $parameters = ['icon', 'logo'];

        if ($request->has('logo')) {
            $settingStore->update([
                'logo' => $request->logo]);
        }
        if ($request->has('icon')) {
            $settingStore->update([
                'icon' => $request->icon,
            ]);
        }
        $store_user = User::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->where('user_type', 'store')->first();
        $store_user->update([
            'email' => $request->input('store_email'),
            'phonenumber' => $request->input('phonenumber'),

        ]);

        $logohomepage = Homepage::updateOrCreate([
            'store_id' => auth()->user()->store_id,
        ], [
            'logo' => $request->logo,
        ]);
        if ($request->working_status == 'active') {

            if (!is_null($request->data)) {
                foreach ($request->data as $data) {
                    if ($data['status'] == "not_active") {
                        $workdays = Day_Store::updateOrCreate([
                            'store_id' => auth()->user()->store_id,
                            'day_id' => $data['id'],
                        ], [
                            'status' => $data['status'],
                            'from' => null,
                            'to' => null,

                        ]);

                    } else {
                        $workdays = Day_Store::updateOrCreate([
                            'store_id' => auth()->user()->store_id,
                            'day_id' => $data['id'],
                        ], [
                            'status' => $data['status'],
                            'from' => $data['from'],
                            'to' => $data['to'],

                        ]);
                    }
                }
            }
        }
        $success['storeSetting'] = new StoreResource(Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first());
        $success['setting_store'] = new StoreResource(Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first());

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل الاعدادات بنجاح', ' update successfully');
    }

    public function checkDomain(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'domain' => ['required', 'string', Rule::unique('stores')->where(function ($query) {
                return $query->where('is_deleted', 0)->where('id', '!=', auth()->user()->store_id);
            })],
        ]);
        if ($validator->fails()) {
            # code...

            return $this->sendError(null, $validator->errors());
        }

        $success['status'] = 200;
        return $this->sendResponse($success, 'دومين صحيح', ' correct domain');

    }
    

}
