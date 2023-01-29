<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SeoResource extends JsonResource
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
            'index_page_title' => $this->index_page_title,
            'index_page_description' => $this->index_page_description,
            'show_pages' => $this->show_pages,
            'link' => $this->link,
           'robots' => $this->robots,
            'key_words' => explode(',',$this->key_words),
            'status' => $this->status !==null ? $this->status:'active',
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'store' => New StoreResource($this->store)

        ];
    }
}
