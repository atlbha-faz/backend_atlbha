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
        
                if($this->periodtype == null || $this->periodtype == '6months'){
                  $periodtype = 'شهور'.' '.'6';
        }else{
            $periodtype = 'سنوي';
        }
          
        if($this->status ==null || $this->status == 'active'){
            $status = 'نشط';
        }else{
            $status = 'غير نشط';
        }
        
        if($this->special ==null || $this->special == 'special'){
            $special = 'مميز';
        }else{
            $special = 'غير مميز';
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
        'slug'=>$this->slug,
        'domain'=>$this->domain,
        'phonenumber'=>$this->phonenumber,
        'store_email'=>$this->store_email,
        'icon' =>$this->icon,
        'description'=>$this->description,
        'business_license'=>$this->business_license,
        'ID_file' =>$this->ID_file,
        'link' =>$this->link,
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
        'periodtype'=>$periodtype,
        'left'=>$this->left($this->id),
        'rate'=> $this->rate($this->id)!==null ? $this->rate($this->id):0,
        'verification_status'=>$verification_status,
        'verification_date'=>$this->verification_date,
        'status' => $status,
        'special' => $special,
        // 'package' =>$this->packagee($this->packages->last()->package_id),
             'package' =>$this->packagee($this->package_id),
        'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
    ];
    }
}
