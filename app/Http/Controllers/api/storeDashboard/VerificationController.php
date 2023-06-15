<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Events\VerificationEvent;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Models\User;
use App\Notifications\verificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Notification;

class VerificationController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function verification_show()
    {

        $success['stores'] = StoreResource::collection(Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->get());

        // $success['activity']=Store::where('store_id',auth()->user()->store_id)->activities->first();
        $type = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->pluck('commercialregistertype')->first();
        if ($type == 'maeruf') {
            $success['name'] = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->pluck('store_name')->first();
            $success['city'] = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->pluck('city_id')->first();

        } elseif ($type == 'commercialregister') {
            $success['city'] = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->pluck('city_id')->first();
            $success['link'] = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->pluck('link')->first();
        }
        $success['file'] = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->pluck('file')->first();
        $success['username'] = User::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->pluck('name')->first();
        $success['phonenumber'] = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->pluck('phonenumber')->first();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بيانات النوثيق بنجاح', 'verifiction shown successfully');
    }

    public function verification_update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'activity_id' => 'required|array',
            'commercialregistertype' => 'required|in:commercialregister,maeruf',
            'store_name' => 'required_if:commercialregistertype,commercialregister',
            'city_id' => 'required',
            'link' => 'required_if:commercialregistertype,maeruf',
            'file' => 'required|mimes:pdf',
            'name' => 'required|string|max:255',
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $store = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();

        if ($store->verification_status == "admin_waiting" || $store->verification_status == "accept") {
            return $this->sendError("الطلب قيد المراجعه", "request is in process");
        }
        $users = User::where('store_id', null)->get();

        $data = [
            'message' => 'طلب توثيق',
            'store_id' => auth()->user()->store_id,
            'user_id' => auth()->user()->id,
            'type' => "verified",
            'object_id' => auth()->user()->store_id,
        ];
        foreach ($users as $user) {
            Notification::send($user, new verificationNotification($data));
        }
        event(new VerificationEvent($data));
        $store->update([
            'commercialregistertype' => $request->input('commercialregistertype'),

            'city_id' => $request->input('city_id'),
            'link' => $request->input('link'),
            'file' => $request->file,
            'phonenumber' => $request->input('phonenumber'),
            'verification_status' => "admin_waiting",

        ]);
        if ($store->commercialregistertype == "commercialregister") {
            $store->update(['store_name' => $request->input('store_name')]);
        }

        $store->activities()->sync($request->activity_id);

        $user = User::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $user->update([
            'name' => $request->input('name'),
        ]);

        $success['store'] = store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل الاعدادات بنجاح', 'registration_status update successfully');
    }
}
