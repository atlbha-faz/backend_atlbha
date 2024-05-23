<?php

namespace App\Http\Resources;

// use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        // return parent::toArray($request);
          return [
            'id' =>$this->id,
           // 'user' => New UserResource($this->user),
              
      'user_id' =>$this->user->user_id,
        'name' => $this->user->name,
        'user_name' => $this->user->user_name,
        'user_type' => $this->user->user_type,
        'email' => $this->user->email,
        //'password' => $this->password,
        'phonenumber' => $this->user->phonenumber,
        'image' =>$this->user->image,
        'status' => $this->user->status == null || $this->user->status == 'active' ? __('message.active'):__('message.not_active'),
        'is_deleted' => $this->user->is_deleted!==null ? $this->user->is_deleted:0,
        'country' => New CountryResource($this->user->country),
        'city' => New CityResource($this->user->city),
         
            'facebook' => $this->facebook,
            'snapchat' => $this->snapchat,
            'twiter' => $this->twiter,
            'whatsapp' => $this->whatsapp,
            'youtube' => $this->youtube,
            'instegram' => $this->instegram,
           // 'socialmediatext' => $this->socialmediatext,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
       


          ];
    }
}
