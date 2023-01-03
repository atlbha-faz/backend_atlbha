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
            'name' => 'الرياض',
            'name_en' => 'Riyadh',
            'code' => '00966',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'مكة المكرمة',
            'name_en' => 'makah',
            'code' => '00966',
           'country_id'=>1
        ]);
          City::create([
            'name' =>  'المدينة المنورة',
            'name_en' => 'Riyadh',
            'code' => '00966',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'القصيم',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
        ]);
          City::create([
            'name' => 'الشرقية',
            'name_en' => 'Riyadh',
            'code' => '00966',
           'country_id'=>1
        ]);
        City::create([
            'name' => 'عسير',
            'name_en' => 'jeddh',
            'code' => '00966',
          'country_id'=>1
        ]);
          City::create([
            'name' => 'تبوك',
            'name_en' => 'Riyadh',
            'code' => '00966',
           'country_id'=>1
        ]);
        City::create([
            'name' => 'حائل',
            'name_en' => 'jeddh',
            'code' => '00966',
          'country_id'=>1
        ]);
          City::create([
            'name' => ' الحدود الشمالية',
            'name_en' => 'Riyadh',
            'code' => '00966',
           'country_id'=>1
        ]);
        City::create([
            'name' => 'جازان',
            'name_en' => 'jeddh',
            'code' => '00966',
            'country_id'=>1
        ]);
          City::create([
            'name' => 'نجران',
            'name_en' => 'Riyadh',
            'code' => '00966',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'الباحة',
            'name_en' => 'jeddh',
            'code' => '00966',
             'country_id'=>1
        ]);
          City::create([
            'name' => 'الجوف',
            'name_en' => 'Riyadh',
            'code' => '00966',
             'country_id'=>1
        ]);
        City::create([
            'name' => 'فرسان',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
        ]);



    }
}
