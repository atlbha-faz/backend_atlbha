<?php

namespace App\Http\Resources;

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
        $user = \App\Models\User::where('user_name',$this->identifier)->first();
        return [
            'username' => $user->img,
            'name' => $user->name,
            'status' => 'غير مكتمل',
            'content' =>($this->content)
        ];
    }
}
