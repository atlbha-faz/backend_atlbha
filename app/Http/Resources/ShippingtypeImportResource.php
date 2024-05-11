<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingtypeImportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->status == null || $this->status == 'active') {
            $status = __('message.active');
        } else {
            $status =  __('message.not_active');
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $status,
            'image' => $this->image,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'price' => $this->price,
            'time' =>$this->time,
            'overprice' => $this->overprice,

        ];
    }
}
