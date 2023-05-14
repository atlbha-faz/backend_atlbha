<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Client::create([
            'ID_number'=>12345678,
            'first_name'=>'محمد',
            'last_name'=>'احمد',
            'email'=>'ahmed@gmail.com',
            'gender'=>'male',
            'phonenumber'=>'0096650775433',
            'image'=>'man.png',
            'country_id'=>1,
            'city_id'=>1,
            'store_id'=>1
        ]);
         Client::create([
            'ID_number'=>12345678,
            'first_name'=>'يوسف',
            'last_name'=>'احمد',
            'email'=>'yousef@gmail.com',
            'gender'=>'male',
            'phonenumber'=>'0096650775433',
            'image'=>'man.png',
            'country_id'=>1,
            'city_id'=>1,
                'store_id'=>1
        ]);
    }
}
