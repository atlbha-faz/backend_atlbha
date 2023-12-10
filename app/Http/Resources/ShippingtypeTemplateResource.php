<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingtypeTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $status = 'نشط';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $status,
            'image' =>asset('storage/images/shippingtype') . '/' . $this->image,
            'cod' => $this->cod,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'price' => $this->price,
            'time' => $this->time,
        ];

    }

}
