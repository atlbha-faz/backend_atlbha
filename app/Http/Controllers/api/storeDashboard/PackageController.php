<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\PackageResource;
use App\Models\Coupon;
use App\Models\coupons_users;
use App\Models\Package;
use App\Models\Package_store;
use App\Models\Paymenttype;
use App\Models\Store;
use App\Models\User;
use App\Services\FatoorahServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function setPackage(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'package_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $store = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $end_at = Carbon::now()->addYear()->format('Y-m-d H:i:s');
        $rows = Package_store::where('store_id', $store->id)->where('package_id', $request->package_id)->where('payment_status', null)->get();
        if ($rows->count() > 1) {
            foreach ($rows as $row) {
                $row->delete();
            }
        }
        $payment = Package_store::updateOrCreate([
            'package_id' => $request->package_id,
            'store_id' => $store->id,
            'payment_status' => null,
        ], [
            'start_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_at' => $end_at,
            'discount_value' => null,
            'coupon_id' => null
        ]);
        $coupons = coupons_users::where('user_id', auth()->user()->id)->get();
        foreach ($coupons as $coupon) {
            $coupon->delete();
        }
        $package = Package::where('id', $payment->package_id)->first();
        $success['package'] = new PackageResource($package);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم اختيار الباقة بنجاح', 'package successfully');
    }
    public function getPackage(Request $request)
    {
        $store = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $package_coupon = Package_store::where('store_id', $store->id)->orderBy('start_at', 'desc')->first();
        if (is_null($package_coupon)) {
            return $this->sendError("اختار الباقة", "package is't exists");
        }
        $package = Package::where('id', $package_coupon->package_id)->first();
        $success['package'] = new PackageResource($package);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم عرض الباقة بنجاح', 'package successfully');
    }
    public function payment(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'paymentype_id' => 'required',
            'package_id' => 'required',
            'package_reference'=>'required_if:paymentype_id,5'
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $store = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        if($store->store_name == null){
               return $this->sendError( 'يرجى إدخال بيانات اعدادات المتجر',null);
        }
        $end_at = Carbon::now()->addYear()->format('Y-m-d H:i:s');
        $payment = Package_store::where('store_id', $store->id)->where('package_id', $request->package_id)->where('payment_status', null)->orderBy('start_at', 'desc')->first();
        $package = Package::where('id', $payment->package_id)->first();
        $paymentype = Paymenttype::where('id', $request->paymentype_id)->first();
        if (in_array($request->paymentype_id, [1, 2])) {

            $processingDetails = [
                "AutoCapture" => true,
                "Bypass3DS" => false,
            ];
            $processingDetailsobject = (object) ($processingDetails);
            if ($package->discount != null && $package->discount > 0) {
                $price = $payment->coupon_id == null ? ($package->yearly_price - $package->discount) : $payment->discount_value;
            } else {
                $price = $payment->coupon_id == null ? ($package->yearly_price - $package->discount) : $payment->discount_value;
            }
            if ($price == 0) {
                return $this->sendError("يجب ان يكون المبلغ اكبر من الصفر", "price must be more than zero");
            }
            $data = [
                "PaymentMethodId" => $paymentype->paymentMethodId,
                "CustomerName" => (auth()->user()->name != null ? auth()->user()->name : auth()->user()->store->store_name.'('.auth()->user()->user_name.')'),
                "InvoiceValue" => $price, // total_price
                "CustomerEmail" => auth()->user()->email,
                "CustomerMobile"=>substr(auth()->user()->phonenumber, 4),
                "CallBackUrl" => 'https://store.atlbha.com/checkout-packages/success',
                "ErrorUrl" => 'https://store.atlbha.com/checkout-packages/failed',
                "Language" => 'AR',
                "DisplayCurrencyIso" => 'SAR',
                "ProcessingDetails" => $processingDetailsobject,
            ];
            $data = json_encode($data);
            $supplier = new FatoorahServices();
            $response = $supplier->buildRequest('v2/ExecutePayment', 'POST', $data);

            if (isset($response['IsSuccess'])) {
                if ($response['IsSuccess'] == true) {

                    $InvoiceId = $response['Data']['InvoiceId']; // save this id with your order table
                    $success['payment'] = $response;
                    $payment->update([
                        'paymentType' => $paymentype->name,
                        'paymentTransectionID' => $InvoiceId,
                    ]);

                } else {
                    $success['payment'] = $response;
                }
            } else {
                $success['payment'] = $response;
            }
        } else {

            $payment->update([
                'paymentType' => $paymentype->name,
                'paymentTransectionID' => $request->package_reference,
            ]);
        }
        $success['package'] = new PackageResource($package);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم ارسال الطلب بنجاح', 'order send successfully');

    }

    public function applyPackageCoupon(Request $request, $unique_id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $package_coupon = Package_store::where('id', $unique_id)->first();
        if (is_null($package_coupon)) {
            return $this->sendError("اختار الباقة", "package is't exists");
        }
        $package = Package::where('id', $package_coupon->package_id)->first();
        $now_time = Carbon::now();
        $coupon = Coupon::where('code', $request->code)->where('is_deleted', 0)->where('store_id', null)->first();
        if (is_null($coupon)) {
            return $this->sendError("الكوبون غير موجود", "coupon is't exists");
        }

        if ($coupon != null && $coupon->status == 'active' && ($now_time->gte($coupon->start_at))) {
            if ($package_coupon->coupon_id == $coupon->id) {
                $success['status'] = 200;
                return $this->sendResponse($success, 'الكوبون مستخدم بالفعل', 'The coupon is already used');
            } else {
                $package_coupon = $this->restPackage($package_coupon->id);
            }
            $coupon->users()->attach(auth()->user()->id);
            $user = User::where('id', auth()->user()->id)->first();
            $useCouponUser = coupons_users::where('user_id', auth()->user()->id)->where('coupon_id', $coupon->id)->get();
            $useCouponAll = coupons_users::where('coupon_id', $coupon->id)->get();
            $end_at = Carbon::now()->addYear()->format('Y-m-d H:i:s');
            if ($coupon->user_redemptions >= count($useCouponUser) && $coupon->total_redemptions >= count($useCouponAll)) {
                if ($coupon->discount_type == 'fixed') {
                    $packageAfterdiscount = $package->yearly_price - $package->discount - $coupon->discount;
                    $package_coupon->update([
                        'discount_value' => $packageAfterdiscount,
                        'coupon_id' => $coupon->id,
                    ]);

                } else {
                    $couponDiscountPercent = $coupon->discount / 100;
                    $discountAmount = $package->yearly_price * $couponDiscountPercent;
                    $packageAfterdiscount = $package->yearly_price - $discountAmount - $package->discount;
                    $package_coupon->update([
                        'discount_value' => $packageAfterdiscount,
                        'coupon_id' => $coupon->id,
                    ]);

                }

            } else {
                $success['status'] = 200;

                return $this->sendResponse($success, 'الكود غير صالح', 'The coupon is invalid');

            }
        } else {
            $success['status'] = 200;

            return $this->sendResponse($success, 'الكود غير صالح', 'The coupon is invalid');

        }
        $success['package'] = new PackageResource($package);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تفعيل الكود بنجاح', 'coupon updated successfully');

    }
    private function restPackage($id)
    {
        $package = Package_store::where('id', $id)->first();
        $package->update([
            'discount_value' => null,
            'coupon_id' => null,
        ]);
        return $package->refresh();
    }
}
