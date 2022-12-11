<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
        'store_name'=>$this->store_name,
        'domain'=>$this->domain,
        'phonenumber'=>$this->phonenumber,
        'store_email'=>$this->store_email,
        'icon' =>$this->icon,
        'description'=>$this->description,
        'business_license'=>$this->business_license,
        'ID_file' =>$this->ID_file,
        'accept_status'=>$this->accept_status,
        'snapchat'=>$this->snapchat,
        'facebook' =>$this->facebook,
        'twiter'=>$this->twiter,
        'youtube'=>$this->youtube,
        'instegram' =>$this->instegram,
        'logo'=>$this->logo,
        'entity_type'=>$this->entity_type,
        'user' =>New UserResource($this->user),
        'activity' =>New ActivityResource($this->activity),
        'package' =>New PackageResource($this->package),
        'category' =>New CategoryResource($this->category),
        'country' => New CountryResource($this->country),
        'city' => New CityResource($this->city),
        'status' => $this->status !==null ? $this->status:'active',
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
    ];
    }
}
