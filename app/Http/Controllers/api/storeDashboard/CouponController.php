<?php

namespace App\Http\Controllers\api\storeDashboard;

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
        $success['coupons']=CouponResource::collection(Coupon::where('is_deleted',0)->where('store_id',auth()->user()->store_id)->get());
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
        // dd($request->free_shipping);
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'code'=>'required|string|max:255',
            'discount_type'=>'required|in:fixed,percent',
            'total_price'=>['required','numeric','gt:0'],
            'discount'=>['required','numeric','gt:0'],
            'expire_date' =>['required','date'],
            'total_redemptions'=>['required','numeric'],
            'user_redemptions'=>['required','numeric'],

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $coupon = Coupon::create([
            'code' => $request->code,
            'discount_type' => $request->discount_type,
            'total_price' => $request->total_price,
            'discount' => $request->discount,
            'expire_date' => $request->expire_date,
            'total_redemptions' => $request->total_redemptions,
            'user_redemptions' => $request->user_redemptions,
            'free_shipping' => $request->free_shipping,
            'exception_discount_product' => $request->exception_discount_product,
            'store_id'=> auth()->user()->store_id,
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
            'code'=>'required|string|max:255',
            'discount_type'=>'required|in:fixed,percent',
            'total_price'=>['required','numeric|gt:0'],
            'discount'=>['required','numeric|gt:0'],
            'expire_date' =>['required','date'],
            'total_redemptions'=>['required','numeric'],
            'user_redemptions'=>['required','numeric']
           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $coupon->update([
               'code' => $request->input('code'),
               'discount_type' => $request->input('discount_type'),
               'total_price' => $request->input('total_price'),
               'discount' => $request->input('discount'),
               'expire_date' => $request->input('expire_date'),
               'total_redemptions' => $request->input('total_redemptions'),
               'user_redemptions' => $request->input('user_redemptions'),
               'free_shipping' => $request->input('free_shipping'),
               'exception_discount_product' => $request->input('exception_discount_product'),
            //    'store_id' => $request->input('store_id'),
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

}
