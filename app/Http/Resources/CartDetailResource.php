<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartDetailResource extends JsonResource
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
            'id' => $this->id,
           'product' => New ProductResource($this->product),
            'qty' => $this->qty,
            'price' => $this->price,
            'sum' => $this->subtotal($this->id),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
    
        ];
    }
}
