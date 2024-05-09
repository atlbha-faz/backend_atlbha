<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AtlbhaIndexSearchStoreResource extends JsonResource
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
                'id'=>$this->id,
                'name' => $this->store_name,
                'description' => $this->description,
                'image' => $this->logo,
                'url' => 'https://template.atlbha.sa/' . $this->domain,
            ];
          
       
    }
}
