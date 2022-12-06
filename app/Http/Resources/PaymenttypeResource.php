<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymenttypeResource extends JsonResource
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
        'name'=>$this->name,
         'image'=>$this->image,
         'status' => $this->status !==null ? $this->status:'active',
         'is_deleted' => $this->is_deleted!==null ? $this->status:0,
    ];
    }
}