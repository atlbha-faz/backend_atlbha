<?php

namespace App\Http\Resources;

use App\Models\User;
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
             'user'=>User::where('id',$this->data['user_id'])->get(),
            'message' => $this->data['message'],
            'store_id' => $this->data['store_id'],
            'store_name' => \App\Models\Store::find($this->data['store_id']) !== null ? \App\Models\Store::find($this->data['store_id'])->store_name: "deleted Store",
            'type'=> $this->data['type'],
            'object_id'=> $this->data['object_id'],
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

           
          ];

    }
}
