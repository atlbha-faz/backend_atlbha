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
            'name' => 'الدرعية',
            'name_en' => 'makah',
            'code' => '00966',
           'country_id'=>1
        ]);
          City::create([
            'name' =>  'المجمعة',
            'name_en' => 'Riyadh',
            'code' => '00966',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'الخرج',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
        ]);
          City::create([
            'name' => 'الدوادمى',
            'name_en' => 'Riyadh',
            'code' => '00966',
           'country_id'=>1
        ]);
        City::create([
            'name' => 'القويعية',
            'name_en' => 'jeddh',
            'code' => '00966',
          'country_id'=>1
        ]);
          City::create([
            'name' => 'وادي الدواسر',
            'name_en' => 'Riyadh',
            'code' => '00966',
           'country_id'=>1
        ]);
        City::create([
            'name' => 'الأفلاج',
            'name_en' => 'jeddh',
            'code' => '00966',
          'country_id'=>1
        ]);
          City::create([
            'name' => '  مكة',
            'name_en' => 'Riyadh',
            'code' => '00966',
           'country_id'=>1
        ]);
        City::create([
            'name' => 'جدة',
            'name_en' => 'jeddh',
            'code' => '00966',
            'country_id'=>1
        ]);
          City::create([
            'name' => 'الطائف',
            'name_en' => 'Riyadh',
            'code' => '00966',
            'country_id'=>1
        ]);
        City::create([
            'name' => 'الجموم',
            'name_en' => 'jeddh',
            'code' => '00966',
             'country_id'=>1
        ]);
          City::create([
            'name' => 'الليث ',
            'name_en' => 'Riyadh',
            'code' => '00966',
             'country_id'=>1
        ]);
        City::create([
            'name' => 'القنفذة',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
        ]);
   City::create([
            'name' => 'خليص',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
   ]);
           City::create([
            'name' => 'رابغ',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
           ]);
           City::create([
            'name' => 'المدينة المنورة',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
           ]);
           City::create([
            'name' => 'ينبع',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
           ]);
           City::create([
            'name' => 'بدر',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
           ]);
           City::create([
            'name' => 'العلا',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
           ]);
           City::create([
            'name' => 'الحناكية',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
           ]);
           City::create([
            'name' => 'مهد الذهب',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
           ]);
           City::create([
            'name' => 'خيبر',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
           ]);
  City::create([
            'name' => 'العيص',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
           ]);
             City::create([
            'name' => 'وادي الفرع',
            'name_en' => 'jeddh',
            'code' => '00966',
           'country_id'=>1
           ]);

    }
}
