<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Resources\CouponResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class CouponController extends BaseController
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
        $success['coupons']=CouponResource::collection(Coupon::where('is_deleted',0)->where('store_id',null)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع الكوبونات بنجاح','coupons return successfully');
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
            'code'=>'required|string|max:255',
            'discount_type'=>'required|in:fixed,percent',
            'discount'=>['required','numeric','gt:0'],
            'start_at' =>['required','date'],
            'expire_date' =>['required','date'],
            'total_redemptions'=>['required','numeric'],

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $coupon = Coupon::create([
            'code' => $request->code,
            'discount_type' => $request->discount_type,
            'discount' => $request->discount,
            'start_at' => $request->start_at,
            'expire_date' => $request->expire_date,
            'total_redemptions' => $request->total_redemptions,
            'store_id' => null,
          ]);

         $success['coupons']=New CouponResource($coupon);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة الكوبون بنجاح','Coupon Added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $country
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show($coupon)
    {
        $coupon = Coupon::query()->find($coupon);
        if (is_null($coupon ) || $coupon->is_deleted==1){
               return $this->sendError("الكوبون غير موجودة","Coupon is't exists");
               }
              $success['Coupons']=New CouponResource($coupon);
              $success['status']= 200;

               return $this->sendResponse($success,'تم  عرض بنجاح','coupon showed successfully');

          }

           public function changeStatus($id)
          {
              $coupon = Coupon::query()->find($id);
              if (is_null($coupon ) || $coupon->is_deleted==1){
               return $this->sendError("الكوبون غير موجودة","coupon is't exists");
               }
              if($coupon->status === 'active'){
           $coupon->update(['status' => 'not_active']);
           }
          else{
              $coupon->update(['status' => 'active']);
          }
              $success['coupons']=New CouponResource($coupon);
              $success['status']= 200;
               return $this->sendResponse($success,'تم تعدبل حالة الكوبون بنجاح','coupon status updared successfully');
          }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        if (is_null($coupon ) || $coupon->is_deleted==1){
            return $this->sendError("الكوبون غير موجودة"," coupon is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
            'discount_type'=>'required|in:fixed,percent',
            'discount'=>['required','numeric','gt:0'],
            'expire_date' =>['required','date'],
            'start_at' =>['required','date'],
            'total_redemptions'=>['required','numeric'],
         
           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $coupon->update([
               'discount_type' => $request->input('discount_type'),
               'discount' => $request->input('discount'),
               'start_at' => $request->input('start_at'),
               'expire_date' => $request->input('expire_date'),
               'total_redemptions' => $request->input('total_redemptions'),
               'store_id' => null,
           ]);

           $success['coupons']=New CouponResource($coupon);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','coupon updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy($coupon)
    {
        $coupon =  Coupon::query()->find($coupon);
        if (is_null($coupon ) || $coupon->is_deleted==1){
            return $this->sendError("الكوبون غير موجودة","coupon is't exists");
            }
           $coupon->update(['is_deleted' => 1]);

           $success['coupons']=New couponResource($coupon);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف الكوبون بنجاح','coupon deleted successfully');
    }
      public function deleteall(Request $request)
    {

            $coupons =Coupon::whereIn('id',$request->id)->get();
           foreach($coupons as $coupon)
           {
             if (is_null($coupon) || $coupon->is_deleted==1 ){
                    return $this->sendError("الكوبون غير موجودة","coupon is't exists");
             }
             $coupon->update(['is_deleted' => 1]);
            $success['coupons']=New CouponResource($coupon);

            }

           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف الكوبون بنجاح','coupon deleted successfully');
    }
       public function changeSatusall(Request $request)
            {

                    $coupons =Coupon::whereIn('id',$request->id)->get();
                foreach($coupons as $coupon)
                {
                    if (is_null($coupon) || $coupon->is_deleted==1){
                        return $this->sendError("  الكوبون غير موجودة","coupon is't exists");
              }
                    if($coupon->status === 'active'){
                $coupon->update(['status' => 'not_active']);
                }
                else{
                $coupon->update(['status' => 'active']);
                }
                $success['coupons']= New CouponResource($coupon);

                    }
                    $success['status']= 200;

                return $this->sendResponse($success,'تم تعديل حالة الكوبون بنجاح','coupon updated successfully');
           }

}