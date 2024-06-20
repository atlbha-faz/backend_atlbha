<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
    public function index(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $data =Coupon::where('is_deleted', 0)->where('store_id', null);
        if ($request->has('status')) {
            $data->where('status', $request->status);
        }
        if ($request->has('code')) {
            $data->orderBy('code', 'ASC');
        }else{
            $data->orderByDesc('created_at');
        }
        $data = $data->paginate($count);
        $success['coupons'] = CouponResource::collection($data);
        $success['page_count'] =  $data->lastPage();
        $success['current_page'] =  $data->currentPage();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع جميع الكوبونات بنجاح', 'coupons return successfully');
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
        $validator = Validator::make($input, [
            'code' => ['required', 'regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9]+$/', 'max:255',
                Rule::unique('coupons')->where(function ($query) {
                    return $query->where('store_id', null);
                })->where('is_deleted', 0)],
            'discount_type' => 'required|in:fixed,percent',
            'discount' => ['required', 'numeric', 'gt:0'],
            'start_at' => ['required', 'date'],
            'expire_date' => ['required', 'date'],
            'total_redemptions' => ['required', 'numeric'],
            'user_redemptions' => ['nullable', 'numeric'],

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $coupon = Coupon::create([
            'code' => $request->code,
            'discount_type' => $request->discount_type,
            'discount' => $request->discount,
            'start_at' => $request->start_at,
            'expire_date' => $request->expire_date,
            'total_redemptions' => $request->total_redemptions,
            'user_redemptions' => $request->user_redemptions,
            'store_id' => null,
        ]);

        $success['coupons'] = new CouponResource($coupon);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة الكوبون بنجاح', 'Coupon Added successfully');
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
        $coupon = Coupon::where('store_id', null)->where('id', $coupon)->first();
        if (is_null($coupon) || $coupon->is_deleted != 0) {
            return $this->sendError("الكوبون غير موجودة", "Coupon is't exists");
        }
        $success['Coupons'] = new CouponResource($coupon);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'coupon showed successfully');

    }

    public function changeStatus($id)
    {
        $coupon = Coupon::where('store_id', null)->where('id', $id)->first();
        if (is_null($coupon) || $coupon->is_deleted != 0) {
            return $this->sendError("الكوبون غير موجودة", "coupon is't exists");
        }
        if ($coupon->status === 'active') {
            $coupon->update(['status' => 'not_active']);
        } else {
            $coupon->update(['status' => 'active']);
        }
        $success['coupons'] = new CouponResource($coupon);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم تعدبل حالة الكوبون بنجاح', 'coupon status updared successfully');
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
    public function update(Request $request, $coupon)
    {
        $coupon = Coupon::where('id', $coupon)->first();
        if (is_null($coupon) || $coupon->is_deleted != 0 || $coupon->store_id != null) {
            return $this->sendError("الكوبون غير موجودة", " coupon is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [

            'discount_type' => 'required|in:fixed,percent',
            'discount' => ['required', 'numeric', 'gt:0'],
            'expire_date' => ['required', 'date'],
            'start_at' => ['required', 'date'],
            'total_redemptions' => ['required', 'numeric'],
            'user_redemptions' => ['nullable', 'numeric'],

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $coupon->update([
            'discount_type' => $request->input('discount_type'),
            'discount' => $request->input('discount'),
            'start_at' => $request->input('start_at'),
            'expire_date' => $request->input('expire_date'),
            'total_redemptions' => $request->input('total_redemptions'),
            'user_redemptions' => $request->input('user_redemptions'),
            'store_id' => null,
        ]);

        $success['coupons'] = new CouponResource($coupon);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'coupon updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy($coupon)
    {
        $coupon = Coupon::where('store_id', null)->where('id', $coupon)->first();
        if (is_null($coupon) || $coupon->is_deleted != 0) {
            return $this->sendError("الكوبون غير موجودة", "coupon is't exists");
        }
        $coupon->update(['is_deleted' => $coupon->id]);

        $success['coupons'] = new couponResource($coupon);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف الكوبون بنجاح', 'coupon deleted successfully');
    }
    public function deleteAll(Request $request)
    {

        $coupons = Coupon::where('store_id', null)->whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($coupons) > 0) {
            foreach ($coupons as $coupon) {

                $coupon->update(['is_deleted' => $coupon->id]);
                $success['coupons'] = new CouponResource($coupon);

            }

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم حذف الكوبون بنجاح', 'coupon deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير موجودة', 'id is not exit');
        }

    }

    public function changeSatusAll(Request $request)
    {

        $coupons = Coupon::where('store_id', null)->whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($coupons) > 0) {
            foreach ($coupons as $coupon) {

                if ($coupon->status === 'active') {
                    $coupon->update(['status' => 'not_active']);
                } else {
                    $coupon->update(['status' => 'active']);
                }
                $success['coupons'] = new CouponResource($coupon);

            }
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تعديل حالة الكوبون بنجاح', 'coupon updated successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'الكوبون غير صحيحة', 'coupon does not exit');
        }

    }

}
