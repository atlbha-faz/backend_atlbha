<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionsResource extends JsonResource
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
       
       'user' =>New UserResource($this->user),
       'activity' =>ActivityResource::collection($this->activities),
       'country' => New CountryResource($this->country),
       'city' => New CityResource($this->city),
       'periodtype'=>$this->periodtype,
       'left'=>$this->left($this->id),
       'package_name' => $this->packagee($this->package_id),
       'rate'=> $this->rate($this->id)!==null ? $this->rate($this->id):0,
       'verification_status'=>$this->verification_status !==null ? $this->verification_status:'pending',
       'confirmation_status'=>$this->confirmation_status !==null ? $this->confirmation_status:'request',
       'status' => $this->status !==null ? $this->status:'active',
       'special' => $this->special !==null ? $this->special:'not_special' ,
       'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
   ];
   }
}
