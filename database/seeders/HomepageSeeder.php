<?php

namespace Database\Seeders;

use App\Models\Homepage;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HomepageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Homepage::create([
        'logo'=>'logo.png',
        'panar1'=>'panar1.jpg',
        'panarstatus1'=>1,
        'panar2'=>'paner2.jpg',
        'panarstatus2'=>1,
        'panar3'=>'paner3.jpg',
        'panarstatus3'=>1,
        'clientstatus'=>1,
        'commentstatus'=>1,
        'slider1'=>'slider1.jpg',
        'sliderstatus1'=>1,
        'slider2'=>'slider2.jpg',
        'sliderstatus2'=>1,
        'slider3'=>'slider3.jpg',
        'sliderstatus3'=>1,
        'store_id'=>null
       ]);
        Homepage::create([
        'logo'=>'Amazon_logo.png',
        'panar1'=>'panar.jpg',
        'panarstatus1'=>1,
        'panar2'=>'panar.jpg',
        'panarstatus2'=>1,
        'panar3'=>'panar.jpg',
        'panarstatus3'=>1,
        'clientstatus'=>1,
        'commentstatus'=>1,
        'slider1'=>'panar.jpg',
        'sliderstatus1'=>1,
        'slider2'=>'panar.jpg',
        'sliderstatus2'=>1,
        'slider3'=>'panar.jpg',
        'sliderstatus3'=>1,
        'store_id'=>1
       ]);
    }
}
