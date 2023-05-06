<?php

namespace App\Http\Resources;

use App\Http\Resources\CartDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $user = \App\Models\User::where('user_name',$this->identifier)->first();
        return [
          
            'id' => $this->id,
            'count'=>  $this->count,
            'total'=>  $this->total,
            'discount_type'=>  $this->discount_type,
            'discount_value'=>  $this->discount_value,
            'discount_total'=>  $this->discount_total,
            'free_shipping'=>  $this->free_shipping,
            'free_shipping'=>  $this->discount_expire_date,
            'user' => New UserResource($this->user),
            // 'store' => New StoreResource($this->store),
            'cartDetail' =>CartDetailResource::collection($this->cartDetails),
            'status' => "غير مكتمل",
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
       
        ];

    }
}
