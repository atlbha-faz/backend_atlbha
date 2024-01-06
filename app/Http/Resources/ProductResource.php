<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            $status = 'نشط';
        } else {
            $status = 'غير نشط';
        }

        if ($this->special == null || $this->special == 'special') {
            $special = 'مميز';
        } else {
            $special = 'غير مميز';
        }
          if ($this->admin_special == null || $this->admin_special == 'special') {
            $admin_special = 'مميز';
        } else {
            $admin_special = 'غير مميز';
        }
        $domain = $this->store_id  !== null ? $this->store->domain : 'atlbha';


        return [
            'id' => $this->id,

            'name' => $this->name,
            //'sku' => $this->sku,
            'for' => $this->for,
            'slug' => $this->slug,
            'description' => $this->description,
            'purchasing_price' => $this->purchasing_price,
            'selling_price' => $this->selling_price,
            'quantity' => $this->quantity,
             'weight'=> $this->weight !== null ? $this->weight * 1000 : 500,
            'less_qty' => $this->less_qty,
            'stock' => $this->stock,
            'tags' => $this->tags,
            'cover' => $this->cover,
            'altImage' => $this->altImage,
            'discount_price' => $this->discount_price !== null ? $this->discount_price : 0,
            'SEOdescription' => $this->SEOdescription !== ""? explode(',', $this->SEOdescription):array(),
            'snappixel' => $this->snappixel,
            'tiktokpixel' => $this->tiktokpixel,
            'twitterpixel' => $this->twitterpixel,
            'instapixel' => $this->instapixel,
             'short_description' => $this->short_description,
            'robot_link' => $this->robot_link,
            'google_analytics'=> $this->google_analytics,
            'importproduct' => $this->importproduct->count(),
            'subcategory' => CategoryResource::collection(\App\Models\Category::with(['store'=> function ($query) {
             $query->select('id');
            }])->whereIn('id', explode(',', $this->subcategory_id))->get()),
            'status' => $status,
            'special' => $special,
            'admin_special' => $admin_special,
            'url' => 'https://template.atlbha.sa/' . $domain . '/shop/product/' . $this->id,
            'amount' => $this->amount,
            'productRating' => $this->productrate($this->id) !== null ? $this->productrate($this->id) : 0,
            'productRatingCount' => $this->productratecount($this->id) !== null ? $this->productratecount($this->id) : 0,
            'getOrderTotal' => $this->getOrderTotal($this->id) !== null ? $this->getOrderTotal($this->id) : 0,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'category' => new CategoryResource($this->category),
            'store' => new StoreResource($this->store),
            'images' => ImageResource::collection($this->image->where('is_deleted', 0)),
            'options' => OptionResource::collection($this->option),
                'attributes' =>AttributeResource::collection($this->attributes),
            'is_import' => false,

        ];
    }
}
