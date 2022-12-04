<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'description' => $this->description,
            'file' => $this->file,
            'price' => $this->price,
            'status' => $this->status,
            'is_deleted' => $this->is_deleted,
            'store' => New StoreResource($this->store)

        ];
    }
}
