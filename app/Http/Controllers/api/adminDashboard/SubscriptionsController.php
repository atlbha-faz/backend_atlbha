<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\AlertResource;
use App\Http\Resources\SubscriptionsResource;
use App\Mail\SendMail;
use App\Models\Alert;
use App\Models\Package_store;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SubscriptionsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 20;

        $data = Store::with(['categories' => function ($query) {
            $query->select('name');
        }, 'city' => function ($query) {
            $query->select('id');
        }, 'country' => function ($query) {
            $query->select('id');
        }, 'user' => function ($query) {
            $query->select('id');
        }])->where('is_deleted', 0)->where('package_id', '!=', null)->whereHas('packages', function ($q) {
            $q->where('payment_status', 'paid');
        })->orderByDesc('start_at')->select('id', 'store_name', 'verification_status', 'logo', 'package_id', 'start_at');
        $success['status'] = 200;
        $data = $data->paginate($count);
        $success['stores'] = SubscriptionsResource::collection($data);
        $success['page_count'] = $data->lastPage();
        $success['current_page'] = $data->currentPage();

        return $this->sendResponse($success, 'تم ارجاع المتاجر بنجاح', 'Subscriptions return successfully');
    }
    public function deleteAll(Request $request)
    {

        $stores = Store::whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($stores) > 0) {
            foreach ($stores as $store) {

                $store_package = Package_store::where('package_id', $store->package_id)->where('payment_status', 'paid')->where('store_id', $store->id)->orderBy('id', 'DESC')->first();

                if (is_null($store_package) || $store_package->is_deleted != 0) {
                } else {
                    $store_package->update(['is_deleted' => $store_package->id]);
                }
                if ($store->package_id != null) {
                    $store->update(['package_id' => null]);
                    $store->update(['periodtype' => null]);

                }
                $success['Subscriptions'] = new SubscriptionsResource($store);

            }

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم حذف الاشتراك بنجاح', 'Subscriptions deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غيرموجودة', 'id is not exit');
        }
    }
    public function changeSatusAll(Request $request)
    {

        $stores = Store::whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($stores) > 0) {
            foreach ($stores as $store) {

                $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->where('payment_status', 'paid')->orderBy('start_at', 'DESC')->first();
                //   if( $store->package_id != null){
                //   $store->update(['package_id' => null]);
                //   }
                if (is_null($store_package) || $store_package->is_deleted != 0) {
                } else {
                    if ($store_package->status === 'active') {
                        $store_package->update(['status' => 'not_active']);
                    } else {
                        $store_package->update(['status' => 'active']);
                    }
                }
                $success['Subscriptions'] = new SubscriptionsResource($store);

            }
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تعديل حالة الاشتراك بنجاح', 'Subscriptions updated successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غيرموجودة', 'id is not exit');
        }
    }
    public function addAlert(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'type' => 'required|in:now,after',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'store_id' => 'exists:stores,id',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        $data = [
            'subject' => $request->subject,
            'message' => $request->message,
            'store_id' => $request->store_id,

        ];
        if ($request->type == "now") {
            $alert = Alert::create([
                'subject' => $request->subject,
                'message' => $request->message,
                'store_id' => $request->store_id]);
        } else {
            $alert = Alert::create([
                'subject' => $request->subject,
                'message' => $request->message,
                'store_id' => $request->store_id,
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
            ]);

        }
        $users = User::where('store_id', $request->store_id)->where('user_type', 'store')->get();
        foreach ($users as $user) {
            // Notification::send($user, new emailNotification($data));
            try {
                Mail::to($user->email)->send(new SendMail($data));
            } catch (\Exception $e) {
                return $this->sendError('صيغة البريد الالكتروني غير صحيحة', 'The email format is incorrect.');
            }

        }
        $success['alerts'] = new AlertResource($alert);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة بنجاح', ' Added successfully');
    }
    public function searchSubscriptionsName(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $stores = Store::where('is_deleted', 0)->where('verification_status', '!=', 'pending')
            ->whereHas('packages', function ($q) {
                $q->where('payment_status', 'paid');
            })->where(function ($q) use ($query) {
            $q->where('store_name', 'like', "%$query%")->orWhereHas('packages', function ($subQuery) use ($query) {
                $subQuery->where('name', 'like', "%$query%");
            });
        })
            ->orderBy('created_at', 'desc')
            ->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $stores->total();
        $success['page_count'] = $stores->lastPage();
        $success['current_page'] = $stores->currentPage();
        $success['stores'] = SubscriptionsResource::collection($stores);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الاشتراكات بنجاح', 'Subscriptions Information returned successfully');

    }

}
