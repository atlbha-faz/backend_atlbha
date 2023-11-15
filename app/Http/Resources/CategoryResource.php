<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'number' => $this->number,
            'icon' => $this->icon,
            'for' => $this->for,
            'status' => $status,
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
             'store' => New StoreResource($this->store),
            // 'parent_id' => New CategoryResource($this->category),
            'subcategory' =>$this->subcategory,
            'countsubcategory' =>$this->subcategory->count(),
          ];
    }
}
