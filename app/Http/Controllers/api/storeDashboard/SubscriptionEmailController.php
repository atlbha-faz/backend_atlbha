<?php

namespace App\Http\Controllers\api\storeDashboard;

use Illuminate\Http\Request;
use App\Models\SubscriptionEmail;
use App\Http\Resources\SubscriptionEmailResource;
use App\Http\Controllers\api\BaseController as BaseController;

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
}
