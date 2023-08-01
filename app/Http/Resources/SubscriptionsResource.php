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
        if( $this->packagestatus($this->id) ==null || $this->packagestatus($this->id)  == 'active'){
            $status = 'نشط';
        }else{
            $status = 'غير نشط';
        }
        
        if($this->special ==null || $this->special == 'special'){
            $special = 'مميز';
        }else{
            $special = 'غير مميز';
        }
        
       if($this->periodtype ==null)
       {
        $periodtype='6 شهور';
       }
        elseif($this->periodtype == 'year'){
            $periodtype = 'سنوي';
        }else{
            $periodtype = '6 شهور';
        }
        
    
        
        
         if($this->verification_status ==null || $this->verification_status == 'pending'){
            $verification_status = 'لم يتم الطلب';
        }elseif($this->verification_status == 'admin_waiting'){
            $verification_status = 'جاري التوثيق';
        }elseif($this->verification_status == 'accept'){
            $verification_status = 'تم التوثيق';
        }elseif($this->verification_status == 'reject'){
            $verification_status = 'التوثيق مرفوض';
        }
        
        return [
       'id' =>$this->id,
       'store_name'=>$this->store_name,
       
       'user' =>New UserResource($this->user),
       'activity' =>ActivityResource::collection($this->activities),
       'country' => New CountryResource($this->country),
       'city' => New CityResource($this->city),
       'periodtype'=>$periodtype,
       'left'=>$this->left($this->id),
       'package_name' => $this->packagee($this->package_id),
       'rate'=> $this->rate($this->id)!==null ? $this->rate($this->id):0,
       'verification_status'=>$verification_status,
       'status' => $status,
       'special' => $special ,
       'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
   ];
   }
}
