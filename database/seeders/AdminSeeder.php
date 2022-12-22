<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'user_id'=>1,
            'name' => 'superAdmin',
            'user_name' => 'superAdmin',
            'email' =>'admin@gmail.com' ,
            'password'=>'12345678',
            'gender' =>'male',
            'user_type' =>'admin',
            'country_id' => 1,
            'city_id'=> 1,
            'verified'=>1,
            'store_id' => null
           
        ]);
        User::create([
            'user_id'=>2,
            'name' => 'Admin',
            'user_name' => 'Admin',
            'email' =>'admin56@gmail.com' ,
            'password'=>'12345678',
             'gender' =>'male',
            'user_type' =>'admin_employee',
            'country_id' => 1,
            'city_id'=> 1,
            'verified'=>1,
            'store_id' => null

        ]);

    }
}
