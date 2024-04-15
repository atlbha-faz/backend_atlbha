<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Account;
use App\Models\Paymenttype;
use Illuminate\Http\Request;
use App\Models\paymenttype_store;
use App\Http\Resources\PaymenttypeResource;
use App\Http\Controllers\api\BaseController as BaseController;

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

        $success['paymenttypes'] = PaymenttypeResource::collection(Paymenttype::where('is_deleted', 0)->where('status', 'active')->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع طرق الدفع بنجاح', 'payment types return successfully');
    }


    public function show($paymenttype)
    {
        $paymenttype = Paymenttype::query()->find($paymenttype);
        if (is_null($paymenttype) || $paymenttype->is_deleted != 0 || $paymenttype->status == "not_active") {
            return $this->sendError("'طريقة الدفع غير موجودة", "payment type is't exists");
        }

        $success['paymenttypes'] = new PaymenttypeResource($paymenttype);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض طريقة الدفع بنجاح', 'payment type showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paymenttype  $paymenttype
     * @return \Illuminate\Http\Response
     */
    public function edit(Paymenttype $paymenttype)
    {
        //
    }

 

    public function changeStatus($id)
    {
        $paymenttype = Paymenttype::query()->find($id);
        if (is_null($paymenttype) || $paymenttype->is_deleted != 0 || $paymenttype->status == "not_active") {
            return $this->sendError("شركة الدفع غير موجودة", "paymenttype is't exists");
        }
      
        $paymenttype = paymenttype_store::where('paymentype_id', $id)->where('store_id', auth()->user()->store_id)->first();

        if ($paymenttype != null) {
            $paymenttype->delete();
        } else {
            if (in_array($id, [1,2,3]) ){
                $account = Account::where('store_id', auth()->user()->store_id)->where('status','APPROVED')->first();
                if($account == null){
                    return $this->sendError("  ليس لديك حساب بنكي", "account is't exists"); 
                }
    
             }
            $paymenttype = paymenttype_store::create([
                'paymentype_id' => $id,
                'store_id' => auth()->user()->store_id,
            ]);

            $success['paymenttype'] = $paymenttype;

        }
        $success['paymenttypes'] = PaymenttypeResource::collection(Paymenttype::where('is_deleted', 0)->where('status', 'active')->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة طريقة الدفع بنجاح', 'payment type updated successfully');

    }




}
