<?php

namespace App\Http\Resources;

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
        
          if($this->status ==null || $this->status == 'pending'){
            $status = 'قيد المعالجة';
        }elseif($this->status == 'accept'){
            $status = 'منتهي';
        }elseif($this->status == 'reject'){
            $status = 'غير منتهي';
        }
        
        return [
            'id' =>$this->id,
            'order_number'=>$this->order_number,
            'type' =>$this->type,
            'status'=>$status,
            'is_deleted'=>$this->is_deleted,
            'created_at' => (string) $this->created_at,
            'store' => New StoreResource($this->store),
            'services'=>ServiceResource::collection($this->services_websiteorders),


        ];
    }
}
