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
            'notification_time' => $this->notification_time,
            'description' => $this->description,
             'status' => $this->status,
            'is_deleted' => $this->is_deleted,
            'created_at' => (string) $this->created_at,
             'updated_at' => (string) $this->updated_at,
             'user' => New UserResource($this->user),
             'type' => New Notification_typeResource($this->notification_type),
          ];
          
    }
}