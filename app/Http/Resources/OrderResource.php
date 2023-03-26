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
        if($this->order_status ==null || $this->order_status == 'new'){
            $status = 'جديد';
        }elseif($this->order_status == 'completed'){
            $status = 'مكتمل';
        }elseif($this->order_status == 'not_completed'){
            $status = 'غير مكتمل';
        }elseif($this->order_status == 'delivery_in_progress'){
            $status = 'جاري التجهيز';
        }elseif($this->order_status == 'ready'){
            $status = 'جاهز';
        }elseif($this->order_status == 'canceled'){
            $status = 'ملغي';
        }
        
        
        
        return [
            'id' =>$this->id,
            'order_number' => $this->order_number,
            'user' => New UserResource($this->user),
            'products' => ProductResource::collection($this->products),
            'quantity' => $this->items()->where('store_id',auth()->user()->store_id)->sum('quantity'),
            'total_price' => $this->items()->where('store_id',auth()->user()->store_id)->sum('total_price'),
            'status' => $status,
            'created_at' => $this->created_at,
        ];
    }
}
