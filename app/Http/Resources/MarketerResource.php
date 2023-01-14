<?php

namespace App\Http\Resources;

// use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
          return [
            'id' =>$this->id,
            'user' => New UserResource($this->user),
            'facebook' => $this->facebook,
            'snapchat' => $this->snapchat,
            'twiter' => $this->twiter,
            'whatsapp' => $this->whatsapp,
            'youtube' => $this->youtube,
            'instegram' => $this->instegram,
            'socialmediatext' => $this->socialmediatext,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
       


          ];
    }
}
