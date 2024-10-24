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
        if ($this->status == null || $this->status == 'active') {
            $status =__('message.active');
        } else {
            $status = __('message.not_active');
        }

        return [
            'id' => $this->id,
            'google_analytics' => $this->google_analytics,
            'snappixel' => $this->snappixel,
            'tiktokpixel' => $this->tiktokpixel,
            'twitterpixel' => $this->twitterpixel,
            'instapixel' => $this->instapixel,
            'metaDescription' => $this->metaDescription,
            'key_words' => $this->key_words !=="" ?explode(',', $this->key_words): null,
            'title' => $this->title,
            'header'=> $this->header,
            'footer'=> $this->footer,
            'graph'=>json_decode($this->graph,true),
            'tag'=> $this->tag,
            'search'=> $this->search,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'store' => $this->store !== null ? new StoreResource($this->store): null,

        ];
    }
}
