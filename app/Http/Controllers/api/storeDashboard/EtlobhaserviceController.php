<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Events\VerificationEvent;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\StoreResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WebsiteorderResource;
use App\Models\Service;
use App\Models\Store;
use App\Models\User;
use App\Models\Websiteorder;
use App\Notifications\verificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Notification;

class EtlobhaserviceController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function show()
    {

        $success['stores'] = new StoreResource(Store::where('is_deleted', 0, )->where('id', auth()->user()->store_id)->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المتاجر بنجاح', 'Stores return successfully');
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'service_id' => 'nullable|array|exists:services,id',
            'name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $order_number = Websiteorder::orderBy('id', 'desc')->first();
        if (is_null($order_number)) {
            $number = 0001;
        } else {

            $number = $order_number->order_number;
            $number = ((int) $number) + 1;
        }
        $websiteorder = Websiteorder::create([
            'type' => 'service',
            'order_number' => str_pad($number, 4, '0', STR_PAD_LEFT),
            'store_id' => auth()->user()->store_id,
        ]);
        if ($request->has('name') && $request->name != null) {
            $service = Service::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'not_active',
            ]);
            $array1 = array($service->id);
            if ($request->service_id != null) {
                $result = array_merge($request->service_id, $array1);
            } else {
                $result = $array1;
            }
        } else {
            if ($request->service_id != null) {
                $result = $request->service_id;
            }
        }
        if ($result != null) {
            $websiteorder->services()->attach($result);
        }
        $data = [
            'message' => 'طلب خدمة',
            'store_id' => auth()->user()->store_id,
            'user_id' => auth()->user()->id,
            'type' => "service",
            'object_id' => $websiteorder->id,
        ];
        $userAdmains = User::where('user_type', 'admin')->get();
        foreach ($userAdmains as $user) {
            Notification::send($user, new verificationNotification($data));
        }
        event(new VerificationEvent($data));

        $success['Websiteorders'] = new WebsiteorderResource($websiteorder);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة الطلب بنجاح', 'Websiteorder Added successfully');
    }
    public function marketerRequest(Request $request, $id)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $users = User::where('is_deleted', 0)->where('city_id', $id)->where('user_type', "marketer")->paginate($count);
            
        $marketers = UserResource::collection($users);
        $success['page_count'] = $marketers->lastPage();
        $success['current_page'] = $marketers->currentPage();
        $success['marketers'] = $marketers;

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المندوبين بنجاح', 'marketer return successfully');

    }
}
