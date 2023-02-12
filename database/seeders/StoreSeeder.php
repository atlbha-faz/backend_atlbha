<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\Package;
use App\Models\Package_store;
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
             'user_id'=>null,
             'special'=>'special',

        ]);
 Store::create([
            'store_name' => 'امازون',
            'store_email' => 'a1@gmail.com',
            'domain' => 'https//www.a.com',
            'icon' => 'Amazon_logo.png',
            'phonenumber' => '0096650775499',
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
             'user_id'=>null,
             'special'=>'special',

        ]);
 Store::create([
            'store_name' => 'امازون',
            'store_email' => 'a2@gmail.com',
            'domain' => 'https//www.a.com',
            'icon' => 'Amazon_logo.png',
            'phonenumber' => '0096650775498',
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
             'user_id'=>null,
             'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'امازون',
            'store_email' => 'a3@gmail.com',
            'domain' => 'https//www.a.com',
            'icon' => 'Amazon_logo.png',
            'phonenumber' => '0096650775497',
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
             'user_id'=>null,
             'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'امازون',
            'store_email' => 'a4@gmail.com',
            'domain' => 'https//www.a.com',
            'icon' => 'Amazon_logo.png',
            'phonenumber' => '0096650775496',
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
             'user_id'=>null,
             'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'امازون',
            'store_email' => 'a5@gmail.com',
            'domain' => 'https//www.a.com',
            'icon' => 'Amazon_logo.png',
            'phonenumber' => '0096650775495',
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
             'user_id'=>null,
             'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'امازون',
            'store_email' => 'a6@gmail.com',
            'domain' => 'https//www.a.com',
            'icon' => 'Amazon_logo.png',
            'phonenumber' => '0096650775494',
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
             'user_id'=>null,
             'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'امازون',
            'store_email' => 'a7@gmail.com',
            'domain' => 'https//www.a.com',
            'icon' => 'Amazon_logo.png',
            'phonenumber' => '0096650775493',
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
             'user_id'=>null,
             'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'نون',
            'store_email' => 'b@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775492',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
             Store::create([
            'store_name' => 'نون',
            'store_email' => 'c@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775491',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
          Store::create([
            'store_name' => 'نون',
            'store_email' => 'd@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775490',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'نون',
            'store_email' => 'e@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775489',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'نون',
            'store_email' => 'f@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775488',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'نون',
            'store_email' => 'g@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775487',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'امازون',
            'store_email' => 'h@gmail.com',
            'domain' => 'https//www.a.com',
            'icon' => 'Amazon_logo.png',
            'phonenumber' => '0096650775486',
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
             'user_id'=>null,
             'special'=>'special',

        ]);


         Store::create([
            'store_name' => 'نون',
            'store_email' => 'i@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775485',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
             Store::create([
            'store_name' => 'نون',
            'store_email' => 'j@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775484',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
          Store::create([
            'store_name' => 'نون',
            'store_email' => 'k@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775483',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'نون',
            'store_email' => 'l@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775482',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'نون',
            'store_email' => 'm@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775481',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
        
        Package_store::create([
          'package_id'=>1,
          'store_id'=>1,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        Package_store::create([
          'package_id'=>2,
          'store_id'=>2,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        Package_store::create([
          'package_id'=>3,
          'store_id'=>2,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>1
         ]);
         Store::create([
            'store_name' => 'نون',
            'store_email' => 'n@gmail.com',
            'domain' => 'https//www.n.com',
            'icon' => 'noon.png',
            'phonenumber' => '0096650775470',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
        
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b2@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b23@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b3@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b4@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b5@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b6@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b7@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b8@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b9@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b10@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b11@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b12@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b13@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b14@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b15@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b16@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b17@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b18@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b19@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b20@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b21@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
            Store::create([
            'store_name' => 'نون',
            'store_email' => 'b22@gmail.com',
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
            'user_id'=>null,
            'special'=>'special',

        ]);
         Store::create([
            'store_name' => 'امازون',
            'store_email' => 'o@gmail.com',
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
             'user_id'=>null,
             'special'=>'special',

        ]);
        
        
        Package_store::create([
          'package_id'=>1,
          'store_id'=>2,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>2
         ]);
        
         Package_store::create([
          'package_id'=>1,
          'store_id'=>3,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>3
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>4,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>4
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>5,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>5
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>6,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>6
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>7,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>7
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>8,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>8
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>9,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>9
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>10,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>10
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>11,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>11
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>12,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>12
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>13,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>13
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>14,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>14
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>15,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>15
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>16,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>16
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>17,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>17
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>18,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>18
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>19,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>19
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>20,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>20
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>21,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>21
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>22,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>22
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>23,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>23
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>24,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>24
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>25,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>25
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>26,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>26
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>27,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>27
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>28,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>28
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>29,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>29
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>30,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>30
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>31,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>31
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>32,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>32
         ]);

         Package_store::create([
          'package_id'=>1,
          'store_id'=>33,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>33
         ]);
        
         Package_store::create([
          'package_id'=>1,
          'store_id'=>34,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>34
         ]);
        
 Package_store::create([
          'package_id'=>1,
          'store_id'=>35,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>35
         ]);
        
         Package_store::create([
          'package_id'=>1,
          'store_id'=>36,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>36
         ]);
        
         Package_store::create([
          'package_id'=>1,
          'store_id'=>37,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>37
         ]);

         Package_store::create([
          'package_id'=>1,
          'store_id'=>38,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>38
         ]);
        
         Package_store::create([
          'package_id'=>1,
          'store_id'=>39,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>39
         ]);
        
         Package_store::create([
          'package_id'=>1,
          'store_id'=>40,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>40
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>41,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>41
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>42,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>1,
         'store_id'=>42
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>43,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>2,
         'store_id'=>43
         ]);
         Package_store::create([
          'package_id'=>1,
          'store_id'=>44,
          'periodtype' => 'year',
            'start_at' => '2022-03-01',
            'end_at' => '2023-03-01',
        ]);
        
        
        activities_stores::create([
       'activity_id'=>2,
         'store_id'=>44
         ]);
    //      Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => '@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //          Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //       Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //      Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //      Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //      Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //      Store::create([
    //         'store_name' => 'امازون',
    //         'store_email' => 'a@gmail.com',
    //         'domain' => 'https//www.a.com',
    //         'icon' => 'Amazon_logo.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is amazon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'Amazon_logo.png',
    //         'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //          'user_id'=>null,
    //          'special'=>'special',

    //     ]);


    //      Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //          Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //       Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //      Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //      Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //      Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //      Store::create([
    //         'store_name' => 'امازون',
    //         'store_email' => 'a@gmail.com',
    //         'domain' => 'https//www.a.com',
    //         'icon' => 'Amazon_logo.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is amazon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'Amazon_logo.png',
    //         'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //          'user_id'=>null,
    //          'special'=>'special',

    //     ]);


    //      Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //          Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //       Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //      Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //      Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //      Store::create([
    //         'store_name' => 'نون',
    //         'store_email' => 'n@gmail.com',
    //         'domain' => 'https//www.n.com',
    //         'icon' => 'noon.png',
    //         'phonenumber' => '0096650775433',
    //         'description' => 'this is noon store',
    //         'snapchat' => 'https//www.snapchat.com',
    //         'facebook' => 'https//www.facebook.com',
    //         'twiter' => 'https//www.twiter.com',
    //         'youtube' => 'https//www.youtube.com',
    //         'instegram' => 'https//www.instegram.com',
    //         'logo' => 'noon.png',
    //        'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //         'package_id' => 1,
    //         'country_id' => 1,
    //         'city_id' =>1,
    //         'user_id'=>null,
    //         'special'=>'special',

    //     ]);
    //     activities_stores::create([
    //    'activity_id'=>1,
    //      'store_id'=>1
    //     ]);
    //       activities_stores::create([
    //    'activity_id'=>1,
    //      'store_id'=>2
    //     ]);


    // Package_store::create([
    //       'package_id'=>1,
    //       'store_id'=>1,
    //       'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //     ]);
    //     Package_store::create([
    //       'package_id'=>2,
    //       'store_id'=>2,
    //       'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //     ]);
    //     Package_store::create([
    //       'package_id'=>3,
    //       'store_id'=>3,
    //       'periodtype' => 'year',
    //         'start_at' => '2022-03-01',
    //         'end_at' => '2023-03-01',
    //     ]);
    }
}
