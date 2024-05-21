<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LightCategoryResource extends JsonResource
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
            'name' => $this->name,
            'number' => $this->number,
            'icon' => $this->icon,
            'for' => $this->for,
            'status' => $this->status == null || $this->status == 'active' ? __('message.active'):__('message.not_active'),
        ];
    }
}
