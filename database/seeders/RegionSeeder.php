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
            'name_en' => 'Riyadh Province',
            'country_id'=>1
          ]);
          Region::create([
            'name' => ' مكة المكرمة',
            'name_en' => 'Makkah Province',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'المدينة المنورة',
            'name_en' => 'Madinah Province',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'القصيم',
            'name_en' => 'Qassim Province',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'الشرقية',
            'name_en' => 'Eastern Province',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'عسير',
            'name_en' => 'Asir Province',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'تبوك ',
            'name_en' => 'Tabuk Province',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'حائل ',
            'name_en' => 'Hail Province',
            'country_id'=>1
          ]);
          Region::create([
            'name' => ' الحدود الشمالية',
            'name_en' => 'North Province',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'جازان ',
            'name_en' => 'Gizan Province',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'نجران ',
            'name_en' => 'Najran Province',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'الباحة ',
            'name_en' => 'Al Baha Province',
            'country_id'=>1
          ]);
          Region::create([
            'name' => 'الجوف ',
            'name_en' => 'Al Jouf Province',
            'country_id'=>1
          ]);

    }
}
