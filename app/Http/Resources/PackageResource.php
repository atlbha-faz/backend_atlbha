<?php

namespace App\Http\Resources;

use App\Models\Store;
use App\Models\Package_store;
use App\Http\Resources\PlanResource;
use Illuminate\Support\Facades\Auth;
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
        foreach (\App\Models\Plan::all() as $p) {
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
            $package_store = Package_store::where('store_id', $store->id)->where('package_id',$this->id)->orderBy('id', 'desc')->first();
            $unique_id= $package_store !== null ? $package_store->id : null;
            }
        } else {
            $store = null;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'yearly_price' => $this->yearly_price,
            'discount' => $this->discount,
            'status' => $this->status == null || $this->status == 'active' ? __('message.active') : __('message.not_active'),
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'is_selected' => $store !== null ? $current_package : null,
            'plans' => PlanResource::collection($plans),
            'templates' => TemplateResource::collection($this->templates),
            'unique_id'=>$store !== null ? $unique_id : null,
            // 'stores'=> StoreResource::collection($this->stores)

        ];
    }
}
