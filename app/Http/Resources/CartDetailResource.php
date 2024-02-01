<?php

namespace App\Http\Resources;

use App\Models\Option;
use App\Models\Attribute;
use App\Models\Attribute_product;
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
    {  if( $this->option_id !== null)
        {
         $q = Option::where('id',$this->option_id)->where('product_id',$this->product->id)->first();
        // $attributeArray=Attribute_product::where('product_id',$this->product->id)->pluck('attribute_id')->toArray();
        // $attribute=Attribute::whereIn('id', $attributeArray)->pluck('name')->toArray();
         $array = explode(',', $q->name['ar']);
        // $options = array_combine($array,$attribute);
        }
        return [
            'id' => $this->id,
           'product' => New ProductResource($this->product),
            'qty' => $this->qty,
            'price' => $this->price,
            'sum' => $this->subtotal($this->id),
            'stock'=> $this->option_id !== null ?  $q->quantity :$this->product->stock,
            'options' => $this->option_id !== null ?  $array :null,
           
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
    
        ];
    }
}
