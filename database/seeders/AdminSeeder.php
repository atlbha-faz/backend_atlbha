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
            'name' => 'Admin',
            'user_name' => 'Admin',
            'email' =>'admin@gmail.com' ,
            'password'=>'12345678',
            'user_type' =>'admin',
            'verified'=>1,
            'store_id' => null

        ]);

    }
}
