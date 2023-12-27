<?php

namespace App\Http\Resources;

use App\Http\Resources\ValueResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            'type' => $this->type,
            'values'=>ValueResource::collection(json_decode( $this->pivot->value))
        ];
    }
}
