<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Store;
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
            'icon' => 'icon.png',
            'phonenumber' => '050454466',
            'description' => 'this is amazon store',
            'business_license' => 'business_license.pdf',
            'ID_file' => 'ID_file.pdf',
            'accept_status' => 'accepted',
            'snapchat' => 'https//www.snapchat.com',
            'facebook' => 'https//www.facebook.com',
            'twiter' => 'https//www.twiter.com',
            'youtube' => 'https//www.youtube.com',
            'instegram' => 'https//www.instegram.com',
            'logo' => 'logo.png',
            'entity_type' => 'شركة',
            'start_at' => Carbon::now(),
            'end_at' => '2023-03-01',
            'period' => '3',
            'activity_id' => 1,
            'package_id' => 1,
            'country_id' => 1,
            'city_id' =>1,
            'user_id'=>null

        ]);


         Store::create([
            'store_name' => 'نون',
            'store_email' => 'n@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'icon.png',
            'phonenumber' => '050454466',
            'description' => 'this is noon store',
            'business_license' => 'business_license.pdf',
            'ID_file' => 'ID_file.pdf',
            'accept_status' => 'accepted',
            'snapchat' => 'https//www.snapchat.com',
            'facebook' => 'https//www.facebook.com',
            'twiter' => 'https//www.twiter.com',
            'youtube' => 'https//www.youtube.com',
            'instegram' => 'https//www.instegram.com',
            'logo' => 'logo.png',
            'entity_type' => 'شركة',
            'start_at' => Carbon::now(),
            'end_at' => '2023-03-01',
            'period' => '3',
            'activity_id' => 2,
            'package_id' => 1,
            'country_id' => 1,
            'city_id' =>1,
            'user_id'=>null

        ]);
    }
}
