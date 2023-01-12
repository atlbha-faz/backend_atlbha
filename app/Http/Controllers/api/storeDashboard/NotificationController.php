<?php

namespace App\Http\Controllers\api\storeDashboard;
use Carbon\Carbon;
use App\Models\NotificationModel;
use Illuminate\Http\Request;
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
        $success['notifications']=auth()->user()->Notifications;
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
       
        $success['notifications']=$userNotification;
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

    public function deleteNotificationAll()
    {
        $notifications = auth()->user()->Notifications;
     foreach($notifications  as $notification ){
           $notification->delete();
     }
           $success['status']= 200;
            return $this->sendResponse($success,'تم حذف الاشعار بنجاح','notification deleted successfully');
    }
   
    }

