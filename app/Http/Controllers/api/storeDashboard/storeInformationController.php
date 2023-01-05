<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class storeInformationController extends BaseController
{

      public function __construct()
    {
        $this->middleware('auth:api');
    }
  public function socialMedia_store_show()
    {
        $success['snapchat']=Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->pluck('snapchat')->first();
        $success['facebook']=Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->pluck('facebook')->first();
        $success['twiter']=Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->pluck('twiter')->first();
        $success['youtube']=Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->pluck('youtube')->first();
        $success['instegram']=Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->pluck('instegram')->first();
        $success['status']= 200;

         return $this->sendResponse($success,'تم عرض صفحات التواصل بنجاح','social Media shown successfully');
    }


    public function socialMedia_store_update(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input ,[
           'snapchat'=>'required|url',
           'facebook'=>'required|url',
           'twiter'=>'required|url',
           'youtube'=>'required|url',
           'instegram'=>'required|url'
        ]);
        if ($validator->fails())
        {
           # code...
           return $this->sendError(null,$validator->errors());
        }
        $socialMedia = Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->first();
        $socialMedia->update([
            'snapchat' =>  $request->input('snapchat'),
            'facebook' =>  $request->input('facebook'),
            'twiter' =>  $request->input('twiter'),
            'youtube' =>  $request->input('youtube'),
            'instegram' =>  $request->input('instegram')
        ]);
        $success['snapchat']=Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->pluck('snapchat')->first();
        $success['facebook']=Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->pluck('facebook')->first();
        $success['twiter']=Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->pluck('twiter')->first();
        $success['youtube']=Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->pluck('youtube')->first();
        $success['instegram']=Store::where('is_deleted',0)->where('id',auth()->user()->store_id)->pluck('instegram')->first();
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل صفحات التواصل بنجاح','social Media update successfully');
    }
}
