<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Store;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Resources\StoreResource;
use App\Http\Resources\SettingResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class SettingController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }





    public function setting_store_show()
    {
        $success['setting_store']=new StoreResource(Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->first());
        $success['status']= 200;

         return $this->sendResponse($success,'تم عرض الاعدادات بنجاح','registration_status shown successfully');
    }


    public function setting_store_update(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input ,[
                'icon'=>['nullable','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
                'logo'=>['nullable','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
                'description'=>'required|string',
                'domain'=>'required|string|unique:stores,domain,' . auth()->user()->store_id,
                 'country_id'=>'required|exists:countries,id',
                 'city_id'=>'required|exists:cities,id',
        ]);
        if ($validator->fails())
        {
           # code...
           return $this->sendError(null,$validator->errors());
        }
        $settingStore = Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->first();
        $settingStore->update([
            'icon' =>  $request->icon,
            'logo' =>  $request->logo,
            'description' =>  $request->input('description'),
            'domain' =>  $request->input('domain'),
            'country_id' =>  $request->input('country_id'),
            'city_id' =>  $request->input('city_id'),
        ]);
        $success['storeSetting']=Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->first();
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل الاعدادات بنجاح',' update successfully');
    }



}
