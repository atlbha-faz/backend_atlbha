<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\NotificationResource;
use App\Models\NotificationModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $count= ($request->has('number') && $request->input('number') !== null)? $request->input('number'):10;
        $success['count_of_notifications'] = auth()->user()->Notifications->where('read_at', null)->count();

        $userNotifications = auth()->user()->notifications()->paginate($count);

        $notifications = NotificationResource::collection($userNotifications);

        // $notifications = NotificationResource::collection(auth()->user()->Notifications->paginate(5));
        $success['page_count'] = $notifications->lastPage();
        $pageNumber = request()->query('page', 1);
        $success['current_page'] = $notifications->currentPage();
        $success['notifications'] = $notifications;

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع جميع الاشعارات بنجاح', 'Notifications return successfully');
    }
    public function read(Request $request)
    {
        if ($request->has('id')) {
            $userUnreadNotifications = NotificationModel::query()->whereIn('id', $request->id)->get();
            foreach ($userUnreadNotifications as $userUnreadNotification) {
                $userUnreadNotification->update(['read_at' => Carbon::now()]);
            }
            $success['notifications'] = new NotificationResource($userUnreadNotification);
        } else {
            $userUnreadNotifications = NotificationModel::query()->where('read_at', null)->get();
            foreach ($userUnreadNotifications as $userUnreadNotification) {
                $userUnreadNotification->update(['read_at' => Carbon::now()]);
            }
        }
        if ($request->has('data')) {
            $userNotifications = auth()->user()->Notifications;

            $notifications = NotificationResource::collection($userNotifications);

            $success['status'] = 200;

            // $notifications = NotificationResource::collection(auth()->user()->Notifications->paginate(5));

            $success['notifications'] = $notifications;
        } else {
            $success['count_of_notifications'] = 0;
            $success['status'] = 200;

        }

        return $this->sendResponse($success, 'تم ارجاع  الاشعار بنجاح', 'Notifications return successfully');
    }
    public function show($id)
    {
        $userNotification = NotificationModel::query()->find($id);

        $success['notifications'] = new NotificationResource($userNotification);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع  الاشعار بنجاح', 'Notifications return successfully');
    }
    public function deleteNotification($id)
    {
        $notification = NotificationModel::query()->find($id);
        if (is_null($notification)) {
            return $this->sendError("الاشعار غير موجود", "notification is't exists");
        }
        $notification->delete();

        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف الاشعار بنجاح', 'notification deleted successfully');
    }

    public function deleteNotificationAll()
    {
        $notifications = auth()->user()->Notifications;
        foreach ($notifications as $notification) {
            $notification->delete();
        }
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف الاشعار بنجاح', 'notification deleted successfully');
    }

    public function countUnRead(Request $request)
    {
        $success['count_of_notifications'] = auth()->user()->Notifications->where('read_at', null)->count();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع جميع الاشعارات بنجاح', 'Notifications return successfully');
    }
}
