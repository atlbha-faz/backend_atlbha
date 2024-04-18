<?php

namespace App\Http\Resources;

use App\Http\Resources\OrderResource;
use App\Http\Resources\OptionResource;
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
            'order' => new OrderResource($this->order),
            'user_id' => $this->user_id,
            'option_id' =>new OptionResource($this->option),
            'product_id' => new AtlbhaIndexSearchProductResource($this->product),
            'price' => $this->price,
            'qty' => $this->qty,
            'comment' => $this->comment,
            'reason_txt' => $this->reason_txt,
            'status' => $status,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            
        ];
    }
}
