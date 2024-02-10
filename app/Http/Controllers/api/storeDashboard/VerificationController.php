<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Events\VerificationEvent;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\StoreResource;
use App\Mail\SendMail;
use App\Models\Store;
use App\Models\User;
use App\Notifications\verificationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        $success['stores'] = StoreResource::collection(Store::with(['categories' => function ($query) {
            $query->select('name');
        }])->where('is_deleted', 0)->where('id', auth()->user()->store_id)->get());

        // $success['activity']=Store::where('store_id',auth()->user()->store_id)->activities->first();
        $type = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->pluck('verification_type')->first();

        $success['name'] = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->pluck('store_name')->first();
        $success['city'] = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->pluck('city_id')->first();

        if ($type == 'maeruf') {
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
        $store = Store::with(['categories' => function ($query) {
            $query->select('name');

        }])->where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();

        $input = $request->all();
        $validator = Validator::make($input, [
            'activity_id' => 'required|array',
            'subcategory_id' => ['nullable', 'array'],
            'verification_type' => 'required|in:commercialregister,maeruf',
            'city_id' => 'required',
            'link' => 'nullable',
            'file' => 'required|mimes:pdf',
            'owner_name' => 'required|string|max:255',
            'commercial_name' => 'required_if:verification_type,commercialregister|unique:stores,store_name,' . auth()->user()->store_id,
            'verification_code' => 'nullable',
            // 'name' => 'required|string|max:255',
            // 'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('stores')->where(function ($query) use ($store) {
            //     return $query->where('is_deleted', 0)->where('id', '!=', $store->id);
            // })],
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $store = Store::with(['categories' => function ($query) {
            $query->select('name');

        }])->where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();

        if ($store->verification_status == "admin_waiting" || $store->verification_status == "accept") {
            return $this->sendError("الطلب قيد المراجعه", "request is in process");
        }

        $date = Carbon::now()->toDateTimeString();
        $store->update([
            'verification_type' => $request->input('verification_type'),
            'city_id' => $request->input('city_id'),
            // 'link' => $request->input('link'),
            'file' => $request->file,
            // 'phonenumber' => $request->input('phonenumber'),
            'verification_status' => "admin_waiting",
            'verification_date' => $date,
            'commercial_name' => $request->input('commercial_name'),
            'owner_name' => $request->input('owner_name'),
            'verification_code' => $request->input('verification_code'),
        ]);

        // $store->activities()->sync($request->activity_id);
        if ($request->subcategory_id != null) {
            $subcategory = implode(',', $request->subcategory_id);
        } else {
            $subcategory = null;
        }
        $store->categories()->attach($request->activity_id, ['subcategory_id' => $subcategory]);
        $users = User::where('store_id', null)->whereIn('user_type', ['admin', 'admin_employee'])->whereIn('id', [1, 2])->get();

        $data = [
            'message' => ' https://admin.atlbha.com/verification  '
            . $store->owner_name . ' طلب توثيق من ',
            'store_id' => $store->id,
            'user_id' => auth()->user()->id,
            'type' => "طلب توثيق",
            'object_id' => $store->created_at,
        ];
        $data2 = [
            'message' => ' طلب توثيق  ',
            'store_id' => $store->id,
            'user_id' => auth()->user()->id,
            'type' => "طلب توثيق",
            'object_id' => $store->created_at,
        ];
        foreach ($users as $user) {
            Notification::send($user, new verificationNotification($data2));
            Mail::to($user->email)->send(new SendMail($data));
        }
        event(new VerificationEvent($data));
        // $user = User::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        // $user->update([
        //     'name' => $request->input('name'),
        // ]);

        // $success['store'] = store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل الاعدادات بنجاح', 'registration_status update successfully');
    }
}
