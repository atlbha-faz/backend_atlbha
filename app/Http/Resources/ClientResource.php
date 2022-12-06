<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'ID_number' => $this->ID_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'image' => $this->image,
            'gender' => $this->gender,
            'phonenumber' => $this->phonenumber,
            'status' => $this->status !==null ? $this->status:'active',
            'is_deleted' => $this->is_deleted!==null ? $this->status:0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'country' => New CountryResource($this->country),
            'city' => New CityResource($this->city),
          ];
    }
}