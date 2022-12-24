<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackagecouponResource extends JsonResource
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
            'discount' => $this->discount,
            'start_date' => $this->start_date,
            'expire_date' => $this->expire_date,
            'total_redemptions' => $this->total_redemptions,
            'status' => $this->status !==null ? $this->status:'active',
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0
        ];
    }
}