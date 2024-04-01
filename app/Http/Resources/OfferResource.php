<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'offer_type' => $this->offer_type,
            'offer_title' =>  $this->offer_title,
            'offer_view' =>  $this->offer_view,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'purchase_quantity' =>$this->purchase_quantity,
            'purchase_type' => $this->purchase_type,
            'get_quantity' =>  $this->get_quantity,
            'offer1_type' => $this->offer1_type,
            'get_type' => $this->get_type,
            'discount_percent' => $this->discount_percent,
            'discount_value_offer2' =>$this->discount_value_offer2,
            'offer_apply' => $this->offer_apply,
            'offer_type_minimum' =>  $this->offer_type_minimum,
            'offer_amount_minimum' => $this->offer_amount_minimum,
            'coupon_status' => $this->coupon_status,
            'discount_value_offer3' =>$this->discount_value_offer3,
            'maximum_discount' => $this->maximum_discount,
            'status' => $this->status == null || $this->status == 'active' ? __('message.active'):__('message.not_active'),
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0
        ];
    }
}
