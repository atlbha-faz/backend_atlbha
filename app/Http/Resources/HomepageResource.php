<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomepageResource extends JsonResource
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
            'logo' => $this->logo,
            'logo_footer' => $this->logo_footer,
            'banar1' => $this->banar1,
            'banarstatus1' => $this->banarstatus1,
            'banar2' => $this->banar2,
            'banarstatus2' => $this->banarstatus2,
            'banar3' => $this->banar3,
            'banarstatus3' => $this->banarstatus3,
            'clientstatus' => $this->clientstatus,
            'commentstatus' => $this->commentstatus,
            'slider1' => $this->slider1,
            'sliderstatus1' => $this->sliderstatus1,
            'slider2' => $this->slider2,
            'sliderstatus2' => $this->sliderstatus2,
            'slider3' => $this->slider3,
            'sliderstatus3' => $this->sliderstatus3,
            // 'status' => $this->status !==null ? $this->status:'active',
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
            'store' => New StoreResource($this->store),
        ];
    }
}
