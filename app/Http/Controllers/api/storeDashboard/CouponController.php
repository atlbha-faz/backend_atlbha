<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use App\Models\Importproduct;
use Carbon\Carbon;
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
        if ($request->has('page')) {

            $coupons = CouponResource::collection(Coupon::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->orderByDesc('created_at')->paginate(8));
            $success['page_count'] = $coupons->lastPage();
            $success['coupons'] = $coupons;
        } else {
            $success['coupons'] = CouponResource::collection(Coupon::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->orderByDesc('created_at')->get());
        }
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
        // dd($request->free_shipping);
        $input = $request->all();
        $validator = Validator::make($input, [
            'code' => ['required', 'regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9]+$/', 'max:255', Rule::unique('coupons')->where(function ($query) {
                return $query->where('store_id', auth()->user()->store_id)->where('is_deleted', 0);
            })],
            'discount_type' => 'required|in:fixed,percent',
            'total_price' => ['required', 'numeric', 'gt:0'],
            'discount' => ['required', 'numeric', 'gt:0'],
            //'start_at' =>['required','date'],
            'expire_date' => ['nullable', 'date'],
            'total_redemptions' => ['required', 'numeric', 'gt:0'],
            'user_redemptions' => ['required', 'numeric', 'gt:0'],
            'free_shipping' => ['required', 'in:0,1'],
            'exception_discount_product' => ['nullable', 'in:0,1'],
            'coupon_apply' => ['required', 'in:all,selected_product'],
            'select_product_id' => 'required_if:coupon_apply,selected_product',
            // 'select_category_id'=>'required_if:coupon_apply,selected_category',
            // 'select_payment_id'=>'required_if:coupon_apply,selected_payment',
            'status' => 'nullable|in:active,not_active',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $date = Carbon::now()->toDateTimeString();
        $coupon = Coupon::create([
            'code' => $request->code,
            'discount_type' => $request->discount_type,
            'total_price' => $request->total_price,
            'discount' => $request->discount,
            'start_at' => $date,
            'expire_date' => $request->expire_date,
            'total_redemptions' => $request->total_redemptions,
            'user_redemptions' => $request->user_redemptions,
            'free_shipping' => $request->free_shipping,
            // 'exception_discount_product' => $request->exception_discount_product,
            'coupon_apply' => $request->coupon_apply,
            'store_id' => auth()->user()->store_id,
            'status' => $request->status,
        ]);

        switch ($request->coupon_apply) {
            case ('selected_product'): {
                    // $coupon->products()->attach($request->select_product_id);
                    foreach ($request->select_product_id as $product) {
                        $import = Importproduct::where('product_id', $product)->where('store_id', auth()->user()->store_id)->first();
                        if ($import != null) {
                            $coupon->products()->attach($product, ["import" => 1]);
                        } else {
                            $coupon->products()->attach($product, ["import" => 0]);

                        }
                    }
                }
                break;
            case ('selected_category'):
                $coupon->categories()->attach($request->select_category_id);
                break;
            case ('selected_payment'):
                $coupon->paymenttypes()->attach($request->select_payment_id);
                break;

        }

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
        $coupon = Coupon::query()->where('store_id', auth()->user()->store_id)->find($coupon);
        if (is_null($coupon) || $coupon->is_deleted != 0) {
            return $this->sendError("الكوبون غير موجودة", "Coupon is't exists");
        }
        $success['Coupons'] = new CouponResource($coupon);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'coupon showed successfully');

    }

    public function changeStatus($id)
    {
        $coupon = Coupon::where('id', $coupon)->where('store_id', auth()->user()->store_id)->first();

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
        $coupon = Coupon::where('id', $coupon)->where('store_id', auth()->user()->store_id)->first();

        if (is_null($coupon) || $coupon->is_deleted != 0 || $coupon->store_id != auth()->user()->store_id) {
            return $this->sendError("الكوبون غير موجودة", " coupon is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [

            'code' => ['required', 'regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9]+$/', 'max:255', Rule::unique('coupons')->where(function ($query) use ($coupon) {
                return $query->where('store_id', auth()->user()->store_id)->where('is_deleted', 0)->where('id', '!=', $coupon->id);
            })],
            'discount_type' => 'required|in:fixed,percent',
            'total_price' => ['required', 'numeric', 'gt:0'],
            'discount' => ['required', 'numeric', 'gt:0'],
            'expire_date' => ['required', 'date'],
            'free_shipping' => ['required', 'in:0,1'],
            'exception_discount_product' => ['nullable', 'in:0,1'],
            'status' => 'nullable|in:active,not_active',
            'total_redemptions' => ['required', 'numeric', 'gt:0'],
            'user_redemptions' => ['required', 'numeric', 'gt:0'],
            'coupon_apply' => ['required ', 'in:all,selected_product,selected_category,selected_payment'],
            'select_product_id' => 'required_if:coupon_apply,selected_product',
            'select_category_id' => 'required_if:coupon_apply,selected_category',
            'select_payment_id' => 'required_if:coupon_apply,selected_payment',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $date = Carbon::now()->toDateTimeString();
        $coupon->update([
            'code' => $request->input('code'),
            'discount_type' => $request->input('discount_type'),
            'total_price' => $request->input('total_price'),
            'discount' => $request->input('discount'),
            'start_at' => $date,
            'expire_date' => $request->input('expire_date'),
            'total_redemptions' => $request->input('total_redemptions'),
            'user_redemptions' => $request->input('user_redemptions'),
            'free_shipping' => $request->input('free_shipping'),
            'exception_discount_product' => $request->input('exception_discount_product'),
            'coupon_apply' => $request->coupon_apply,
            'status' => $request->input('status'),
            //    'store_id' => $request->input('store_id'),
        ]);

        switch ($request->coupon_apply) {
            case ('selected_product'): {
                    foreach ($request->select_product_id as $product) {
                        $import = Importproduct::where('product_id', $product)->where('store_id', auth()->user()->store_id)->first();
                        if ($import != null) {
                            $coupon->products()->sync($product, ["import" => 1]);
                        } else {
                            $coupon->products()->sync($product, ["import" => 0]);

                        }
                    }
                }
                break;
            case ('selected_category'):
                $coupon->categories()->sync($request->select_category_id);
                break;
            case ('selected_payment'):
                $coupon->paymenttypes()->sync($request->select_payment_id);
                break;

        }

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
        $coupon = Coupon::where('id', $coupon)->where('store_id', auth()->user()->store_id)->first();

        // $coupon =  Coupon::query()->find($coupon);
        if (is_null($coupon) || $coupon->is_deleted != 0) {
            return $this->sendError("الكوبون غير موجودة", "coupon is't exists");
        }
        $coupon->update(['is_deleted' => $coupon->id]);

        $success['coupons'] = new couponResource($coupon);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف الكوبون بنجاح', 'coupon deleted successfully');
    }
    public function deleteall(Request $request)
    {
        $coupons = Coupon::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();

        if (count($coupons) > 0) {
            foreach ($coupons as $coupon) {

                $coupon->update(['is_deleted' => $coupon->id]);
                $success['coupons'] = new CouponResource($coupon);
            }
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف الكوبون بنجاح', 'coupon deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'الكوبون غير صحيح', 'coupon does not exit');
        }
    }
    public function changeSatusall(Request $request)
    {

        $coupons = Coupon::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();
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
            return $this->sendResponse($success, 'الكوبون غير صحيح', 'coupon does not exit');
        }
    }

}
