<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class website_settingResource extends JsonResource
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
            'id' =>$this->id,
            'key' => $this->key,
            'value' => $this->value,
            'is_deleted' => $this->is_deleted,
            'created_at' => (string) $this->created_at,
             'updated_at' => (string) $this->updated_at,
          ];
    }
}