<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::create([
            'name' => 'جده',
            'name_en' => 'jeddh',
            'code' => '00966',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'الرياض',
            'name_en' => 'Riyadh',
            'code' => '00966',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'دبي',
            'name_en' => 'Dubai',
            'code' => '00967',
            'country_id'=>2
        ]);
        City::create([
            'name' => 'ابوظبي',
            'name_en' => 'Abu Dhabi',
            'code' => '00967',
            'country_id'=>2
        ]);
    }
}