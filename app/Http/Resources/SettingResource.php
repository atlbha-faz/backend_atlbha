<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'link' => $this->link,
            'email' => $this->email,
            'phoneNumber' => $this->phoneNumber,
            'logo' => $this->logo,
            'icon' => $this->icon,
            'address' => $this->address,
            'status' => $this->status,
            'is_deleted' => $this->is_deleted,
            'country' => New CountryResource($this->country),
            'city' => New CityResource($this->city)

        ];
    }
}
