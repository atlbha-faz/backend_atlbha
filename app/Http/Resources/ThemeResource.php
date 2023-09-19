<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
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
            'searchBorder' => $request->searchBorder,
            'searchBg' =>$request->searchBg,
            'categoriesBg' => $request->categoriesBg,
            'menuBg' => $request->menuBg,
            'layoutBg' => $request->layoutBg,
            'iconsBg' =>$request->iconsBg,
            'productBorder' => $request->productBorder,
            'productBg' =>$request->productBg,
            'filtersBorder' => $request->filtersBorder,
            'filtersBg' => $request->filtersBg,
            'mainButtonBg' => $request->mainButtonBg,
            'mainButtonBorder' => $request->mainButtonBorder,
            'subButtonBg' => $request->subButtonBg,
            'subButtonBorder' => $request->subButtonBorder,
            'footerBorder' => $request->footerBorder,
            'footerBg' => $request->footerBg,
            'store' => New StoreResource($this->store),
        ];
    }
}
