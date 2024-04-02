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
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $subsicriptions = SubscriptionEmailResource::collection(SubscriptionEmail::where('store_id', auth()->user()->store_id)->orderByDesc('created_at')->paginate($count));
        $success['page_count'] = $subsicriptions->lastPage();
        $success['current_page'] =$subsicriptions->currentPage();
        $success['subsicriptions'] = $subsicriptions;

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع اشتراكات الايميل بنجاح', 'Subscription Emails return successfully');

    }
    public function deleteAll(Request $request)
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
