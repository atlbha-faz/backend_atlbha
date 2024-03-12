<?php

namespace App\Imports;

use App\Models\Region;
use App\Models\ShippingCity;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class ImileImport implements ToModel,
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
        $name_en = ShippingCity::where('name_en', $row['name_en'])->pluck('name_en')->first();
        $region_id = Region::where('name_en', $row['province'])->pluck('id')->first();
        $saeeCity = ShippingCity::updateOrCreate([
            'name_en' => $name_en,
        ], [

            'region_id' => $region_id,


        ]);

        // $saeeCity->shippingtypes()->attach(3);

        return $saeeCity;
    }

    public function rules(): array
    {
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
