<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DayResource extends JsonResource
{
  
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        switch ($this->name) {
            case "Saturday":
             $name="السبت";
              break;
            case "Sunday":
                $name="الاحد";
              break;
            case "Monday":
                $name="الاثنين";
              break;
              case "Tuesday":
                $name="الثلاثاء";
              break;
              case "Wednesday":
             $name="الاربعاء";
                break;
            case "Thursday":
             $name="الخميس";
              break;
                 case "Friday":
                $name="الجمعة";
                break;
            default:
            $name=null;
          }
        return [
            'id' =>$this->id,
            'name' => $name,
        ];
    }
}
