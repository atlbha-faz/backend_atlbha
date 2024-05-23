<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
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
            $status = __('message.active');
        }else{
            $status =  __('message.not_active');
        }

        return [
            'id' =>$this->id,
            'title' => $this->title,
            'file' => $this->file,
            'status' => $status,
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'videos' => VideoResource::collection($this->video()->where('is_deleted',0)->get()),
            'unitvideo' => $this->countVideo($this->id),
            'durationUnit' => $this->durationUnit($this->id)
        ];
    }
}
