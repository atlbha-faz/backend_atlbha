<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlertResource extends JsonResource
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
            'subject' => $this->subject,
            'message' => $this->message,
            'status' => $this->status == null || $this->status == 'active' ? __('message.active'):__('message.not_active'),
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'store' => New StoreResource($this->store),
        ];
    }
}
