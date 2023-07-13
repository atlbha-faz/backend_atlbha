<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PaymenttypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if ($this->discount_type == null || $this->discount_type == 'fixed') {
            $discount_type = 'مبلغ ثابت';
        } else {
            $discount_type = 'نسبة مئوية';
        }

        return [
            'id' => $this->id,
            'code' => $this->code,
            'discount_type' => $discount_type,
            'total_price' => $this->total_price,
            'discount' => $this->discount,
            'expire_date' => $this->expire_date,
            'start_at' => $this->start_at,
            'total_redemptions' => $this->total_redemptions,
            'user_redemptions' => $this->user_redemptions,
            'free_shipping' => $this->free_shipping !== null ? $this->free_shipping : 0,
            'exception_discount_product' => $this->exception_discount_product !== null ? $this->exception_discount_product : 0,
            'coupon_apply' => $this->coupon_apply,
            'selected_product' => $this->coupon_apply == "selected_product" ? ($this->products()) : null,
            'selected_category' => $this->coupon_apply == "selected_category" ? CategoryResource::collection($this->categories) : null,
            'selected_payment' => $this->coupon_apply == "selected_payment" ? PaymenttypeResource::collection($this->paymenttypes) : null,
            'status' => $this->expireCoupon($this->id),
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
        ];
    }
}
