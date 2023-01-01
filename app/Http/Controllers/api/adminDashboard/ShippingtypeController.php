<?php


namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Shippingtype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ShippingtypeResource;
use App\Http\Controllers\api\BaseController as BaseController;

class ShippingtypeController extends BaseController
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

        $success['shippingtypes']=ShippingtypeResource::collection(Shippingtype::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع شركات الشحن بنجاح','Shippingtype return successfully');
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
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */



     public function changeStatus($id)
    {
        $shippingtype = Shippingtype::query()->find($id);
         if (is_null($shippingtype) || $shippingtype->is_deleted==1){
         return $this->sendError("شركة الشحن غير موجودة","shippingtype is't exists");
         }

        if($shippingtype->status === 'active'){
        $shippingtype->update(['status' => 'not_active']);
        }
        else{
        $shippingtype->update(['status' => 'active']);
        }
        $success['shippingtypes']=New ShippingtypeResource($shippingtype);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة شركة الدفع بنجاح','shipping type updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */

}
