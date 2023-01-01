<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          Region::create([
            'name' => 'الرياض',
            'country_id'=>1
          ]);
          Region::create([
            'name' => ' مكة المكرمة',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'المدينة المنورة',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'القصيم',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'الشرقية',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'عسير',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'تبوك ',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'حائل ',
            'country_id'=>1
          ]);
          Region::create([
            'name' => ' الحدود الشمالية',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'جازان ',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'نجران ',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'الباحة ',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'الجوف ',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'فرسان ',
            'country_id'=>1
          ]);
         
    }
}