<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\website_socialmedia;
class website_socialmediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        website_socialmedia::create([
            'name' => 'facebook',
            'logo' => 'f.png',
            'link' =>'http:facebook.com',
        ]);
          website_socialmedia::create([
            'name' => 'Instegram',
            'logo' => 'i.png',
            'link' =>'http//:Instegram.com',
        ]);
          website_socialmedia::create([
            'name' => 'twitter',
            'logo' => 'tw.png',
            'link' =>'http//:twitter.com',
        ]);
          website_socialmedia::create([
            'name' => 'Tik Tok',
            'logo' => 't.png',
            'link' =>'http//:Tik.com',
        ]);
          website_socialmedia::create([
            'name' => 'Snapchat',
            'logo' => 's.png',
            'link' =>'http//:Snapchat.com',
        ]);
    }
}
