<?php

namespace App\Http\Resources;

use App\Models\Store;
use App\Models\Coupon;
use App\Models\Package_store;
use App\Http\Resources\PlanResource;
use App\Http\Resources\TripResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CouponResource;

use App\Http\Resources\PackageCourseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $plans = array();
        foreach (\App\Models\Plan::orderBy('status', 'desc')->where('is_deleted', 0)->get() as $p) {
            if (in_array($p->id, json_decode($this->plans->pluck('id')))) {
                $pp = collect($p)->merge(['selected' => true]);
            } else {
                $pp = collect($p)->merge(['selected' => false]);
            }
            $plans[] = json_decode($pp);
        }
        if (Auth::check()) {
            $store = Store::where('user_id', auth()->user()->id)->first();
            if($store){
            $current_package = ($store->package_id == $this->id && $store->periodtype =="year")? true : false;
            $package_store = Package_store::where('store_id', $store->id)->where('package_id',$this->id)->orderBy('start_at', 'desc')->first();
            $paid= $package_store !== null ? ($package_store->payment_status =="paid"? true:false) : null;
            $unique_id= $package_store !== null ? $package_store->id : null;

            }
        } else {
            $store = null;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'yearly_price' => $this->yearly_price,
            'price_after_coupon' => $store !== null ? ($package_store != null && $package_store->coupon_id != null )?$package_store->discount_value:($this->yearly_price-$this->discount): null,
            'discount' => $this->discount,
            'status' => $this->status == null || $this->status == 'active' ? __('message.active') : __('message.not_active'),
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'is_selected' => $store !== null ? $current_package : null,
            'package_paid' => $store !== null ? $paid : null,
            'left_days'=>$store !== null ? $this->left($store->id) : null,
            'plans' => PlanResource::collection($plans),
            'templates' => $this->templates !== null ? TemplateResource::collection($this->templates):array(),
            'courses' => PackageCourseResource::collection($this->courses->where('is_deleted', 0)),
            'trip' => $this->trip !== null ? new TripResource($this->trip): null,
            'unique_id'=>$store !== null ? $unique_id : null,
            'coupon_info'=> $store !== null ? ($package_store != null && $package_store->coupon_id!= null )?new CouponResource(Coupon::where('id', $package_store->coupon_id)->first()):null: null,

            // 'stores'=> StoreResource::collection($this->stores)

        ];
    }
}
