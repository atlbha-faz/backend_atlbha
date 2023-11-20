<?php

namespace App\Imports;
use Throwable;
use App\Models\City;
use App\Models\Region;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SaeeImport implements ToModel,
WithHeadingRow,
SkipsOnError,
WithValidation,
//  SkipsFailures
//  SkipsFailures,
SkipsOnFailure
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // $name_en=City::where('name_en',$row['name_en'])->pluck('name_en')->first();
        // $region_id=Region::where('name_en',$row['province'])->pluck('id')->first();
        // $saeeCity = City::updateOrCreate([
        //              'name_en' =>  $name_en,
        //                     ], [
        //                         'name' => $row['name'],
        //                         'name_en' => $row['name_en'],
        //                         'region_id' => $region_id,
        //                         'country_id' => 1,
        //                         'code' => 966
        //                     ]);
        // return  $saeeCity;
    }

    public function rules(): array {
        return [
                '*.name' => 'required|string',
                '*.name_en' => 'required|string',

    
        ];
    
    }
     public function onError(Throwable $e)
        {
            return "validation er";
        }
    
        public function onFailure(Failure ...$failure)
        {
        }
}
