<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'type'=>$this->type,
            'notifiable_type'=>$this->notifiable_type,
            'user_id'=>$this->data['user_id'],
            'message' => $this->data['message'],
            'store_id' => $this->data['store_id'],
            'type'=> $this->data['type'],
            'object_id'=> $this->data['object_id'],
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

           
          ];

    }
}
