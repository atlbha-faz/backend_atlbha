<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class atlobhaContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->status == null || $this->status == 'pending') {
            $status = 'قيد المعالجة';
        } elseif ($this->status == 'finished') {
            $status = 'منتهية';
        } elseif ($this->status == 'not_finished') {
            $status = 'غير منتهية';
        }
        return [
            'id' =>$this->id,
            'name' => $this->name,
            'email' => $this->email,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $status,
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
        ];
    }
}
