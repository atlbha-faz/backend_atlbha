<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\App;
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
        

        return [
        'id' =>$this->id,
        'user_id' =>$this->user_id,
        'name' =>$this->name !== null? $this->name:$this->user_name,
       'lastname' =>$this->lastname !== null? $this->lastname:"",
        'user_name' => $this->user_name,
        'user_type' => $this->user_type,
        'owner_name' => $this->user_type == "store" || $this->user_type == "store_employee" ? $this->store->owner_name:"",
        'email' => $this->email,
        //'password' => $this->password,
        'phonenumber' => $this->phonenumber,
        'image' =>$this->image,
        'status' => $this->status == null || $this->status == 'active' ? __('message.active'):__('message.not_active'),
        'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
        'created_at' => (string) $this->created_at,
        'updated_at' => (string) $this->updated_at,
        'country' => New CountryResource($this->country),
        'city' => New CityResource($this->city),
        'store_logo' => $this->user_type == "store" || $this->user_type == "store_employee" ? $this->store->logo:"",
        'store_domain' => $this->user_type == "store" || $this->user_type == "store_employee" ? $this->store->domain:"",
         'role' => New RoleResource($this->roles->first()),
        ];
    }
}
