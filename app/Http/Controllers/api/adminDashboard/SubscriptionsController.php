<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Note;
use App\Models\User;
use App\Models\Alert;
use App\Models\Store;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Package_store;
use App\Events\VerificationEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\NoteResource;
use App\Http\Resources\AlertResource;
use App\Http\Resources\StoreResource;
use App\Notifications\emailNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\SubscriptionsResource;
use App\Notifications\verificationNotification;
use App\Http\Controllers\api\BaseController as BaseController;

class SubscriptionsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {

        $success['stores']=SubscriptionsResource::collection(Store::where('is_deleted',0,)->where('package_id','!=',null)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المتاجر بنجاح','Subscriptions return successfully');
    }
    public function deleteall(Request $request)
    {

            $stores =Store::whereIn('id',$request->id)->where('is_deleted',0)->get();
            if(count($stores)>0){
           foreach($stores as $store)
           {
            
              $store_package=Package_store::where('package_id',$store->package_id)->where('store_id',$store->id)->orderBy('id', 'DESC')->first();
             
             if (is_null($store_package) || $store_package->is_deleted==1){
            }
            else{
             $store_package->update(['is_deleted' => 1]);
            }
            if( $store->package_id != null){
                $store->update(['package_id' => null]);
                $store->update(['periodtype' => null]);
                $store->update(['left' =>0]);
                }
            $success['Subscriptions']=New SubscriptionsResource($store);

            }

           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف الاشتراك بنجاح','Subscriptions deleted successfully');
        }
        else{
            $success['status']= 200;
         return $this->sendResponse($success,'المدخلات غيرموجودة','id is not exit');
          }
    }
       public function changeSatusall(Request $request)
            {

                    $stores =Store::whereIn('id',$request->id)->where('is_deleted',0)->get();
                    if(count($stores)>0){
                    foreach($stores as $store)
                {
               
              $store_package=Package_store::where('package_id',$store->package_id)->where('store_id',$store->id)->orderBy('id', 'DESC')->first();
            //   if( $store->package_id != null){
            //   $store->update(['package_id' => null]);
            //   }
              if (is_null($store_package) || $store_package->is_deleted==1){
              }
              else{
                    if($store_package->status === 'active'){
                        $store_package->update(['status' => 'not_active']);
                }
                else{
                    $store_package->update(['status' => 'active']);
                }
            }
                $success['Subscriptions']= New SubscriptionsResource($store);

                    }
                    $success['status']= 200;

                return $this->sendResponse($success,'تم تعديل حالة الاشتراك بنجاح','Subscriptions updated successfully');
           }
           else{
            $success['status']= 200;
         return $this->sendResponse($success,'المدخلات غيرموجودة','id is not exit');
          }
        }
           public function addAlert(Request $request)
           {
               $input = $request->all();
               $validator =  Validator::make($input ,[
                   'type'=>'required|in:now,after',
                   'subject'=>'required|string|max:255',
                   'message'=>'required|string',
                   'store_id'=>'exists:stores,id',
       
               ]);
               if ($validator->fails())
               {
                   return $this->sendError(null,$validator->errors());
               }
               
               $data = [
                   'subject' => $request->subject,
                   'message' => $request->message,
                   'store_id' => $request->store_id,
             
               ];
               if($request->type =="now"){
               $alert= Alert::create( [ 
               'subject' => $request->subject,
               'message' => $request->message,
               'store_id' => $request->store_id]);
               }
               else{
                   $alert= Alert::create( [ 
                   'subject' => $request->subject,
                   'message' => $request->message,
                   'store_id' => $request->store_id,
                   'start_at' =>$request->start_at,
                   'end_at' =>$request->end_at,
               ]);
       
               }
               $users = User::where('store_id',$request->store_id)->where('user_type','store')->get();
              foreach($users as  $user)
              {
                 Notification::send($user , new emailNotification($data));
              }
                $success['alerts']=New AlertResource($alert);
               $success['status']= 200;
       
                return $this->sendResponse($success,'تم إضافة بنجاح',' Added successfully');
           }
       
}
