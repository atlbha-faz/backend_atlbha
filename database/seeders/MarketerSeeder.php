<?php

namespace Database\Seeders;

use App\Models\Marketer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MarketerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Marketer::create([
            'name' => 'احمد',
            'email'=>'ahmed@gmail.com',
            'image'=> 'man.png',
            'mobile'=> '55076453',
            'snapchat'=>'snapchat',
            'facebook'=>'facebook',
            'twiter'=>'twiter',
            'whatsapp'=>'whatsapp',
            'youtube'=>'youtube',
            'instegram'=>'instegram',
            'socialmediatext'=>'socialmediatext',
            'city_id'=>1,
            'country_id'=>1,

        ]);
    }
}