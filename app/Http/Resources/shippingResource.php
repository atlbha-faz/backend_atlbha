<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class shippingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // if($this->shipping_status ==null || $this->shipping_status == 'new'){
        //     $status = 'جديد';
        // }elseif($this->shipping_status == 'completed'){
        //     $status = 'تم التوصيل';
        // }elseif($this->shipping_status == 'not_completed'){
        //     $status = 'غير مكتمل';
        // }elseif($this->shipping_status == 'delivery_in_progress'){
        //     $status = 'جاهز للشحن';
        // }elseif($this->shipping_status == 'ready'){
        //     $status = 'جاهز للشحن';
        // }elseif($this->shipping_status == 'canceled'){
        //     $status = 'ملغي';
        // }
        
        return [
            'id' =>$this->id,
            'shipping_id' => $this->shipping_id,
             'track_id' => $this->track_id,
             'pickup_date' =>$this->pickup_date,
             'sticker'=> $this->sticker,
             'district'=>$this->district,
            'city'=>$this->city,
            'street_address'=>$this->streetaddress,
            'destination_district'=>$this->destination_district,
            'destination_city'=>$this->destination_city,
            'destination_streetaddress'=>$this->destination_streetaddress,
            'description'=>$this->description,
            'created_at' => $this->created_at,
        ];
    }
}
