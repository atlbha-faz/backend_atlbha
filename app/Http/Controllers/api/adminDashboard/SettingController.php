<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Resources\SettingResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class SettingController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
   {

        $success['settings']=SettingResource::collection(Setting::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الاعدادات بنجاح','settings return successfully');
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'description'=>'required|string',
            'link'=>'required|url',
            'email'=>'required|email|unique:settings',
            'phoneNumber'=>'required|numeric',
            'logo'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'icon'=>'required',
            'address'=>'required|string',
            'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $setting = Setting::create([
            'name' => $request->name,
            'description'=>$request->description,
            'link' =>$request->link,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'logo'=>$request->logo,
            'icon' =>$request->icon,
            'address' => $request->address,
            'country_id' =>$request->country_id,
            'city_id' => $request->city_id,
          ]);


         $success['settings']=New SettingResource( $setting);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة الاعدادات بنجاح',' setting Added successfully');
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
         if ($setting->is_deleted == 1){
         return $this->sendError("الاعدادات غير موجودة","settings is't exists");
         }


        $success['settings']=New SettingResource($setting);
        $success['status']= 200;

         return $this->sendResponse($success,'تم عرض بنجاح','setting showed successfully');
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
    public function update(Request $request, Setting $setting)
       {
         if ($setting->is_deleted==1){
         return $this->sendError("\الاعداداتغير موجودة","setting is't exists");
          }
         $input = $request->all();
         $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'description'=>'required|string',
            'link'=>'required|url',
             'email'=>'required|email|unique:settings',
            'phoneNumber'=>'required|numeric',
            'logo'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'icon'=>['required','image','mimes:ico','max:2048'],
            'address'=>'required|string',
            'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',
         ]);
         if ($validator->fails())
         {
            # code...
            return $this->sendError(null,$validator->errors());
         }
         $setting->update([
             'name' => $request->input('name'),
             'description' => $request->input('description'),
             'link' => $request->input('link'),
             'email' => $request->input('email'),
             'phoneNumber' => $request->input('phoneNumber'),
             'logo' => $request->input('logo'),
             'icon' => $request->input('icon'),
             'address' => $request->input('address'),
             'country_id' => $request->input('country_id'),
             'city_id' => $request->input('city_id'),

         ]);
         //$country->fill($request->post())->update();
            $success['settings']=New SettingResource($setting);
            $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','setting updated successfully');
        }

  public function changeStatus($id)
    {
        $setting = Setting::query()->find($id);
         if ($setting->is_deleted==1){
         return $this->sendError("الاعدادات غير موجودة","setting is't exists");
         }

        if($setting->status === 'active'){
        $setting->update(['status' => 'not_active']);
        }
        else{
        $setting->update(['status' => 'active']);
        }
        $success['$setting']=New SettingResource($setting);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة الاعدادات بنجاح','setting updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy($setting)
    {
       $setting = Setting::query()->find($setting);
         if ($setting->is_deleted==1){
         return $this->sendError("الاعدادات غير موجودة","setting is't exists");
         }
        $setting->update(['is_deleted' => 1]);

        $success['setting']=New SettingResource($setting);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف الاعدادات بنجاح','setting deleted successfully');
    }
}
