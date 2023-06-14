<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\User;
use App\Models\Store;
use App\Models\Service;
use App\Models\Websiteorder;
use Illuminate\Http\Request;
use App\Events\VerificationEvent;
use App\Models\Service_Websiteorder;
use App\Http\Resources\StoreResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\WebsiteorderResource;
use Illuminate\Support\Facades\Notification;
use App\Notifications\verificationNotification;
use App\Http\Controllers\api\BaseController as BaseController;

class WebsiteorderController extends BaseController
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
        $success['count_of_store_order']=Websiteorder::where('is_deleted',0)->where('type','store')->count();

         $array_store = array();
        $i = date("Y-m");
        $x = 1;
        while($x <= 6){
            $array_store[$i]["store"]= Websiteorder::where('is_deleted',0)->where('type','store')->whereYear('created_at', date('Y', strtotime($i)))->whereMonth('created_at', date('m', strtotime($i)))->count();
           $i = date("Y-m", strtotime("-1 month", strtotime($i)));
            $x++;
        }
        $success['array_store']= $array_store;




        $success['count_of_serivces_order']=Websiteorder::where('is_deleted',0)->where('type','service')->count();
        $success['count_of_Design']=Websiteorder::where('is_deleted',0)->where('type','service')->whereHas('services', function($q){
   $q->where('service_id',1);
})->count();
        $success['count_of_TechnicalSupport']=Websiteorder::where('is_deleted',0)->where('type','service')->whereHas('services', function($q){
    $q->where('service_id',2);
})->count();
        $success['count_of_celebrities']=Websiteorder::where('is_deleted',0)->where('type','service')->whereHas('services', function($q){
    $q->where('service_id',3);
})->count();



        $success['Websiteorder']=WebsiteorderResource::collection(Websiteorder::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع الطلبات بنجاح','Websiteorder return successfully');
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


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Websiteorder  $websiteorder
     * @return \Illuminate\Http\Response
     */
    public function show($websiteorder)
    {
        $websiteorder =Websiteorder::query()->find($websiteorder);
        if (is_null($websiteorder) || $websiteorder->is_deleted==1){
               return $this->sendError("  الطلب غير موجودة","websiteorder is't exists");
               }
              $success['websiteorders']=New WebsiteorderResource($websiteorder);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض  الطلب  بنجاح','websiteorder showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Websiteorder  $websiteorder
     * @return \Illuminate\Http\Response
     */
    public function edit(Websiteorder $websiteorder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Websiteorder  $websiteorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $websiteorder)
    {
        $websiteorder =Websiteorder::query()->find($websiteorder);
        if ( is_null($websiteorder) || $websiteorder->is_deleted==1){
            return $this->sendError(" الطلب غير موجودة"," websiteorder is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
                'type'=>'required|string|max:255',
                'sevices'=>'exists:services,id'

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $websiteorder->update([
               'type' => $request->input('type'),


           ]);
         if($request->sevices!=null){
         $websiteorder->services_websiteorders()->sync(explode(',', $request->sevices),['status'=>$request->status]);
           }
           $success['websiteorders']=New WebsiteorderResource($websiteorder);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','websiteorder updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Websiteorder  $websiteorder
     * @return \Illuminate\Http\Response
     */
    public function destroy( $websiteorder)
    {
        $websiteorder =Websiteorder::query()->find($websiteorder);
        if (is_null($websiteorder)||$websiteorder->is_deleted==1){
            return $this->sendError(" الطلب غير موجودة","websiteorder is't exists");
            }
           $websiteorder->update(['is_deleted' => 1]);

           $success['websiteorders']=New WebsiteorderResource($websiteorder);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف  الطلب بنجاح','websiteorder deleted successfully');
    }

    public function deleteall(Request $request)
    {
            $websiteorders =Websiteorder::whereIn('id',$request->id)->where('is_deleted',0)->get();
            if(count($websiteorders)>0){
           foreach($websiteorders as $websiteorder)
           {
             $websiteorder->update(['is_deleted' => 1]);
            $success['websiteorder']=New WebsiteorderResource($websiteorder);

            }

           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف الطلب بنجاح','websiteorder deleted successfully');
             }
             else{
                $success['status']= 200;
            return $this->sendResponse($success,'المدخلات غير صحيحة','id does not exit');
            }
             }


           public function acceptStore($websiteorder)
           {
            $websiteorder =Websiteorder::query()->where('type','store')->find($websiteorder);
            if (is_null($websiteorder) || $websiteorder->is_deleted==1){
                return $this->sendError("الطلب غير موجود","Order is't exists");
                }
               $store = Store::query()->find($websiteorder->store_id);

               $websiteorder->update(['status' => 'accept']);
               $store->update(['confirmation_status' => 'accept']);
               $users = User::where('store_id', $store->id)->get();
               $data = [
                   'message' => ' تم قبول الطلب',
                   'store_id' => $websiteorder->store_id,
                   'user_id'=>auth()->user()->id,
                   'type'=>"accept",
                   'object_id'=>$websiteorder->store_id
               ];

               foreach($users as $user)
               {
               Notification::send($user, new verificationNotification($data));
               }

               event(new VerificationEvent($data));
               $success['store']=New StoreResource($store);
               $success['status']= 200;

                return $this->sendResponse($success,'تم قبول الطلب بنجاح',' accept successfully');

           }

           public function rejectStore($websiteorder)
           {
            $websiteorder =Websiteorder::query()->where('type','store')->find($websiteorder);
            if (is_null($websiteorder) || $websiteorder->is_deleted==1){
                return $this->sendError("الطلب غير موجود","Order is't exists");
                }
               $store = Store::query()->find($websiteorder->store_id);

               $websiteorder->update(['status' => 'reject']);
               $store->update(['confirmation_status' => 'reject']);
               $users = User::where('store_id', $store->id)->get();
               $data = [
                   'message' => ' تم رفض الطلب',
                   'store_id' => $websiteorder->store_id,
                   'user_id'=>auth()->user()->id,
                   'type'=>"reject",
                   'object_id'=>$websiteorder->store_id
               ];

               foreach($users as $user)
               {
               Notification::send($user, new verificationNotification($data));
               }

               event(new VerificationEvent($data));
               $success['store']=New StoreResource($store);
               $success['status']= 200;

                return $this->sendResponse($success,'تم رفض الطلب بنجاح','reject successfully');

           }

           public function acceptService($websiteorder)
           {
            $websiteorder =Websiteorder::query()->where('type','service')->find($websiteorder);
            if (is_null($websiteorder) || $websiteorder->is_deleted==1){
                return $this->sendError("الطلب غير موجود","Order is't exists");
                }
               $services= Service_Websiteorder::where('websiteorder_id',$websiteorder->id)->get();

               foreach( $services as  $service){
                $service->update(['status' => 'accept']);
              }
               $websiteorder->update(['status' => 'accept']);

               $users = User::where('store_id', $websiteorder->store_id)->get();
               $data = [
                   'message' => ' تم قبول الخدمة',
                   'store_id' =>$websiteorder->store_id,
                   'user_id'=>auth()->user()->id,
                   'type'=>"service_accept",
                   'object_id'=>$websiteorder->store_id
               ];

               foreach($users as $user)
               {
               Notification::send($user, new verificationNotification($data));
               }

               event(new VerificationEvent($data));
               $success['websiteorder']= New WebsiteorderResource($websiteorder);
               $success['status']= 200;

                return $this->sendResponse($success,'تم قبول الطلب بنجاح',' accept successfully');

           }


           public function rejectService($websiteorder)
           {
            $websiteorder =Websiteorder::query()->where('type','service')->find($websiteorder);
            if (is_null($websiteorder) || $websiteorder->is_deleted==1){
                return $this->sendError("الطلب غير موجود","Order is't exists");
                }
                $services= Service_Websiteorder::where('websiteorder_id',$websiteorder->id)->get();
                foreach( $services as  $service){
                  $service->update(['status' => 'reject']);
                }
               $websiteorder->update(['status' => 'reject']);
               $users = User::where('store_id', $websiteorder->store_id)->get();
               $data = [
                   'message' => ' تم رفض الخدمة',
                   'store_id' =>$websiteorder->store_id,
                   'user_id'=>auth()->user()->id,
                   'type'=>"service_reject",
                   'object_id'=>$websiteorder->store_id
               ];

               foreach($users as $user)
               {
               Notification::send($user, new verificationNotification($data));
               }

               event(new VerificationEvent($data));
               $success['websiteorder']= New WebsiteorderResource($websiteorder);
               $success['status']= 200;

                return $this->sendResponse($success,'تم رفض الطلب بنجاح',' reject successfully');

           }


}
