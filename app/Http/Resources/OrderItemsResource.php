<?php

namespace App\Http\Resources;

use App\Models\Option;
use App\Models\Attribute;
use App\Models\Attribute_product;
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
        $q = Option::where('id',$this->option_id)->where('product_id',$this->product->id)->first();
        $attributeArray=Attribute_product::where('product_id',$this->product->id)->pluck('attribute_id')->toArray();
        $attribute=Attribute::whereIn('id', $attributeArray)->pluck('name')->toArray();
        $array = explode(',', $q->name['ar']);
        $options = array_combine($array,$attribute);
        return [
            'id' => $this->id,
            'product' => new ProductResource($this->product),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'sum' => $this->subtotal($this->id),
            'options' => $this->option_id !== null ? $options :null,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

        ];

    }
}
