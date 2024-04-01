<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductAdaminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->status == null || $this->status == 'active') {
            $status = __('message.active');
        } else {
            $status =  __('message.not_active');
        }

        if ($this->special == null || $this->special == 'special') {
            $special =  __('message.special');
        } else {
            $special = __('message.not_special');
        }
        if ($this->admin_special == null || $this->admin_special == 'special') {
            $admin_special =  __('message.special');
        } else {
            $admin_special =__('message.not_special');
        }
        $domain = $this->store_id !== null ? $this->store->domain : 'atlbha';

        if ($this->is_import == 1) {
            $import = true;
            $type = "importProduct";
        } else {
            $import = false;
            $type = null;
        }
      
            return [
                'id' => $this->id,
                'name' => $this->name,
                //'sku' => $this->sku,
                'for' => $this->for,
                'subcategory' => CategoryResource::collection(\App\Models\Category::with(['store' => function ($query) {
                    $query->select('id');
                }])->whereIn('id', explode(',', $this->subcategory_id))->get()),
                'cover' => $this->cover,
                'status' => $status,
                'special' => $special,
                'admin_special' => $admin_special,
                'url' => 'https://template.atlbha.sa/' . $domain . '/shop/product/' . $this->id,
                'created_at' => (string) $this->created_at,
                'updated_at' => (string) $this->updated_at,
                'category' => new CategoryResource($this->category),
                'store' => new StoreResource($this->store),
                'type' => $type,
                'is_import' => $import,

            ];
    }
}
