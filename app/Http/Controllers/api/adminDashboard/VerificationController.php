<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Events\VerificationEvent;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\NoteResource;
use App\Http\Resources\VerificationResource;
use App\Mail\SendMail;
use App\Models\categories_stores;
use App\Models\Note;
use App\Models\Store;
use App\Models\User;
use App\Notifications\verificationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VerificationController extends BaseController
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

        $stores = Store::with(['categories' => function ($query) {
            $query->select('name', 'icon');
        }, 'city' => function ($query) {
            $query->select('id');
        }, 'country' => function ($query) {
            $query->select('id');
        }, 'user' => function ($query) {
            $query->select('id', 'name', 'email', 'phonenumber');
        }])->where('is_deleted', 0)->where('verification_status', '!=', 'pending')->orderByDesc('created_at')->get();
        $success['stores'] = VerificationResource::collection($stores);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المتاجر بنجاح', 'Stores return successfully');
    }
    public function acceptVerification($id)
    {
        $store = Store::query()->find($id);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجود", "store is't exists");
        }
        $date = Carbon::now()->toDateTimeString();

        $store->update(['verification_status' => 'accept',
            'verification_date' => $date]);

        $user = User::where('store_id', $store->id)->where('user_type', 'store')->first();
        $data = [
            'message' => 'تم قبول توثيق المتجر الخاص بك في منصة اطلبها',
            'store_id' => $store->id,
            'user_id' => auth()->user()->id,
            'type' => "منصة اطلبها",
            'object_id' => $store->id,
        ];

        Notification::send($user, new verificationNotification($data));
        Mail::to($user->email)->send(new SendMail($data));

        event(new VerificationEvent($data));
        $success['store'] = new VerificationResource($store);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة المتجر بنجاح', 'store updated successfully');

    }

    public function rejectVerification($id)
    {
        $store = Store::query()->find($id);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجود", "store is't exists");
        }
        $date = Carbon::now()->toDateTimeString();
        $store->update(['verification_status' => 'reject',
            'verification_date' => $date]);
        $user = User::where('store_id', $store->id)->where('user_type', 'store')->first();

        $data = [
            'message' => 'تم رفض المتجر الخاص بك في منصة اطلبها',
            'store_id' => $store->id,
            'user_id' => auth()->user()->id,
            'type' => "منصة اطلبها",
            'object_id' => $store->id,
        ];

        Notification::send($user, new verificationNotification($data));

        Mail::to($user->email)->send(new SendMail($data));

        event(new VerificationEvent($data));
        $success['store'] = new VerificationResource($store);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة المتجر بنجاح', 'store updated successfully');

    }
    /* public function destroy($store)
    {
    $store = Store::query()->find($store);
    if (is_null($store) || $store->is_deleted !=0){
    return $this->sendError("المتجر غير موجود","store is't exists");
    }
    $store->update(['is_deleted' => 1]);

    $success['store']=New VerificationResource($store);
    $success['status']= 200;

    return $this->sendResponse($success,'تم حذف المتجر بنجاح','store deleted successfully');
    }*/
    public function verification_show($id)
    {
        $store = Store::query()->find($id);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجودة", " store is't exists");
        }
        $success['store'] = new VerificationResource(Store::where('is_deleted', 0)->where('id', $store->id)->where('verification_status', '!=', 'pending')->orderByDesc('created_at')->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المتاجر بنجاح', 'Stores return successfully');

    }
    public function addNote(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'subject' => 'required|string|max:255',
            'details' => 'required|string',
            'store_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $note = Note::create([
            'subject' => $request->subject,
            'details' => $request->details,
            'store_id' => $request->store_id,
            'product_id' => null,
        ]);
        $store = Store::query()->find($request->store_id);
        $data = [
            'message' => $request->details,
            'store_id' => $store->id,
            'user_id' => $store->user_id,
            'type' => $request->subject,
            'object_id' => null,
        ];
        $user = User::where('user_type', 'store')->where('store_id', $store->id)->first();
        // Notification::send($user, new verificationNotification($data));
        // event(new VerificationEvent($data));
        try {
            Mail::to($user->email)->send(new SendMail($data));

        } catch (\Exception $e) {
            // Exception handling
            $errorMessage = 'Failed to send email. Please try again later.';
            Log::error('Email delivery failure: ' . $e->getMessage());
        }
        $success['notes'] = new NoteResource($note);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة ملاحظة بنجاح', 'note Added successfully');
    }

    public function verification_update(Request $request)
    {
        $store = Store::query()->find($request->store_id);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجودة", " store is't exists");
        }
        $user = User::where('is_deleted', 0)->where('store_id', $request->store_id)->where('user_type', 'store')->first();

        $input = $request->all();
        $validator = Validator::make($input, [
            'activity_id' => 'required|array',
            'subcategory_id' => ['nullable', 'array'],
            'store_name' => 'required|string',
            'file' => 'nullable',
            'name' => 'nullable|string|max:255',
            'store_id' => 'required',
            'email' => ['nullable', 'email', Rule::unique('users')->where(function ($query) use ($user) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0)
                    ->where('id', '!=', $user->id);
            }),
            ],
            'phonenumber' => ['nullable', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users')->where(function ($query) use ($user) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0)
                    ->where('id', '!=', $user->id);
            }),
            ],
            'owner_name' => 'required|string|max:255',
            'commercial_name' => 'nullable',
            'verification_code' => 'required',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $users = User::where('store_id', $request->store_id)->where('user_type', 'store')->get();

        $data = [
            'message' => 'تعديل توثيق',
            'store_id' => $request->store_id,
            'user_id' => auth()->user()->id,
            'type' => "store_verified",
            'object_id' => $request->store_id,
        ];
        foreach ($users as $user) {
            Notification::send($user, new verificationNotification($data));
        }
        event(new VerificationEvent($data));
        $store->update([
            'store_name' => $request->input('store_name'),
            // 'link' => $request->input('link'),
            'file' => $request->file,
            'store_email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
            'owner_name' => $request->input('owner_name'),   
            'verification_code' => $request->input('verification_code'),

        ]);
        if( $store->verification_type == 'commercialregister'){
            $store->update([
                'commercial_name' => $request->input('commercial_name'),
            ]);  
        }
  
        if (is_null($store->phonenumber)) {
            $store->update([
                'phonenumber' => $request->input('phonenumber'),
            ]);
        }
        if ($request->subcategory_id != null) {
            $subcategory = implode(',', $request->subcategory_id);
        } else {
            $subcategory = null;
        }

        $store->categories()->sync($request->activity_id);
        $sub = categories_stores::where('store_id', $store->id)->first();
        $sub->update([
            'subcategory_id' => $subcategory,
        ]);

        $user = User::where('is_deleted', 0)->where('store_id', $request->store_id)->where('user_type', 'store')->first();
        $user->update([
            // 'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
        ]);

        $success['store'] = Store::where('is_deleted', 0)->where('id', $request->store_id)->first();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل المتجر بنجاح', 'store update successfully');
    }
    public function deleteall(Request $request)
    {

        $stores = Store::whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($stores) > 0) {
            foreach ($stores as $store) {
                if (is_null($store) || $store->is_deleted != 0) {
                    return $this->sendError("المتجر غير موجودة", " store is't exists");
                }
                $store->update([
                    // 'link' => null,
                    'file' => null,
                    'verification_status' => 'pending',
                ]);
            }
            $success['stores'] = VerificationResource::collection($stores);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف المتجر بنجاح', 'store deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
    }

}
