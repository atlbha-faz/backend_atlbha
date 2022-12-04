<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TechnicalsupportResource extends JsonResource
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
            'phoneNumber' =>$this->phoneNumber,
            'content' =>$this->content,
            'type'=>$this->type,
            'supportstatus'=>$this->supportstatus,
            'status'=>$this->status,
            'is_deleted'=>$this->is_deleted,
            'store' => New StoreResource($this->store)

        ];
    }
}
