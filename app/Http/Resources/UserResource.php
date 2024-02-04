<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        'user_id' =>$this->user_id,
        'name' => $this->name,
       'lastname' =>$this->lastname !== null? $this->lastname:"",
        'user_name' => $this->user_name,
        'user_type' => $this->user_type,
        'owner_name' => $this->user_type == "store" || $this->user_type == "store_employee" ? $this->store->owner_name:"",
        'email' => $this->email,
        //'password' => $this->password,
        'phonenumber' => $this->phonenumber,
        'image' =>$this->image,
        'status' => $status,
        'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
        'created_at' => (string) $this->created_at,
        'updated_at' => (string) $this->updated_at,
        'country' => New CountryResource($this->country),
        'city' => New CityResource($this->city),
         'role' => New RoleResource($this->roles->first()),
        ];
    }
}
