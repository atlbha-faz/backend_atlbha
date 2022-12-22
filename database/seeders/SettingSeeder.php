<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  
    public function run()
    {
        Setting::create([
            'name' => 'الاعدادات الاساسية',
            'description' => 'اهم الاعدادات للموقع',
            'link' => 'http:setting.com',
            'email' => 'site@gmail.com',
            'phoneNumber' => '55065623',
            'logo' => 'icon.png',
            'icon' => 'icon.png',
            'address' => 'jeddah KSA',
            'country_id' => 1,
            'city_id' => 1,
        ]);
    }
}