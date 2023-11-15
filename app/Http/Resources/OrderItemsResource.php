<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemsResource extends JsonResource
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
            'product' => new ProductResource($this->product),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'sum' => $this->subtotal($this->id),
        //   'shipping' => $this->order->shipping != null ? new  shippingResource ($this->order->shipping) : null,

            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

        ];

    }
}
