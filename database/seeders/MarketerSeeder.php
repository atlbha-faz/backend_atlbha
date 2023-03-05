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
           'user_id'=>3,
            'snapchat'=>'snapchat',
            'facebook'=>'facebook',
            'twiter'=>'twiter',
            'whatsapp'=>'whatsapp',
            'youtube'=>'youtube',
            'instegram'=>'instegram',
           // 'socialmediatext'=>'socialmediatext',

        ]);
    }
}
