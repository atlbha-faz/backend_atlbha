<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VerificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->categories->first() == !null) {
            $a = $this->categories->first()->pivot->subcategory_id;
            $subcategory = explode(',', $a);
        }

        if ($this->status == null || $this->status == 'active') {
            $status = 'نشط';
        } else {
            $status = 'غير نشط';
        }

        if ($this->special == null || $this->special == 'special') {
            $special = 'مميز';
        } else {
            $special = 'غير مميز';
        }
        if ($this->commercialregistertype == null || $this->commercialregistertype == 'commercialregister') {
            $verification_type = 'commercialregister';
        } else {
            $verification_type = 'freelancing';
        }

        if ($this->verification_status == null || $this->verification_status == 'pending') {
            $verification_status = 'لم يتم الطلب';
        } elseif ($this->verification_status == 'admin_waiting') {
            $verification_status = 'جاري التوثيق';
        } elseif ($this->verification_status == 'accept') {
            $verification_status = 'تم التوثيق';
        } elseif ($this->verification_status == 'reject') {
            $verification_status = 'التوثيق مرفوض';
        }
        return [
            'id' => $this->id,
            'store_name' => $this->store_name,
            'domain' => $this->domain,
            'phonenumber' => $this->phonenumber,
            'store_email' => $this->store_email,
            'icon' => $this->icon,
            'description' => $this->description,
            'business_license' => $this->business_license,
            'file' => $this->file,
            'link' => $this->link,
            'owner_name' => $this->owner_name,
            'verification_type' => $verification_type,
            'commercial_name' => $commercial_name,
            'snapchat' => $this->snapchat,
            'facebook' => $this->facebook,
            'twiter' => $this->twiter,
            'youtube' => $this->youtube,
            'instegram' => $this->instegram,
            'logo' => $this->logo,
            //    'entity_type'=>$this->entity_type,
            'user' => new UserResource($this->user),
            //    'activity' =>ActivityResource::collection($this->activities),
            'country' => new CountryResource($this->country),
            'city' => new CityResource($this->city),
            'rate' => $this->rate($this->id) !== null ? $this->rate($this->id) : 0,
            'periodtype' => $this->periodtype,
            'left' => $this->left($this->id),
            'verification_status' => $verification_status,
            'verification_date' => $this->verification_date,
            'activity' => CategoryResource::collection($this->categories),
            'status' => $status,
            'special' => $special,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'subcategory' => $this->categories->first() == !null ? CategoryResource::collection(\App\Models\Category::whereIn('id', $subcategory)->get()) : array(),

        ];
    }
}
