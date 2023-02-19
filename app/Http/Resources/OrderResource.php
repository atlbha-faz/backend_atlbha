<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->status ==null || $this->status == 'active'){
            $status = 'نشط';
        }else{
            $status = 'غير نشط';
        }
        
        return [
            'id' =>$this->id,
            'order_number' => $this->order_number,
            'user' => New UserResource($this->user),
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'status' => $status,
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
        ];
    }
}
