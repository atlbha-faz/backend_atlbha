<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\NotificationResource;
use App\Http\Controllers\api\BaseController as BaseController;
class NotificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $success['Notifications']=NotificationResource::collection(Notification::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع  الاشعارات بنجاح','Notifications return successfully');
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
            'description'=>'required|string',
            'notification_time'=>'required|date',
            'notificationtype_id'=>'required|exists:notification_types,id',
            'user_id'=>'required|exists:users,id',
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $notification = Notification::create([
            'description' => $request->description,
            'notification_time' => $request->notification_time,
            'notificationtype_id' => $request->notificationtype_id,
            'user_id' => $request->user_id,
          ]);


         $success['notifications']=New NotificationResource($notification);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة الاشعار بنجاح','notification Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification= Notification::query()->find($id);
        if ($notification->is_deleted==1){
               return $this->sendError(" الاشعار غير موجودة","Notification is't exists");
               }
              $success['notifications']=New NotificationResource($notification);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض  الاشعار بنجاح','Notification showed successfully');
    }
    public function changeStatus($id)
    {
        $notification = Notification::query()->find($id);
        if ($notification->is_deleted==1){
         return $this->sendError(" نوع الاشعار غير موجودة","Notification is't exists");
         }
        if($notification->status === 'active'){
            $notification->update(['status' => 'not_active']);
     }
    else{
        $notification->update(['status' => 'active']);
    }
        $success['notifications']=New NotificationResource($notification);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة نوع الاشعار بنجاح',' Notification status updared successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $notification)
    {
         $notification = Notification::query()->find($notification);

        if ($notification->is_deleted==1){
            return $this->sendError(" الاشعار غير موجودة"," notification is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
            'description'=>'required|string',
            'notification_time'=>'required|date',
            'notificationtype_id'=>'required|exists:notification_types,id',
            'user_id'=>'required|exists:users,id',

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $notification->update([
               'description' => $request->input('description'),
               'notification_time' => $request->input('notification_time'),
               'notificationtype_id' => $request->input('notificationtype_id'),
               'user_id' => $request->input('user_id'),
           ]);

           $success['notification']=New NotificationResource($notification);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','notification updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $notification =Notification::query()->find($id);
        if ($notification->is_deleted==1){
            return $this->sendError(" الاشعار غير موجودة","notification is't exists");
            }
           $notification->update(['is_deleted' => 1]);

           $success['notifications']=New NotificationResource($notification);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف  الاشعار بنجاح','notification deleted successfully');
    }
}
