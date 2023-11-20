<?php

namespace App\Imports;

use App\Models\ShippingCity;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class SmsaImport implements ToModel,
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

        $smsaCity = ShippingCity::updateOrCreate([
            'name_en' => $name_en,
        ], [
            'name' => $row['name'],

            'country_id' => 1,

        ]);
        $smsaCity->shippingtypes()->attach(2);

        return $smsaCity;
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

    public function onFailure(Failure...$failure)
    {
    }
}
