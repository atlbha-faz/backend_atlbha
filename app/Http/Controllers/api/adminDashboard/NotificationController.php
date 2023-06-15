<?php

namespace App\Http\Controllers\api\adminDashboard;
use Notification;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Alert;
use App\Models\Store;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Models\NotificationModel;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\AlertResource;
use App\Http\Resources\ContactResource;
use App\Notifications\emailNotification;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class NotificationController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
     
        $success['count_of_notifications']=auth()->user()->Notifications->count();
        $success['notifications']=NotificationResource::collection(auth()->user()->Notifications);
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع الاشعارات بنجاح','Notifications return successfully');
    }
    public function read($id){

        $userUnreadNotification =  NotificationModel::query()->find($id);
        $userUnreadNotification->update(['read_at' =>Carbon::now()]);

        $success['notifications']=$userUnreadNotification;
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع  الاشعار بنجاح','Notifications return successfully');
    }
    public function show($id){
        $userNotification =  NotificationModel::query()->find($id);
        if (is_null($userNotification) ){
            return $this->sendError("الاشعار غير موجود"," Notification is't exists");
       }
        $success['notifications']=new NotificationResource($userNotification);
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع  الاشعار بنجاح','Notifications return successfully');
    }
    public function deleteNotification($id)
    {
        $notification = NotificationModel::query()->find($id);
        if (is_null($notification)){
            return $this->sendError("الاشعار غير موجود","notification is't exists");
            }
           $notification->delete();

           $success['status']= 200;
            return $this->sendResponse($success,'تم حذف الاشعار بنجاح','notification deleted successfully');
    }

    public function deleteNotificationAll(Request $request)
    {
        $notifications = NotificationModel::whereIn('id',$request->id)->get();
        if(count($notifications)>0)
        {
     foreach($notifications  as $notification ){
           $notification->delete();
     }
           $success['status']= 200;
            return $this->sendResponse($success,'تم حذف الاشعار بنجاح','notification deleted successfully');
    }
    else{
        $success['status']= 200;
        return $this->sendResponse($success,'الاشعار غير موجود','id does not exit');
        
    }
}

    public function addEmail(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'subject'=>'required|string|max:255',
            'message'=>'required|string|max:255',
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
        $contact = Contact::create($data);
        $users = User::where('store_id',$request->store_id)->where('is_deleted',0)->where('user_type','store')->get();
       foreach($users as  $user)
       {
          Notification::send($user , new emailNotification($data));
       }
         $success['contacts']=New ContactResource($contact);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة بنجاح',' Added successfully');
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
        $users = User::where('store_id',$request->store_id)->where('is_deleted',0)->where('user_type','store')->get();
       foreach($users as  $user)
       {
          Notification::send($user , new emailNotification($data));
       }
         $success['alerts']=New AlertResource($alert);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة بنجاح',' Added successfully');
    }

}
