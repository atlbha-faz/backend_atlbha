<?php


namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Paymenttype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

        $success['paymenttypes']=PaymenttypeResource::collection(Paymenttype::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع طرق الدفع بنجاح','payment types return successfully');
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
    public function store(Request $request)
   {
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'image'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],


        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $paymenttype = Paymenttype::create([
            'name' => $request->name,
            'image' => $request->image,

          ]);


         $success['paymenttypes']=New PaymenttypeResource($paymenttype);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة طرق دفع بنجاح',' Payment type Added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paymenttype  $paymenttype
     * @return \Illuminate\Http\Response
     */
     public function show($paymenttype)
     {
        $paymenttype = Paymenttype::query()->find($paymenttype);
        if (is_null($paymenttype) || $paymenttype->is_deleted==1){
        return $this->sendError("'طريقة الدفع غير موجودة","payment type is't exists");
        }


       $success['paymenttypes']=New PaymenttypeResource($paymenttype);
       $success['status']= 200;

        return $this->sendResponse($success,'تم عرض طريقة الدفع بنجاح','payment type showed successfully');
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
    public function update(Request $request, Paymenttype $paymenttype)
     {
         if (is_null($paymenttype) || $paymenttype->is_deleted==1){
         return $this->sendError("طريقة الدفع غير موجودة","payment type is't exists");
          }
         $input = $request->all();
         $validator =  Validator::make($input ,[
           'name'=>'required|string|max:255',
            'image'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
         ]);
         if ($validator->fails())
         {
            # code...
            return $this->sendError(null,$validator->errors());
         }
         $paymenttype->update([
            'name' => $request->input('name'),

        'image' => $request->input('image'),


         ]);
         //$country->fill($request->post())->update();
            $success['paymenttypes']=New PaymenttypeResource($paymenttype);
            $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','payment type updated successfully');
    }

     public function changeStatus($id)
    {
        $paymenttype = Paymenttype::query()->find($id);
         if (is_null($paymenttype) || $paymenttype->is_deleted==1){
         return $this->sendError("شركة الشحن غير موجودة","paymenttype is't exists");
         }

        if($paymenttype->status === 'active'){
        $paymenttype->update(['status' => 'not_active']);
        }
        else{
        $paymenttype->update(['status' => 'active']);
        }
        $success['paymenttypes']=New paymenttypeResource($paymenttype);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة طريقة الدفع بنجاح','payment type updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paymenttype  $paymenttype
     * @return \Illuminate\Http\Response
     */
    public function destroy($paymenttype)
      {
       $paymenttype = Paymenttype::query()->find($paymenttype);
         if (is_null($paymenttype) || $paymenttype->is_deleted==1){
         return $this->sendError("طريقةالدفع غير موجودة","payment type is't exists");
         }
        $paymenttype->update(['is_deleted' => 1]);

        $success['paymenttypes']=New PaymenttypeResource($paymenttype);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف طريقةالدفع بنجاح','payment type deleted successfully');
    }
}