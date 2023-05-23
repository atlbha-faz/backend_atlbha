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
            if($this->status ==null || $this->status == 'active'){
            $status = 'نشط';
        }else{
            $status = 'غير نشط';
        }
        
        if($this->special ==null || $this->special == 'special'){
            $special = 'مميز';
        }else{
            $special = 'غير مميز';
        }
        
       return [
            'id' =>$this->id,
           
            'name' => $this->name,
            //'sku' => $this->sku,
            'for' => $this->for,
             'slug' => $this->slug,
            'description' => $this->description,
            'purchasing_price' => $this->purchasing_price,
            'selling_price' => $this->selling_price,
            'quantity' => $this->quantity,
            'less_qty' => $this->less_qty,
            'stock' => $this->stock,
            'tags' => $this->tags,
            'cover' =>$this->cover,
            'discount_price'=>$this->discount_price,
            'discount_percent'=>$this->discount_percent,
            'SEOdescription'=>$this->SEOdescription,
            'importproduct'=>$this->importproduct->count(),
            'subcategory' => CategoryResource::collection(\App\Models\Category::whereIn('id',explode(',',$this->subcategory_id))->get()),
            'status' => $status,
            'special' => $special ,
            'productRating'=>$this->productrate($this->id) !== null ? $this->productrate($this->id) : 0,
            'getOrderTotal'=>$this->getOrderTotal($this->id) !== null ? $this->getOrderTotal($this->id) : 0,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
           
            'category' => New CategoryResource($this->category),
            'store' => New StoreResource($this->store),
         'images' => ImageResource::collection($this->image),
           'options'=>OptionResource::collection($this->option),
           
             'is_import' =>false,


       ];
        }
}
