<?php

namespace App\Http\Resources;

use App\Models\Option;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
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
            'id'=>$this->id,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'discount_price'=> $this->discount_price,
            'default_option'=> $this->default_option,

        ];
    }
}
