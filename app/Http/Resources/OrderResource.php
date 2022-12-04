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
        return [
            'id' =>$this->id,
            'order_number' => $this->order_number,
            'user' => New UserResource($this->user),
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'is_deleted' => $this->is_deleted
        ];
    }
}
