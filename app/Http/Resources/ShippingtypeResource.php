<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingtypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(auth()->user()->user_type == 'store' || auth()->user()->user_type == 'store_employee'){
            if($this->stores()->where('store_id',auth()->user()->store_id )->first() != null){
          $status = 'نشط';
      }else{
          $status = 'غير نشط';
      }
      }else{
if($this->status ==null || $this->status == 'active'){
            $status = 'نشط';
        }else{
            $status = 'غير نشط';
        }
        }
     return [
        'id' =>$this->id,
        'name'=>$this->name,
        'status' => $status,
        'image'=>$this->image,
        'cod' => $this->cod,
        'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
    ];

    
}
}
