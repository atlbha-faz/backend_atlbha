<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'order' => new OrderResource($this->order),
            'user_id' => $this->user_id,
            'option_id' => $this->option_id,
            'product_id' => $this->product_id,
            'comment' => $this->comment,
            'reason_txt' => $this->reason_txt,
            'status' => $status,
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            
        ];
    }
}
