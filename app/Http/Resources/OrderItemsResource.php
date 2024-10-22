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
    { if( $this->option_id !== null)
        {
        $q = Option::where('id',$this->option_id)->where('product_id',$this->product->id)->first();
        // $attributeArray=Attribute_product::where('product_id',$this->product->id)->pluck('attribute_id')->toArray();
        // $attribute=Attribute::whereIn('id', $attributeArray)->pluck('name')->toArray();
        if($q !== null){
            $array = explode(',', $q->name['ar']);
            $qty= $q->quantity;
            $less_qty= $q->less_qty;
            $period= $q->period;
           }
           else{
               $array=null;
               $qty=null;
               $less_qty=null;
               $period=null;
           }
        // $options = array_combine($array,$attribute);
        }
        else
        {   
            $period = null;
        }
       
        return [
            'id' => $this->id,
            'product' => new ProductResource($this->product),
            'is_service' => ($this->product->is_service) ? true : false,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'period' => $period,
            'sum' =>round( $this->subtotal($this->id),2),
            'stock'=> $this->option_id !== null ?  $qty :$this->product->stock,
            'period'=> $this->option_id !== null ?  $period :null,
            'less_qty'=> $this->option_id !== null ?  $less_qty :$this->product->less_qty,
            'options' => $this->option_id !== null ?  $array :null,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

        ];

    }
}
