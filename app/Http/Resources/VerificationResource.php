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
            $status = __('message.active');
        } else {
            $status = __('message.not_active');
        }

        if ($this->special == null || $this->special == 'special') {
            $special = __('message.special');
        } else {
            $special = __('message.not_special');
        }
        // if ($this->verification_type == null || $this->verification_type == 'commercialregister') {
        //     $verification_type = 'commercialregister';
        // } else {
        //     $verification_type = 'freelancing';
        // }

        if ($this->verification_status == null || $this->verification_status == 'pending') {
            $verification_status = __('message.verificationpending');
        } elseif ($this->verification_status == 'admin_waiting') {
            $verification_status = __('message.admin_waiting');
        } elseif ($this->verification_status == 'accept') {
            $verification_status = __('message.accept');
        } elseif ($this->verification_status == 'reject') {
            $verification_status = __('message.reject');
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
            'link' => 'https://eauthenticate.saudibusiness.gov.sa/inquiry',
            'owner_name' => $this->owner_name,
            'verification_type' => $this->verification_type,
            'commercial_name' => $this->commercial_name,
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
            'verification_code' => $this->verification_code,
            'created_at' => $this->created_at,
            'activity' => CategoryResource::collection($this->categories),
            'status' => $status,
            'special' => $special,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'subcategory' => $this->categories->first() == !null ? CategoryResource::collection(\App\Models\Category::whereIn('id', $subcategory)->get()) : array(),
            'setting_is_done' => $this->store_name != null ? true : false,
        ];
    }
}
