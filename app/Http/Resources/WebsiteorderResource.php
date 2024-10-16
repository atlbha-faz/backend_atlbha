<?php

namespace App\Http\Resources;

use App\Http\Resources\CouponResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteorderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->status == null || $this->status == 'pending') {
            $status = __('message.pending');
        } elseif ($this->status == 'accept') {
            $status = "منتهية";
        } elseif ($this->status == 'reject') {
            $status = "غير منتهية";
        }

        $type = '';
        if ($this->type == null || $this->type == 'store') {
            $type = 'متجر جديد';
        } elseif ($this->type == 'service') {
            $type = 'طلب خدمة';
        }
        $tax = round($this->total_price * 0.15, 2);
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'type' => $type,
            'status' => $status,
            'is_deleted' => $this->is_deleted,
            'created_at' => (string) $this->created_at,
            'store' =>$this->store==null ? null : new StoreResource($this->store),
            'services' => ServiceResource::collection($this->services),
            'name' =>$this->name==null ? null :$this->name,
            'store_domain'=>$this->store_domain==null ? null :$this->store_domain,
            'phone_number' => $this->phone_number==null ? null :$this->phone_number,
            'email' => $this->email==null ? null :$this->email,
            'price' => number_format($this->total_price - $tax,2),
            'tax'=> number_format($tax,2),
            'total_price' => $this->total_price,
            'discount_value' => $this->discount_value==null ? $this->total_price :$this->discount_value,
            'payment_method'=>$this->payment_method,
            'reference'=>$this->paymentTransectionID,
            'payment_status'=>$this->payment_status,
            'coupon' => $this->coupon==null ? null : new CouponResource($this->coupon),
        ];
    }
}
