<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceResource extends JsonResource
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
            'title'=>$this->title,
            'message'=>$this->message,
            'store' =>New StoreResource($this->store),
            'status' => $this->status == null || $this->status == 'active' ? __('message.active'):__('message.not_active'),
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0
        ];
    }
}
