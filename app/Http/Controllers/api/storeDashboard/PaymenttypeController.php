<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\PaymenttypeResource;
use App\Models\Paymenttype;
use App\Models\paymenttype_store;
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

        $success['paymenttypes'] = PaymenttypeResource::collection(Paymenttype::where('is_deleted', 0)->where('status', 'active')->orderByDesc('created_at')->get());
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
//     public function store(Request $request)
//    {
//         $input = $request->all();
//         $validator =  Validator::make($input ,[
//             'name'=>'required|string|max:255',
//             'image'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],

//         ]);
//         if ($validator->fails())
//          {
//             return $this->sendError(null,$validator->errors());
//          }
//          $paymenttype = Paymenttype::create([
//             'name' => $request->name,
//             'image' => $request->image,

//           ]);

//          $success['paymenttypes']=New PaymenttypeResource($paymenttype);
//          $success['status']= 200;

//           return $this->sendResponse($success,'تم إضافة طرق دفع بنجاح',' Payment type Added successfully');
//     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paymenttype  $paymenttype
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paymenttype  $paymenttype
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Paymenttype $paymenttype)
    //  {
    //      if (is_null($paymenttype) || $paymenttype->is_deleted !=0){
    //      return $this->sendError("طريقة الدفع غير موجودة","payment type is't exists");
    //       }
    //      $input = $request->all();
    //      $validator =  Validator::make($input ,[
    //        'name'=>'required|string|max:255',
    //         'image'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
    //      ]);
    //      if ($validator->fails())
    //      {
    //         # code...
    //         return $this->sendError(null,$validator->errors());
    //      }
    //      $paymenttype->update([
    //         'name' => $request->input('name'),

    //     'image' => $request->input('image'),

    //      ]);
    //      //$country->fill($request->post())->update();
    //         $success['paymenttypes']=New PaymenttypeResource($paymenttype);
    //         $success['status']= 200;

    //         return $this->sendResponse($success,'تم التعديل بنجاح','payment type updated successfully');
    // }

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
            $paymenttype = paymenttype_store::create([
                'paymentype_id' => $id,
                'store_id' => auth()->user()->store_id,
            ]);

            $success['paymenttypes'] = $paymenttype;

        }
        $success['paymenttypesall'] = PaymenttypeResource::collection(Paymenttype::where('is_deleted', 0)->where('status', 'active')->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة طريقة الدفع بنجاح', 'payment type updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paymenttype  $paymenttype
     * @return \Illuminate\Http\Response
     */
    // public function destroy($paymenttype)
    //   {
    //    $paymenttype = Paymenttype::query()->find($paymenttype);
    //      if (is_null($paymenttype) || $paymenttype->is_deleted !=0){
    //      return $this->sendError("طريقةالدفع غير موجودة","payment type is't exists");
    //      }
    //     $paymenttype->update(['is_deleted' => 1]);

    //     $success['paymenttypes']=New PaymenttypeResource($paymenttype);
    //     $success['status']= 200;
    //

    //      return $this->sendResponse($success,'تم حذف طريقةالدفع بنجاح','payment type deleted successfully');
    // }
}
