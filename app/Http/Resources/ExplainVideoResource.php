<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ExplainVideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $videoSrc = Str::between($this->video, 'src="', '" title');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'video' => $this->video,
            'videoSrc' => $videoSrc,
            'thumbnail' => $this->thumbnail,
            'duration' => $this->duration,
            'status' => $this->status == null || $this->status == 'active' ? __('message.active') : __('message.not_active'),
            'url' => "https://store.atlbha.sa/Academy/ExplainDetails/" . $this->id,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'user' => new UserResource($this->user),
        ];
    }
}
