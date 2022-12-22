<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StoreUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    User::create([
        'user_id'=>4,
        'name' => 'roqia',
        'user_name' =>'roqia',
        'email' =>'roqia@gmail.com',
        'password' =>'12345678',
        'gender' =>'female',
        'phonenumber' => 05056665,
        'image'=>'image.jpg',
        'user_type'=>'store',
        'country_id' => 1,
        'city_id'=> 1,
        'verified'=>1,
        'store_id'=>1,
      ]);
 User::create([
        'user_id'=>3,
        'name' => 'rawaa',
        'user_name' =>'rawaa',
        'email' =>'rawaa@gmail.com',
        'password' =>'12345678',
        'gender' =>'female',
        'phonenumber' => 05056665,
        'image'=>'image.jpg',
        'user_type'=>'store',
        'country_id' => 1,
        'city_id'=> 1,
        'verified'=>1,
        'store_id'=>2,
      ]);
       User::create([
        'user_id'=>5,
        'name' => 'ali',
        'user_name' =>'ali',
        'email' =>'ali@gmail.com',
        'password' =>'12345678',
        'gender' =>'male',
        'phonenumber' => 05056665,
        'image'=>'image.jpg',
        'user_type'=>'customer',
        'country_id' => 1,
        'city_id'=> 1,
        'verified'=>1,
        'store_id'=>2,
      ]);

}

}
