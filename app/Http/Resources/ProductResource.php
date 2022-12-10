<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'sku' => $this->sku,
            'for' => $this->for,
            'description' => $this->description,
            'purchasing_price' => $this->purchasing_price,
            'selling_price' => $this->selling_price,
            'quantity' => $this->quantity,
            'less_qty' => $this->less_qty,
            'tags' => $this->tags,
            'cover' =>$this->cover,
            'status' => $this->status !== null ? $this->status : 'active',
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'category' => New CategoryResource($this->category),
            'store' => New StoreResource($this->store),
       ];
        }
}
