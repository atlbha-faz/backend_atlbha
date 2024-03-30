<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\PackagecouponResource;
use App\Models\Packagecoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackagecouponController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $success['Packagecoupons'] = PackagecouponResource::collection(Packagecoupon::where('is_deleted', 0)->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الكوبانات بنجاح', 'Packagecoupon return successfully');
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
            'code' => 'required|string|max:255',
            'discount_type' => 'required|in:fixed,percent',
            'discount' => ['required', 'numeric', 'gt:0'],
            'start_date' => ['required', 'date'],
            'expire_date' => ['required', 'date'],
            'total_redemptions' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $coupon = Packagecoupon::create([
            'code' => $request->code,
            'discount_type' => $request->discount_type,
            'discount' => $request->discount,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'total_redemptions' => $request->total_redemptions,
        ]);

        $success['coupons'] = new PackagecouponResource($coupon);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة الكوبون بنجاح', 'Coupon Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Packagecoupon  $packagecoupon
     * @return \Illuminate\Http\Response
     */
    public function show($packagecoupon)
    {
        $coupon = Packagecoupon::query()->find($packagecoupon);
        if (is_null($coupon) || $coupon->is_deleted != 0) {
            return $this->sendError("الكوبون غير موجودة", "Coupon is't exists");
        }
        $success['packagecoupon'] = new PackagecouponResource($coupon);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'coupon showed successfully');
    }

    public function changeStatus($id)
    {
        $coupon = Packagecoupon::query()->find($id);
        if (is_null($coupon) || $coupon->is_deleted != 0) {
            return $this->sendError("الكوبون غير موجودة", "coupon is't exists");
        }
        if ($coupon->status === 'active') {
            $coupon->update(['status' => 'not_active']);
        } else {
            $coupon->update(['status' => 'active']);
        }
        $success['coupons'] = new PackagecouponResource($coupon);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم تعديل حالة الكوبون بنجاح', 'coupon status updared successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Packagecoupon  $packagecoupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Packagecoupon $packagecoupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Packagecoupon  $packagecoupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $packagecoupon)
    {
        $packagecoupon = Packagecoupon::query()->find($packagecoupon);
        if (is_null($packagecoupon) || $packagecoupon->is_deleted != 0) {
            return $this->sendError("الكوبون غير موجودة", " packagecoupon is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'code' => 'required|string|max:255',
            'discount_type' => 'required|in:fixed,percent',
            'discount' => ['required', 'numeric', 'gt:0'],
            'start_date' => ['required', 'date'],
            'expire_date' => ['required', 'date'],
            'total_redemptions' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $packagecoupon->update([
            'code' => $request->input('code'),
            'discount_type' => $request->input('discount_type'),
            'discount' => $request->input('discount'),
            'start_date' => $request->input('start_date'),
            'expire_date' => $request->input('expire_date'),
            'total_redemptions' => $request->input('total_redemptions'),
        ]);

        $success['packagecoupons'] = new packagecouponResource($packagecoupon);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'packagecoupon updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Packagecoupon  $packagecoupon
     * @return \Illuminate\Http\Response
     */
    public function destroy($packagecoupon)
    {
        $packagecoupon = Packagecoupon::query()->find($packagecoupon);
        if (is_null($packagecoupon) || $packagecoupon->is_deleted != 0) {
            return $this->sendError("الكوبون غير موجودة", "coupon is't exists");
        }
        $packagecoupon->update(['is_deleted' => $packagecoupon->id]);

        $success['packagecoupons'] = new PackagecouponResource($packagecoupon);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف الكوبون بنجاح', 'coupon deleted successfully');
    }
}
