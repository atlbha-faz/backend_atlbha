<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\User;
use App\Models\Store;
use App\Models\Marketer;
use App\Models\Websiteorder;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\StoreResource;
use App\Http\Resources\MarketerResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\WebsiteorderResource;
use App\Http\Controllers\api\BaseController as BaseController;

class EtlobhaserviceController extends BaseController
{

       public function __construct()
    {
        $this->middleware('auth:api');
     }

       public function show($id)
    {

      $success['stores']=New StoreResource(Store::where('is_deleted',0,)->where('id',$id)->first());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المتاجر بنجاح','Stores return successfully');
    }
      public function store(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input ,[

            'service_id'=>'array|exists:services,id'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $order_number=Websiteorder::orderBy('id', 'desc')->first();
        if(is_null($order_number)){
        $number = 0001;
        }else{

        $number=$order_number->order_number;
        $number= ((int) $number) +1;
        }
        $websiteorder = Websiteorder::create([
            'type' => 'service',
            'order_number'=> str_pad($number, 4, '0', STR_PAD_LEFT),
            'store_id'=>auth()->user()->store_id,
          ]);
         if($request->service_id!=null){
          $websiteorder->services_websiteorders()->attach($request->service_id);
         }
         $success['Websiteorders']=New WebsiteorderResource($websiteorder );
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة الطلب بنجاح','Websiteorder Added successfully');
    }
    public function marketerRequest($id){
         $success['marketers']=UserResource::collection(User::where('is_deleted',0)->where('city_id',$id)->where('user_type',"marketer")->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المندوبين بنجاح','marketer return successfully');

    }
}
