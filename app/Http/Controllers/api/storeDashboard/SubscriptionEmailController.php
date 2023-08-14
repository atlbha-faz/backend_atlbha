<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\SubscriptionEmailResource;
use App\Models\SubscriptionEmail;
use Illuminate\Http\Request;

class SubscriptionEmailController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $success['subsicriptions'] = SubscriptionEmailResource::collection(SubscriptionEmail::where('store_id', auth()->user()->store_id)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع اشتراكات الايميل بنجاح', 'Subscription Emails return successfully');

    }
    public function deleteall(Request $request)
    {

        $subsicriptions = SubscriptionEmail::whereIn('id', $request->id)->get();
        if (count($subsicriptions) > 0) {
            foreach ($subsicriptions as $subsicription) {

                $subsicription->delete();
                // $success['subsicriptions'] = new SubscriptionEmailResource($subsicription);

            }
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف اشتراك البريد بنجاح', 'Subscription Emai deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غيرموجودة', 'id is not exit');
        }

    }

}
