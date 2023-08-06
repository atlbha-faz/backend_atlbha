<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function setting_store_show()
    {
        $success['setting_store'] = new StoreResource(Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الاعدادات بنجاح', 'registration_status shown successfully');
    }

    public function setting_store_update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'description' => 'required|string',
            'store_address' => 'nullable|string',
            'domain' => 'required|string|unique:stores,domain,' . auth()->user()->store_id,
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'store_email' => 'required|email|unique:stores,store_email,' . auth()->user()->store_id,
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', 'unique:stores,store_email,' . auth()->user()->store_id],
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $settingStore = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $settingStore->update([
            'icon' => $request->icon,
            'logo' => $request->logo,
            'description' => $request->input('description'),
            'domain' => $request->input('domain'),
            'country_id' => $request->input('country_id'),
            'city_id' => $request->input('city_id'),
            'store_email' => $request->input('store_email'),
            'store_address' => $request->input('store_address'),
            'phonenumber' => $request->input('phonenumber'),
        ]);
        $success['storeSetting'] = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل الاعدادات بنجاح', ' update successfully');
    }

}
