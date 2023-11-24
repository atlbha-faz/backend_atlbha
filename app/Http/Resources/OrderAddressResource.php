<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderAddressResource extends JsonResource
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
            'id' => $this->id,
            'city' => $this->city,
            'street_address' => $this->street_address,
            'district' => $this->district,
            'postal_code' => $this->postal_code,
            // 'type'=>$this->type,
            'default_address' => $this->default_address,
            'shippingtype_id' => $this->shippingtype != null ? new ShippingtypeResource($this->shippingtype) : null,
        ];
    }
}
