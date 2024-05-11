<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingtypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if (auth()->user()->user_type == 'store' || auth()->user()->user_type == 'store_employee') {
            if ($this->stores()->where('store_id', auth()->user()->store_id)->first() != null) {
                $status = __('message.active');
            } else {
                $status =  __('message.not_active');
            }
        } else {
            if ($this->status == null || $this->status == 'active') {
                $status = __('message.active');
            } else {
                $status =  __('message.not_active');
            }
        }
        if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'admin_employee') {
            $price =  $this->price;
            $time = $this->time;
            $overprice= $this->overprice;

        } elseif (auth()->user()->user_type == 'store' || auth()->user()->user_type == 'store_employee') {
            if ($this->stores()->where('store_id', auth()->user()->store_id)->first() != null) {
                $priceobject = \App\Models\shippingtype_store::where('shippingtype_id', $this->id)->where('store_id', auth()->user()->store_id)->first();
                $price = $priceobject->price;
                $time = $priceobject->time;
                $overprice = $priceobject->overprice;

            } else {
                $price = 0;
                $time = 0;
                $overprice=0;

            }
        } else {
            $price = count($this->stores) == 0 ? 0 : null;
            $time =  0;
            $overprice=0;

        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $status,
            'image' => $this->image,
            'cod' => $this->cod,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'price' => $price,
            'time' => $time,
            'overprice' => $overprice,

        ];

    }
}
