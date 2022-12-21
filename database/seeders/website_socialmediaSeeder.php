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
            'logo' => 'icon.png',
            'link' =>'http:facebook.com',
        ]);
    }
}