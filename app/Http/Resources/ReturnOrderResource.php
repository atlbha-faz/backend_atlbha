<?php

namespace App\Http\Resources;

use App\Http\Resources\OrderResource;
use App\Http\Resources\OptionResource;
use App\Http\Resources\PaymentOrderResource;
use App\Http\Resources\ReturnOrderItemsResource;
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
        if($this->returnOrders->first()->return_status==null || $this->returnOrders->first()->return_status== 'pending'){
            $status = 'جديد';
        }elseif($this->returnOrders->first()->return_status == 'accept'){
            $status = 'تم الاسترجاع';
        }
        else{
            $status = 'لم يتم الاسترجاع';
        }
        
        return [
            'id'=>$this->id,
            'order' => new PaymentOrderResource($this),
            'orderItem' => ReturnOrderItemsResource::collection($this->items->where('is_return', 1)),
            'return_total'=>$this->sumReturns($this->id),
            'comment' => $this->returnOrders->first()->comment,
            'reason_txt' => new ReturnResonsResource($this->returnOrders->first()->returnReason),
            'status' => $status,
            'refund_status' => $this->returnOrders->first()->refund_status,
            
        ];
    }
}
