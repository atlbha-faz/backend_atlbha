<?php

namespace App\Http\Resources;

use App\Models\Product;
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
        'snapchat'=>$this->snapchat,
        'facebook' =>$this->facebook,
        'twiter'=>$this->twiter,
        'youtube'=>$this->youtube,
        'instegram' =>$this->instegram,
        'logo'=>$this->logo,
        'entity_type'=>$this->entity_type,
        'user' =>New UserResource($this->user),
        'activity' =>ActivityResource::collection($this->activities),
        'country' => New CountryResource($this->country),
        'city' => New CityResource($this->city),
        'rate'=> $this->rate($this->id)!==null ? $this->rate($this->id):0,
        'period'=>$this->period($this->id),
        'periodtype'=>$this->periodtype,
        'left'=>$this->left($this->id),
        'confirmation_status'=>$this->confirmation_status,
        'status' => $this->status !==null ? $this->status:'active',
        'special' => $this->special ,
        'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
    ];
    }
}
