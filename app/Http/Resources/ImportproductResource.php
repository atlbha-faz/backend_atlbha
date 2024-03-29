<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImportproductResource extends JsonResource
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
            'price' => $this->price,
            'special' => $this->special,
            'discount_price_import' => $this->discount_price_import,
            'store' =>New StoreResource($this->store),
            'product' =>New ProductResource($this->product)
        ];
    }
}
