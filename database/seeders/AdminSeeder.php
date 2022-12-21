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
            'name' => 'superAdmin',
            'user_name' => 'superAdmin',
            'email' =>'admin@gmail.com' ,
            'password'=>Hash::make('12345678'),
            'user_type' =>'admin',
            'verified'=>1,
            'store_id' => null
           
        ]);
        User::create([
            'name' => 'Admin',
            'user_name' => 'Admin',
            'email' =>'admin56@gmail.com' ,
            'password'=>Hash::make('12345678'),
            'user_type' =>'admin_employee',
            'verified'=>1,
            'store_id' => null
           
        ]);
    }
}