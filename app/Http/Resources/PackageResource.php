<?php

namespace App\Http\Resources;

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
        foreach(\App\Models\Plan::all() as $p){
            if(in_array($p->id,json_decode($this->plans->pluck('id')))){
               $pp =collect($p)->merge(['selected' => true]);
            }else{
                $pp = collect($p)->merge(['selected' => false]);
            }
            $plans[] = json_decode($pp);
        }
        return [
            'id' =>$this->id,
            'name' => $this->name,
            'monthly_price' => $this->monthly_price,
            'yearly_price' => $this->yearly_price,
            'discount' => $this->discount,
            'status' => $this->status !==null ? $this->status:'active',
            'is_deleted' => $this->is_deleted!==null ? $this->is_deleted:0,
            'plans'=>PlanResource::collection($plans),
            'templates'=>TemplateResource::collection($this->templates),
            // 'stores'=> StoreResource::collection($this->stores),
          

        ];
    }
}

