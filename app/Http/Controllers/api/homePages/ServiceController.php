<?php

namespace App\Http\Controllers\api\homePages;

use App\Http\Controllers\api\BaseController;
use App\Http\Requests\EtlobhaServiceRequest;
use App\Http\Resources\WebsiteorderResource;
use App\Models\Coupon;
use App\Models\Paymenttype;
use App\Models\Service;
use App\Models\Websiteorder;
use App\Services\FatoorahServices;
use Carbon\Carbon;

class ServiceController extends BaseController
{
    public function serviceCheckout(EtlobhaServiceRequest $request)
    {
        if ($request->has('order_id')) {
            $websiteorder = Websiteorder::where('id', $request->order_id)->first();
        } else {
            $number = $this->generateOrderNumber();
            $totalPrice = $this->calculateTotalPrice($request->service_id);
            $websiteorder = Websiteorder::create([
                'type' => 'service',
                'order_number' => str_pad($number, 4, '0', STR_PAD_LEFT),
                'name' => $request->name,
                'store_domain' => $request->store_domain,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'total_price' => $totalPrice,

            ]);
            $websiteorder->services()->attach($request->service_id);
        }
        if ($request->has('code') && $request->code != null) {
            return $this->applyServiceCoupon($request->code, $websiteorder->id);
        } else {
            $paymentype = Paymenttype::where('id', $request->paymentype_id)->first();
            if (in_array($request->paymentype_id, [1, 2])) {

                $processingDetails = [
                    "AutoCapture" => true,
                    "Bypass3DS" => false,
                ];
                $processingDetailsobject = (object) ($processingDetails);
                if ($websiteorder->coupon_id != null) {
                    $totalPrice = $websiteorder->discount_value;
                }
                if ($totalPrice == 0) {
                    return $this->sendError("يجب ان يكون المبلغ اكبر من الصفر", "price must be more than zero");
                }
                
                $data = [
                    "PaymentMethodId" => $paymentype->paymentMethodId,
                    "CustomerName" => $websiteorder->name,
                    "InvoiceValue" => $totalPrice, // total_price
                    "CustomerEmail" => $websiteorder->email,
                     "CustomerMobile" => substr($websiteorder->phone_number, 4),
                    "CallBackUrl" => 'https://atlbha.com/success',
                    "ErrorUrl" => 'https://atlbha.com/failed',
                    "Language" => 'AR',
                    "DisplayCurrencyIso" => 'SAR',
                    "ProcessingDetails" => $processingDetailsobject,
                ];
                $data = json_encode($data);
                $payment_process = new FatoorahServices();
                $response = $payment_process->buildRequest('v2/ExecutePayment', 'POST', $data);

                if (isset($response['IsSuccess'])) {
                    if ($response['IsSuccess'] == true) {

                        $InvoiceId = $response['Data']['InvoiceId']; // save this id with your order table
                        $success['payment'] = $response;
                        $websiteorder->update([
                            'payment_method' => $paymentype->name,
                            'paymentTransectionID' => $InvoiceId,
                        ]);

                    } else {
                        $success['payment'] = $response;
                    }
                } else {
                    $success['payment'] = $response;
                }
            } else {

                $websiteorder->update([
                    'payment_method' => $paymentype->name,
                    'paymentTransectionID' => $request->service_reference,
                ]);
            }
            $success['websiteorder'] = new WebsiteorderResource($websiteorder);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم ارسال الطلب بنجاح', 'order send successfully');
        }
    }

    private function generateOrderNumber()
    {
        $order_number = Websiteorder::orderBy('id', 'desc')->where('type', 'service')->first();
        if (is_null($order_number)) {
            $number = 0001;
        } else {

            $number = $order_number->order_number;
            $number = ((int) $number) + 1;
        }
        return $number;
    }
    private function calculateTotalPrice(array $serviceIds)
    {

        return Service::whereIn('id', $serviceIds)->sum('price');
    }
    public function applyServiceCoupon($code, $unique_id)
    {

        $website_order = Websiteorder::where('id', $unique_id)->first();

        $now_time = Carbon::now();
        $coupon = Coupon::where('code', $code)->where('is_deleted', 0)->where('store_id', null)->first();
        if (is_null($coupon)) {
            return $this->sendError("الكوبون غير موجود", "coupon is't exists");
        }

        if ($coupon != null && $coupon->status == 'active' && ($now_time->gte($coupon->start_at))) {
            if ($website_order->coupon_id == $coupon->id) {
                $success['status'] = 200;
                return $this->sendResponse($success, 'الكوبون مستخدم بالفعل', 'The coupon is already used');
            } else {
                $website_order = $this->restService($website_order->id);
            }
            // $coupon->users()->attach(auth()->user()->id);
            // $user = User::where('id', auth()->user()->id)->first();
            // $useCouponUser = coupons_users::where('user_id', auth()->user()->id)->where('coupon_id', $coupon->id)->get();
            // $useCouponAll = coupons_users::where('coupon_id', $coupon->id)->get();
            $end_at = Carbon::now()->addYear()->format('Y-m-d H:i:s');
            // if ($coupon->user_redemptions >= count($useCouponUser) && $coupon->total_redemptions >= count($useCouponAll)) {
            if ($coupon->discount_type == 'fixed') {
                $service_after_discount = $website_order->total_price - $coupon->discount;
                $website_order->update([
                    'discount_value' => $service_after_discount,
                    'coupon_id' => $coupon->id,
                ]);

            } else {
                $couponDiscountPercent = $coupon->discount / 100;
                $discountAmount = $website_order->total_price * $couponDiscountPercent;
                $service_after_discount = $website_order->total_price - $discountAmount;
                $website_order->update([
                    'discount_value' => $service_after_discount,
                    'coupon_id' => $coupon->id,
                ]);

            }
            $success['websiteorder'] = new WebsiteorderResource($website_order);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تفعيل الكود بنجاح', 'coupon updated successfully');
        } else {
            $success['status'] = 200;

            return $this->sendResponse($success, 'الكود غير صالح', 'The coupon is invalid');

        }
  

    }
    private function restService($id)
    {
        $service = Websiteorder::where('id', $id)->first();
        $service->update([
            'discount_value' => null,
            'coupon_id' => null,
        ]);
        return $service->refresh();
    }
    public function removeServiceCoupon($id)
    {
        $websiteorder = Websiteorder::where('id', $id)->where('payment_status', '!=', 'paid')->first();
        if ($websiteorder == null) {
            return $this->sendError("الطلب غير موجود", " websiteorder is't exists");
        }
        if ($websiteorder->coupon_id == null) {
            return $this->sendError("الكوبون غير موجود", "coupon is't exists");
        }
        $websiteorder->update([
            'discount_value' => null,
            'coupon_id' => null,
        ]);
         $success['websiteorder'] = new WebsiteorderResource($websiteorder);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف الكود بنجاح', 'The coupon is already deleted');
    }
    public function showServiceOrder($id)
    {
        $websiteorder = Websiteorder::where('id', $id)->where('payment_status', '!=', 'paid')->first();
        if ($websiteorder == null) {
            return $this->sendError("الطلب غير موجود", " websiteorder is't exists");
        }
    
       
         $success['websiteorder'] = new WebsiteorderResource($websiteorder);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف الكود بنجاح', 'The coupon is already deleted');
    }
    
}
