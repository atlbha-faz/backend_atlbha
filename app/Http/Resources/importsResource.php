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

        return [
            "type" => "importProduct",
            'id' => $this->id,
            'name' => $this->name,
            //'sku' => $this->sku,
            'for' => $this->for,
            'slug' => $this->slug,
            'description' => $this->description,
             'short_description' => $this->short_description,
            'purchasing_price' => $this->purchasing_price,
            'selling_price' => $this->price,
            'discount_price_import'=> $this->discount_price_import,
            'quantity' => $this->quantity,
              'weight'=> $this->weight !== null ? $this->weight * 1000 : 500,
            'less_qty' => $this->less_qty,
            'mainstock' => $this->stock,
            'stock' => $this->qty,
            'tags' => $this->tags,
            'cover' => $this->cover,
            'discount_price' => $this->discount_price,
            'discount_percent' => $this->discount_percent,
            'SEOdescription' => explode(',', $this->SEOdescription),
            'subcategory' => CategoryResource::collection(\App\Models\Category::whereIn('id', explode(',', $this->subcategory_id))->get()),
            'status' =>  $this->status == null || $this->status == 'active' ? __('message.active'):__('message.not_active'),
            'special' => $this->special == null ||$this->special  == 'special' ? __('message.special'):__('message.not_special'),
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'category' => new CategoryResource($this->category),
              'images' => ImageResource::collection($this->image),
              'productRating' => $this->productrate($this->id) !== null ? $this->productrate($this->id) : 0,
              'productRatingCount' => $this->productratecount($this->id) !== null ? $this->productratecount($this->id) : 0,

            'is_import' => true,

        ];
    }
}
