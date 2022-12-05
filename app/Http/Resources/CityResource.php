<?php

namespace App\Http\Resources;

// use App\Models\Country;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'name_en' => $this->name_en,
            'code' => $this->code,
            'status' => $this->status !==null ? $this->status:'active',
            'is_deleted' => $this->is_deleted!==null ? $this->status:0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'country' => New CountryResource($this->country),
          ];
       
    }
}