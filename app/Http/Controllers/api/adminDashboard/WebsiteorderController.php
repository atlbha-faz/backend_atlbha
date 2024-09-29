<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\User;
use App\Models\Store;
use App\Models\Coupon;
use App\Models\Service;
use App\Models\Websiteorder;
use Illuminate\Http\Request;
use App\Events\VerificationEvent;
use App\Models\Service_Websiteorder;
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
    public function index(Request $request)
    {

        $success['count_of_serivces_order'] = Websiteorder::where('is_deleted', 0)->where('type', 'service')->where('payment_status', 'paid')->count();
        $success['count_of_Design'] = Websiteorder::where('is_deleted', 0)->where('type', 'service')->where('payment_status', 'paid')->whereHas('services', function ($q) {
            $q->where('service_id', 1);
        })->count();
        $success['count_of_TechnicalSupport'] = Websiteorder::where('is_deleted', 0)->where('type', 'service')->where('payment_status', 'paid')->whereHas('services', function ($q) {
            $q->where('service_id', 2);
        })->count();
        $success['count_of_celebrities'] = Websiteorder::where('is_deleted', 0)->where('type', 'service')->where('payment_status', 'paid')->whereHas('services', function ($q) {
            $q->where('service_id', 3);
        })->count();
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $data = Websiteorder::where('is_deleted', 0)->where('type', 'service')->where('payment_status', 'paid')->orderByDesc('created_at')->select('id', 'status', 'order_number', 'type', 'name', 'email', 'phone_number', 'total_price', 'coupon_id', 'payment_status', 'payment_method', 'paymentTransectionID','discount_value', 'created_at');
        if ($request->has('filter') && $request->input('filter') !== null) {
            $code = Coupon::where('code', $request->filter)->where('store_id', null)->first();
            if ($code) {
                $data = $data->where('coupon_id', $code->id);
            } else {
                $data = $data->where('coupon_id', "");
            }
        }
        if ($request->has('all') && $request->input('all') !== null) {
            $data = $data->get();
        } else {
            $data = $data->paginate($count);
            $success['page_count'] = $data->lastPage();
            $success['current_page'] = $data->currentPage();
        }
        $success['Websiteorder'] = WebsiteorderResource::collection($data);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع جميع الطلبات بنجاح', 'Websiteorder return successfully');
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
        $websiteorder = Websiteorder::query()->find($websiteorder);
        if (is_null($websiteorder) || $websiteorder->is_deleted != 0) {
            return $this->sendError("  الطلب غير موجودة", "websiteorder is't exists");
        }
        $success['websiteorders'] = new WebsiteorderResource($websiteorder);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض  الطلب  بنجاح', 'websiteorder showed successfully');
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
    public function update(Request $request, $websiteorder)
    {
        $websiteorder = Websiteorder::query()->find($websiteorder);
        if (is_null($websiteorder) || $websiteorder->is_deleted != 0) {
            return $this->sendError(" الطلب غير موجودة", " websiteorder is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'type' => 'required|string|max:255',
            'sevices' => 'exists:services,id',

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $websiteorder->update([
            'type' => $request->input('type'),

        ]);
        if ($request->sevices != null) {
            $websiteorder->services_websiteorders()->sync(explode(',', $request->sevices), ['status' => $request->status]);
        }
        $success['websiteorders'] = new WebsiteorderResource($websiteorder);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'websiteorder updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Websiteorder  $websiteorder
     * @return \Illuminate\Http\Response
     */
    public function destroy($websiteorder)
    {
        $websiteorder = Websiteorder::query()->find($websiteorder);
        if (is_null($websiteorder) || $websiteorder->is_deleted != 0) {
            return $this->sendError(" الطلب غير موجودة", "websiteorder is't exists");
        }
        $websiteorder->update(['is_deleted' => $websiteorder->id]);

        $success['websiteorders'] = new WebsiteorderResource($websiteorder);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف  الطلب بنجاح', 'websiteorder deleted successfully');
    }

    public function deleteAll(Request $request)
    {
        $websiteorders = Websiteorder::whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($websiteorders) > 0) {
            foreach ($websiteorders as $websiteorder) {
                $websiteorder->update(['is_deleted' => $websiteorder->id]);
                $success['websiteorder'] = new WebsiteorderResource($websiteorder);

            }

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم حذف الطلب بنجاح', 'websiteorder deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
    }

    public function acceptService($websiteorder)
    {
        $websiteorder = Websiteorder::query()->where('type', 'service')->find($websiteorder);
        if (is_null($websiteorder) || $websiteorder->is_deleted != 0) {
            return $this->sendError("الطلب غير موجود", "Order is't exists");
        }
        $services = Service_Websiteorder::where('websiteorder_id', $websiteorder->id)->get();
        $serviceName = array();
        foreach ($services as $service) {
            $service->update(['status' => 'accept']);
            $serviceNEW = Service::where('id', $service->service_id)->where('status', 'not_active')->first();
            if ($serviceNEW != null) {
                return $this->sendError("يجب تفعيل الخدمة الجديدة اولا", "Service is't active");
            }
            $serviceName[] = Service::where('id', $service->service_id)->where('is_deleted', 0)->value('name');
        }

        $websiteorder->update(['status' => 'accept']);
        if ($websiteorder->store_id != null) {

            $users = User::where('store_id', $websiteorder->store_id)->whereIn('user_type', ['store_employee', 'store'])->where('is_deleted', 0)->get();
            $data = [
                'message' => ' تم قبول خدمة' . implode(',', $serviceName),
                'store_id' => $websiteorder->store_id,
                'user_id' => auth()->user()->id,
                'type' => "service_accept",
                'object_id' => $websiteorder->store_id,
            ];

            foreach ($users as $user) {
                Notification::send($user, new verificationNotification($data));
                if ($user->device_token !== null) {

                    $fcm = $this->sendFCM($user->device_token,
                        $user->id, 'منصة اطلبها', ' تم قبول خدمة' . implode(',', $serviceName), $user->notifications()->count());

                }

            }
            event(new VerificationEvent($data));
        }
        $success['websiteorder'] = new WebsiteorderResource($websiteorder);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم قبول الطلب بنجاح', ' accept successfully');

    }

    public function rejectService($websiteorder)
    {
        $websiteorder = Websiteorder::query()->where('type', 'service')->find($websiteorder);
        if (is_null($websiteorder) || $websiteorder->is_deleted != 0) {
            return $this->sendError("الطلب غير موجود", "Order is't exists");
        }
        $services = Service_Websiteorder::where('websiteorder_id', $websiteorder->id)->get();
        $serviceName = array();
        foreach ($services as $service) {
            $service->update(['status' => 'reject']);
            $serviceNEW = Service::where('id', $service->service_id)->where('status', 'not_active')->first();
            if ($serviceNEW != null) {
                return $this->sendError("يجب تفعيل الخدمة الجديدة اولا", "Service is't active");
            }
            $serviceName[] = Service::where('id', $service->service_id)->where('is_deleted', 0)->value('name');
        }

        $websiteorder->update(['status' => 'reject']);
        if($websiteorder->store_id != null){
            
        
        $users = User::where('store_id', $websiteorder->store_id)->get();
        $data = [
            'message' => ' تم رفض الخدمة' . implode(',', $serviceName),
            'store_id' => $websiteorder->store_id,
            'user_id' => auth()->user()->id,
            'type' => "service_reject",
            'object_id' => $websiteorder->store_id,
        ];

        foreach ($users as $user) {
            Notification::send($user, new verificationNotification($data));
            if ($user->device_token !== null) {

                $fcm = $this->sendFCM($user->device_token,
                    $user->id, 'منصة اطلبها', ' تم رفض خدمة' . implode(',', $serviceName), $user->notifications()->count());

            }

        }

        event(new VerificationEvent($data));
    }
        $success['websiteorder'] = new WebsiteorderResource($websiteorder);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم رفض الطلب بنجاح', ' reject successfully');

    }
    public function searchOrderServiceName(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $query = $request->input('query');
        $orders = Websiteorder::where('is_deleted', 0)
            ->where('order_number', 'like', "%$query%")->where('payment_status', 'paid')->orderByDesc('created_at')->select('id', 'status', 'order_number', 'type', 'created_at');
        $orders = $orders->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $orders->total();
        $success['page_count'] = $orders->lastPage();
        $success['current_page'] = $orders->currentPage();
        $success['orders'] = WebsiteorderResource::collection($orders);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الطلبات بنجاح', 'orders Information returned successfully');

    }

}
