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
            'panar1' => $this->panar1,
            'panarstatus1' => $this->panarstatus1,
            'panar2' => $this->panar2,
            'panarstatus2' => $this->panarstatus2,
            'panar3' => $this->panar3,
            'panarstatus3' => $this->panarstatus3,
            'clientstatus' => $this->clientstatus,
            'commentstatus' => $this->commentstatus,
            'slider1' => $this->slider1,
            'sliderstatus1' => $this->sliderstatus1,
            'slider2' => $this->slider2,
            'sliderstatus2' => $this->sliderstatus2,
            'slider3' => $this->slider3,
            'sliderstatus3' => $this->sliderstatus3,
<<<<<<< HEAD
            'status' => $this->status !==null ? $this->status:'active',
=======
            // 'status' => $this->status !==null ? $this->status:'active',
>>>>>>> 3a0d838cf6591f78fe755ff6858d0dc85fc2d24b
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
            'store' => New StoreResource($this->store),
        ];
    }
}