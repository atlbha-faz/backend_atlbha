<?php

namespace App\Http\Controllers\api\storeDashboard;

use Carbon\Carbon;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class PaymentController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function billing(Request $request)
    {
        $ids=Order::where('store_id', auth()->user()->store_id)->where('payment_status','paid')->orwhere('paymentype_id',4)->pluck('id')->toArray();
        $payments =PaymentResource::collection(Payment::where('store_id', auth()->user()->store_id)->wherein('orderID',$ids)->orderByDesc('created_at')->get());
        $success['billing'] = $payments;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الفواتير', ' show successfully');
    }



}
?>
