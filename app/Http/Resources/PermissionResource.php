<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Permission;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->parent_id != null){
            
        return [
            'id' =>$this->id,
            'name' => $this->name,
            'action_type' => $this->action_type,
            'name_ar' => $this->name_ar,
        ];
            
        }else{
            return [
            'id' =>$this->id,
            'name' => $this->name,
            'action_type' => $this->action_type,
            'name_ar' => $this->name_ar,
               'subpermissions' => PermissionResource::collection(Permission::where('parent_id',$this->id)->where('type','store')->get()),
        ];
        }
    }
}
