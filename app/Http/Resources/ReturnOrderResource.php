<?php

namespace App\Http\Resources;

use App\Http\Resources\OrderResource;
use App\Http\Resources\OptionResource;
use App\Http\Resources\PaymentOrderResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AtlbhaIndexSearchProductResource;

class ReturnOrderResource extends JsonResource
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
            $status = 'جديد';
        }elseif($this->status == 'accept'){
            $status = 'قبول';
        }
        else{
            $status = 'رفض';
        }
        
        return [
            'id' =>$this->id,
            'order' => new PaymentOrderResource($this->order),
            'orderItem' => OrderItemsResource::collection($this->returnOrders->orderItem),
            'price' => $this->price,
            'qty' => $this->qty,
            'comment' => $this->comment,
            'reason_txt' => $this->reason_txt,
            'status' => $status,
            
        ];
    }
}
