<?php

namespace App\Http\Resources;

use App\Models\Option;
use App\Models\Attribute;
use App\Models\Attribute_product;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnOrderItemsResource extends JsonResource
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
            'price' => $this->price,
            'return_qty' => $this->order->returnOrders->where('order_item_id',$this->id)->first()->qty,
            'total'=>($this->order->returnOrders->where('order_item_id',$this->id)->first()->qty)*$this->price,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

        ];

    }
}
