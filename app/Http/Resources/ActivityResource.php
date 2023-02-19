<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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
            $status = 'نشط',
        }else{
            $status = 'غير نشط',
        }
        return [
            'id' =>$this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'status' => $status,
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
            'storeCount' =>$this->stores($this->id)->count()
        ];
    }
}
