<?php

namespace App\Http\Resources;

use App\Models\Store;
use App\Http\Resources\PlanResource;
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
        if (is_set(auth())) {
            $store = Store::where('user_id', auth()->user()->id)->first();
            $current_package = $store->package_id == $this->id ? true : false;
        } else {
            $store = null;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'monthly_price' => $this->monthly_price,
            'yearly_price' => $this->yearly_price,
            'discount' => $this->discount,
            'status' => $this->status == null || $this->status == 'active' ? __('message.active') : __('message.not_active'),
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'is_selected' => $store !== null ? $current_package : null,
            'plans' => PlanResource::collection($plans),
            'templates' => TemplateResource::collection($this->templates),
            // 'stores'=> StoreResource::collection($this->stores)

        ];
    }
}
