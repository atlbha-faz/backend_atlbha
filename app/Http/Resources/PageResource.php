<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'title' => $this->title,
            'page_content' => $this->page_content,
            'seo_title' => $this->seo_title,
            'seo_link' => $this->seo_link,
            'seo_desc' => $this->seo_desc,
           'tags' => explode(',',$this->tags),
           'store' => New StoreResource($this->store),
           'user' => New UserResource($this->user),
           'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'status' => $this->status !==null ? $this->status:'active',
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0
        ];
    }
}
