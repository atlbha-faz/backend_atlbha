<?php

namespace App\Http\Resources;

use App\Http\Resources\UnitResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(json_decode($this->tags, true)== null){
            $tags=explode(',',$this->tags);
        }
            else{
             $tags=json_decode($this->tags, true);
     
            }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'tags' => $this->tags != null ? $tags: array(),
            'duration' => $this->duration,
            'status' => $this->status == null || $this->status == 'active' ? __('message.active'):__('message.not_active'),
            'url' => "https://store.atlbha.sa/Academy/CourseDetails/" . $this->id,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'user' => new UserResource($this->user),
            'count' => $this->countVideo($this->id),
            'durationCourse' => $this->durationCourse($this->id),
            'unit' => UnitResource::collection($this->unit->where('is_deleted', 0)),
        ];
    }
}
