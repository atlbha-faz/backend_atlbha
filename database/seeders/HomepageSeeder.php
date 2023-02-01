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
        'banar1'=>'banar1.png',
        'banarstatus1'=>1,
        'banar2'=>'baner2.png',
        'banarstatus2'=>1,
        'banar3'=>'baner3.png',
        'banarstatus3'=>1,
        'clientstatus'=>1,
        'commentstatus'=>1,
        'slider1'=>'slider1.png',
        'sliderstatus1'=>1,
        'slider2'=>'slider2.png',
        'sliderstatus2'=>1,
        'slider3'=>'slider3.png',
        'sliderstatus3'=>1,
        'store_id'=>null
       ]);
        Homepage::create([
        'logo'=>'Amazon_logo.png',
        'banar1'=>'banar.png',
        'banarstatus1'=>1,
        'banar2'=>'banar.png',
        'banarstatus2'=>1,
        'banar3'=>'banar.png',
        'banarstatus3'=>1,
        'clientstatus'=>1,
        'commentstatus'=>1,
        'slider1'=>'slider1.png',
        'sliderstatus1'=>1,
        'slider2'=>'slider2.png',
        'sliderstatus2'=>1,
        'slider3'=>'slider3.png',
        'sliderstatus3'=>1,
        'store_id'=>1
       ]);
    }
}
