<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
        
        return [
            'id' =>$this->id,
            'name' => $this->name,
            'description' => $this->description,
            'file' => $this->file,
            'price' => $this->price,
            'pendingServices' => $this->pendingServices($this->id),
            'status' => $status,
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
            // 'store' =>$this->getStore($this->websiteorders)

        ];
    }
}
