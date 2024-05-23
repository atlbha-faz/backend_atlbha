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
        if ($this->status == null || $this->status == 'active') {
            $status = __('message.active');
        } else {
            $status =  __('message.not_active');
        }

        if ($this->supportstatus == null || $this->supportstatus == 'pending') {
            $supportstatus = __('message.pending');
        } elseif ($this->supportstatus == 'finished') {
            $supportstatus = __('message.finished');
        } elseif ($this->supportstatus == 'not_finished') {
            $supportstatus = __('message.not_finished');
        }

        if ($this->type == null || $this->type == 'enquiry') {
            $type = 'استفسار';
        } elseif ($this->type == 'complaint') {
            $type = 'شكوى';
        } elseif ($this->type == 'suggestion') {
            $type = 'اقتراح';
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'phonenumber' => $this->user->phonenumber,
            'name' => $this->user->name,
            'content' => $this->content,
            'type' => $type,
            'supportstatus' => $supportstatus,
            'status' => $status,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'store' => new StoreResource($this->store),
            'user' => new UserResource($this->user),

        ];
    }
}
