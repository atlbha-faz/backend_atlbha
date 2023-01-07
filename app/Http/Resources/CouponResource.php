<?php

namespace App\Http\Resources;

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
         return [
            'id' =>$this->id,
            'code' => $this->code,
            'discount_type' => $this->discount_type,
            'total_price' => $this->total_price,
            'discount' => $this->discount,
            'expire_date' => $this->expire_date,
            'total_redemptions' => $this->total_redemptions,
            'user_redemptions' => $this->user_redemptions,
            'free_shipping' => $this->free_shipping!==null ? $this->free_shipping:0,
            'exception_discount_product' => $this->exception_discount_product!==null ? $this->exception_discount_product:0,
        'status' => $this->expireCoupon($this->id),
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0
        ];
    }
}
