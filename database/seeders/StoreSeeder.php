<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\activities_stores;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Store::create([
            'store_name' => 'امازون',
            'store_email' => 'a@gmail.com',
            'domain' => 'https//www.a.com',
            'icon' => 'Amazon_logo.png',
            'phonenumber' => '0096650775433',
            'description' => 'this is amazon store',
            'snapchat' => 'https//www.snapchat.com',
            'facebook' => 'https//www.facebook.com',
            'twiter' => 'https//www.twiter.com',
            'youtube' => 'https//www.youtube.com',
            'instegram' => 'https//www.instegram.com',
            'logo' => 'Amazon_logo.png',
            'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
            'package_id' => 1,
            'country_id' => 1,
            'city_id' =>1,
             'user_id'=>null

        ]);


         Store::create([
            'store_name' => 'نون',
            'store_email' => 'n@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775433',
            'description' => 'this is noon store',
            'snapchat' => 'https//www.snapchat.com',
            'facebook' => 'https//www.facebook.com',
            'twiter' => 'https//www.twiter.com',
            'youtube' => 'https//www.youtube.com',
            'instegram' => 'https//www.instegram.com',
            'logo' => 'noon.png',
           'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
            'package_id' => 1,
            'country_id' => 1,
            'city_id' =>1,
            'user_id'=>null

        ]);
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>1
        ]);
          activities_stores::create([
       'activity_id'=>1,
         'store_id'=>2
        ]);
    }
}
