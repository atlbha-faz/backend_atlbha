<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackagestoreResource extends JsonResource
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
            'package' => New PackageResource($this->package),
            'store' => New StoreResource($this->store),
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'period' => $this->period
        ];
    }
}
