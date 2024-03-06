<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Http\Resources\CategoryResource;
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
        $storeuser = User::whereIn('user_type', ['store', 'store_employee'])->where('store_id', $this->id)->pluck('updated_at')->toArray();
        if( !empty($storeuser) ){
        $lastLogin= max($storeuser);
        }
        else{
            $lastLogin=null; 
        }
        if ($this->categories->first() == !null) {
            $a = $this->categories->first()->pivot->subcategory_id;
            $subcategory = explode(',', $a);
        }
        if ($this->periodtype == null || $this->periodtype == '6months') {
            $periodtype = 'شهور' . ' ' . '6';
        } else {
            $periodtype = 'سنوي';
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

        if ($this->working_status == 'not_active') {
            foreach (\App\Models\Day::get() as $day) {
                // if ($day->name == "Friday") {
                //     $daystore[] = (object) [
                //         'day' => new DayResource($day),
                //         'from' => null,
                //         'to' => null,
                //         'status' => 'not_active',
                //     ];

                // } else {

                $daystore[] = (object) [
                    'day' => new DayResource($day),
                    'from' => '00:00:00',
                    'to' => '12:00:00',
                    'status' => 'active',
                ];

                // }

            }

        } else {
            $daystore = $this->daystore;
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
            'store_name' => $this->store_name !== null ? $this->store_name : "",
            'slug' => $this->slug,
            'domain' => $this->domain,
            'phonenumber' => $this->phonenumber,
            'store_email' => $this->store_email,
            'owner_name' => $this->owner_name,
            'icon' => $this->icon,
            'description' => $this->description !== null ? $this->description : "",
            'store_address' => $this->store_address,
            'business_license' => $this->business_license,
            'ID_file' => $this->ID_file,
            'link' => 'https://eauthenticate.saudibusiness.gov.sa/inquiry', // link for verification
            'snapchat' => $this->snapchat,
            'facebook' => $this->facebook,
            'twiter' => $this->twiter,
            'youtube' => $this->youtube,
            'instegram' => $this->instegram,
            'logo' => $this->logo,
            'entity_type' => $this->entity_type,
            'user' => new UserResource($this->user),
            // 'activity' =>ActivityResource::collection($this->activities),
            'country' => new CountryResource($this->country),
            'city' => new CityResource($this->city),
            'periodtype' => $periodtype,
            'left' => $this->left($this->id),
            'rate' => $this->rate($this->id) !== null ? $this->rate($this->id) : 0,
            'verification_status' => $verification_status,
            'verification_date' => $this->verification_date,
            'verification_code' => $this->verification_code,
            'status' => $status,
            'special' => $special,
            'verification_type' => $this->verification_type,
            // 'package' =>$this->packagee($this->packages->last()->package_id),
            'package' => $this->packagee($this->package_id),
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'working_status' => $this->working_status,
            'workDays' => DaystoreResource::collection($daystore),
            'activity' => CategoryResource::collection($this->categories),
            'subcategory' => $this->categories->first() == !null ? CategoryResource::collection(\App\Models\Category::whereIn('id', $subcategory)->get()) : array(),
            'created_at' => (string) $this->created_at,
            'last_login' =>$lastLogin,
            // 'updated_at' => (string) $this->updated_at,
        ];
    }
}
