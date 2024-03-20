<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $success['settings'] = new SettingResource(Setting::where('is_deleted', 0)->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الاعدادات بنجاح', 'settings return successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show($setting)
    {
        $setting = Setting::query()->find($setting);
        if (is_null($setting) || $setting->is_deleted != 0) {
            return $this->sendError("الاعدادات غير موجودة", "settings is't exists");
        }

        $success['settings'] = new SettingResource($setting);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بنجاح', 'setting showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $setting)
    {
        $setting = Setting::query()->find($setting);
        if (is_null($setting) || $setting->is_deleted != 0) {
            return $this->sendError("الاعدادات غير موجودة", "setting is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'required|url',
            'email' => 'required|email|unique:settings,email,' . $setting->id,
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
            'address' => 'required|string',
           

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $setting->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'link' => $request->input('link'),
            'email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
            'logo' => $request->input('logo'),
       
            'address' => $request->input('address'),
   

        ]);

        $success['settings'] = new SettingResource($setting);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'setting updated successfully');
    }

    public function changeStatus($id)
    {
        $setting = Setting::query()->find($id);
        if (is_null($setting) || $setting->is_deleted != 0) {
            return $this->sendError("الاعدادات غير موجودة", "setting is't exists");
        }

        if ($setting->status === 'active') {
            $setting->update(['status' => 'not_active']);
        } else {
            $setting->update(['status' => 'active']);
        }
        $success['$setting'] = new SettingResource($setting);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة الاعدادات بنجاح', 'setting updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */

    public function registration_status_show()
    {
        $success['registration_status'] = Setting::where('is_deleted', 0)->pluck('registration_status')->first();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الاعدادات بنجاح', 'registration_status shown successfully');
    }

    public function registration_status_update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'registration_status' => 'required|in:stop_registration,registration_with_admin,registration_without_admin',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $setting = Setting::where('is_deleted', 0)->first();
        $setting->update(['registration_status' => $request->input('registration_status')]);
        $success['registration_status'] = Setting::where('is_deleted', 0)->pluck('registration_status')->first();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل الاعدادات بنجاح', 'registration_status update successfully');
    }

    public function registrationMarketer(Request $request)
    {
        $registrationMarketer = Setting::query()->where('is_deleted', 0)->first();
        if (is_null($registrationMarketer) || $registrationMarketer->is_deleted != 0) {
            return $this->sendError("الحالة غير موجودة", "registrationMarketer is't exists");
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'registration_marketer' => 'required|in:active,not_active',
            'status_marketer' => 'required|in:active,not_active',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $registrationMarketer->update(['registration_marketer' => $request->registration_marketer,
            'status_marketer' => $request->status_marketer]);

        $success['registration_marketer'] = Setting::where('is_deleted', 0)->pluck('registration_marketer')->first();
        $success['status_marketer'] = Setting::where('is_deleted', 0)->pluck('status_marketer')->first();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل الحالة  بنجاح', 'status updated successfully');

    }

    public function registration_marketer_show()
    {
        $success['registration_marketer'] = Setting::where('is_deleted', 0)->pluck('registration_marketer')->first();
        $success['status_marketer'] = Setting::where('is_deleted', 0)->pluck('status_marketer')->first();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض حالات تسجيل المندوبين بنجاح', 'registration_marketer shown successfully');
    }

}
