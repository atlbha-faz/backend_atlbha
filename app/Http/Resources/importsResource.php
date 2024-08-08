<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class importsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        // return [
        //     "type" => "importProduct",
        //     'id' => $this->id,
        //     'name' => $this->name,
        //     //'sku' => $this->sku,
        //     'for' => $this->for,
        //     'slug' => $this->slug,
        //     'description' => $this->description,
        //      'short_description' => $this->short_description,
        //     'purchasing_price' => $this->purchasing_price,
        //     'selling_price' => $this->price,
        //     'discount_price_import'=> $this->discount_price_import,
        //     'quantity' => $this->quantity,
        //       'weight'=> $this->weight !== null ? $this->weight * 1000 : 500,
        //     'less_qty' => $this->less_qty,
        //     'mainstock' => $this->stock,
        //     'stock' => $this->qty,
        //     'tags' => $this->tags,
        //     'cover' => $this->cover,
        //     'discount_price' => $this->discount_price,
        //     'discount_percent' => $this->discount_percent,
        //     'SEOdescription' => explode(',', $this->SEOdescription),
        //     'subcategory' => CategoryResource::collection(\App\Models\Category::whereIn('id', explode(',', $this->subcategory_id))->get()),
        //     'status' =>  $this->status == null || $this->status == 'active' ? __('message.active'):__('message.not_active'),
        //     'special' => $this->special == null ||$this->special  == 'special' ? __('message.special'):__('message.not_special'),
        //     'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
        //     'created_at' => (string) $this->created_at,
        //     'updated_at' => (string) $this->updated_at,
        //     'category' => new CategoryResource($this->category),
        //       'images' => ImageResource::collection($this->image),
        //       'productRating' => $this->productrate($this->id) !== null ? $this->productrate($this->id) : 0,
        //       'productRatingCount' => $this->productratecount($this->id) !== null ? $this->productratecount($this->id) : 0,

        //     'is_import' => true,

        // ];
    
        if ($this->status == null || $this->status == 'active') {
          $status = __('message.active');
      } else {
          $status =  __('message.not_active');
      }

      if ($this->special == null || $this->special == 'special') {
          $special =  __('message.special');
      } else {
          $special =__('message.not_special');
      }
      if ($this->product->admin_special == null || $this->product->admin_special == 'special') {
          $admin_special = __('message.special');
      } else {
          $admin_special =__('message.not_special');
      }
      $domain = $this->product->store_id !== null ? $this->product->store->domain : 'atlbha';

      if ($this->product->store_id == null) {
          $import = true;
          $type = "importProduct";
      } else {
          $import = false;
          $type = null;
      }

          return [
              'id' => $this->product->id,
              'name' => $this->product->name,
              //'sku' => $this->product->sku,
              'for' => $this->product->for,
              'slug' => $this->product->slug,
              'description' => $this->product->description,
              'purchasing_price' => $this->product->purchasing_price,
              'selling_price' => $this->price,
              'quantity' => $this->product->quantity,
              'weight' => $this->product->weight !== null ? $this->product->weight * 1000 : 500,
              'less_qty' => $this->product->less_qty,
              'mainstock' => $import == true ? (\App\Models\Product::where('id', $this->product->id)->first()->stock) : $this->stock,
              'stock' => $this->qty,
              'tags' => $this->product->tags,
              'cover' => $this->product->cover,
              'discount_price_import' => $this->discount_price_import !== null ?( $this->discount_price_import > 0 ?  $this->discount_price_import :""):"",
              'SEOdescription' =>$this->product->SEOdescription != null ?explode(',',$this->product->SEOdescription): array(),
              'snappixel' => $this->product->snappixel,
              'tiktokpixel' => $this->product->tiktokpixel,
              'twitterpixel' => $this->product->twitterpixel,
              'instapixel' => $this->product->instapixel,
              'short_description' => $this->product->short_description,
              'robot_link' => $this->product->robot_link,
              'google_analytics' => $this->product->google_analytics,
              'importproduct' => $this->product->importproduct->count(),
              'subcategory' => CategoryResource::collection(\App\Models\Category::with(['store' => function ($query) {
                  $query->select('id');
              }])->whereIn('id', explode(',', $this->product->subcategory_id))->get()),
              'status' => $status,
              'special' => $special,
              'admin_special' => $admin_special,
              'url' => 'https://template.atlbha.sa/' . $domain . '/shop/product/' . $this->product->id,
              'amount' => $this->product->amount,
              'product_has_options' => $this->product->product_has_options,
              'productRating' => $this->product->productrate($this->product->id) !== null ? $this->product->productrate($this->product->id) : 0,
              'productRatingCount' => $this->product->productratecount($this->product->id) !== null ? $this->product->productratecount($this->product->id) : 0,
              'getOrderTotal' => $this->product->getOrderTotal($this->product->id) !== null ? $this->product->getOrderTotal($this->product->id) : 0,
              'is_deleted' => $this->product->is_deleted !== null ? $this->product->is_deleted : 0,
              'created_at' => (string) $this->product->created_at,
              'updated_at' => (string) $this->product->updated_at,
              'category' => new CategoryResource($this->product->category),
              'store' => new StoreResource($this->store),
               'images' => $import == true ? ImageResource::collection(\App\Models\Product::where('id', $this->product->id)->first()->image->where('is_deleted', 0)) : ImageResource::collection($this->product->image->where('is_deleted', 0)),
              'options' => OptionResource::collection($this->option),
              'attributes' => $import == true ? AttributeResource::collection(\App\Models\Product::where('id', $this->product->id)->first()->attributes) : AttributeResource::collection($this->product->attributes),
              'type' => $type,
              'is_import' => $import,

          ];

      }

    
}
