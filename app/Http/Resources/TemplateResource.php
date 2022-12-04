<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
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
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'status' => $this->status,
            'is_deleted' => $this->is_deleted,
            'parent' => New TemplateResource($this->parent)
        ];
    }
}
