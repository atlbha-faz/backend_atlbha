<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceResource extends JsonResource
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
            'title'=>$this->title,
            'message'=>$this->message,
            'store' =>New StoreResource($this->store),
            'status' => $this->status,
            'is_deleted' => $this->is_deleted
        ];
    }
}
