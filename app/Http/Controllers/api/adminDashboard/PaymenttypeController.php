<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\PaymenttypeResource;
use App\Models\Paymenttype;
use Illuminate\Http\Request;

class PaymenttypeController extends BaseController
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

        $success['paymenttypes'] = PaymenttypeResource::collection(Paymenttype::where('is_deleted', 0)->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع طرق الدفع بنجاح', 'payment types return successfully');
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
     * @param  \App\Models\Paymenttype  $paymenttype
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paymenttype  $paymenttype
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paymenttype  $paymenttype
     * @return \Illuminate\Http\Response
     */

    public function changeStatus($id)
    {
        $paymenttype = Paymenttype::query()->find($id);
        if (is_null($paymenttype) || $paymenttype->is_deleted != 0) {
            return $this->sendError("شركة الدفع غير موجودة", "paymenttype is't exists");
        }
        // if (in_array($id, [1, 2, 3])) {
        //     $account = Account::where('store_id',null)->where('status', 'APPROVED')->first();
        //     if ($account == null) {
        //         return $this->sendError("  ليس لديك حساب بنكي", "account is't exists");
        //     }

        // }
        if ($paymenttype->status === 'active') {
            $paymenttype->update(['status' => 'not_active']);
            $paymenttype->stores()->detach();
        } else {
            $paymenttype->update(['status' => 'active']);
        }
        $success['paymenttypes'] = new paymenttypeResource($paymenttype);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة طريقة الدفع بنجاح', 'payment type updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paymenttype  $paymenttype
     * @return \Illuminate\Http\Response
     */

}
