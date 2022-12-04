<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            'status' => $this->status,
            'is_deleted' => $this->is_deleted,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'store' => New StoreResource($this->store),
        ];
    }
}