<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Partner::create([
            'name' => 'stc',
            'logo' => 'stc.jpg',
            'link' =>'http:facebook.com',
        ]);
        Partner::create([
            'name' => 'stc',
            'logo' => 'stc.jpg',
            'link' =>'http//:Instegram.com',
        ]);
        Partner::create([
            'name' => 'mashaheer',
            'logo' => 'ms.png',
            'link' =>'http//:twitter.com',
        ]);
      
        Partner::create([
            'name' => 'mashaheer',
            'logo' => 'ms.png',
            'link' =>'http//:Snapchat.com',
        ]);
    }
}
