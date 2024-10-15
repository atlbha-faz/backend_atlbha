<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    { 
       
        if (!$this->importproduct->isEmpty()) {
            $import = true;
            $type = "importProduct";
           
            $object=$this->importproduct->first();
          
        } else {
            $import = false;
            $type = null;
            $object=$this;
        }

        if ($object->status == null || $object->status == 'active') {
            $status = __('message.active');
        } else {
            $status = __('message.not_active');
        }

        if ($object->special == null || $object->special == 'special') {
            $special = __('message.special');
        } else {
            $special = __('message.not_special');
        }
        if ($this->admin_special == null || $this->admin_special == 'special') {
            $admin_special = __('message.special');
        } else {
            $admin_special = __('message.not_special');
        }
        
        $domain = $object->store->domain ;

       
        return [
            'id' => $this->id,
            'name' => $this->name,
            //'sku' => $this->sku,
            'for' => $this->for,
            'slug' => $this->slug,
            'description' => $this->description,
            'purchasing_price' => $this->purchasing_price,
            'selling_price' => $import == true ? $object->price : $this->selling_price,
            'quantity' => $this->quantity,
            'weight' => $this->weight !== null ? $this->weight * 1000 : 500,
            'less_qty' => $this->less_qty,
            'mainstock' =>$import == true ?$this->stock: null,
            'stock' =>$import == true ? $object->qty:$this->stock,
            'tags' => $this->tags,
            'cover' => $this->cover,
            'discount_price' =>$import == true ? $object->discount_price_import : $this->discount_price,
            'SEOdescription' => $this->SEOdescription != null ?  explode(',',$this->SEOdescription) : array(),
            'snappixel' => $this->snappixel,
            'tiktokpixel' => $this->tiktokpixel,
            'twitterpixel' => $this->twitterpixel,
            'instapixel' => $this->instapixel,
            'short_description' => $this->short_description,
            'robot_link' => $this->robot_link,
            'google_analytics' => $this->google_analytics,
            'importproduct' => $this->importproduct->count(),
            'subcategory' => CategoryResource::collection(\App\Models\Category::with(['store' => function ($query) {
                $query->select('id');
            }])->whereIn('id', explode(',', $this->subcategory_id))->get()),
            'status' => $status,
            'special' => $special,
            'admin_special' => $admin_special,
            'url' => 'https://template.atlbha.sa/' . $domain . '/shop/product/' . $this->id,
            'amount' => $this->amount,
            'product_has_options' => $this->product_has_options,
            'productRating' => $this->productrate($this->id) !== null ? $this->productrate($this->id) : 0,
            'productRatingCount' => $this->productratecount($this->id) !== null ? $this->productratecount($this->id) : 0,
            'getOrderTotal' => $this->getOrderTotal($this->id) !== null ? $this->getOrderTotal($this->id) : 0,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'category' => new CategoryResource($this->category),
            'store' =>new StoreResource($object->store),
            'images' =>ImageResource::collection($this->image->where('is_deleted', 0)),
            'options' => $object->is_service == 0 ? OptionResource::collection( $object->option->where('is_deleted', 0)): null,
            'attributes' => AttributeResource::collection($this->attributes),
            'type' => $type,
            'is_import' => $import,

        ];

    }

}
