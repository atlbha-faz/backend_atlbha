<?php

namespace App\Http\Resources;
use Illuminate\Support\Str;

use Illuminate\Http\Resources\Json\JsonResource;

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
        if($this->status ==null || $this->status == 'active'){
            $status = 'نشط';
        }else{
            $status = 'غير نشط';
        }

$videoSrc = Str::between($this->video, 'src="', '" title');

        return [
            'id' =>$this->id,
            'title' => $this->title,
            'video' => $this->video,
            'videoSrc'=>$videoSrc,
            'thumbnail' => $this->thumbnail,
            'duration' => $this->duration,
            'status' => $status,
            'url'=> "https://store.atlbha.com/Academy/ExplainDetails/".$this->id,
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'user' => New UserResource($this->user),
        ];
    }
}
