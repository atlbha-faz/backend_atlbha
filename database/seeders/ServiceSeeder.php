<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
            'name' => 'التصميم',
            'description' =>'التصميم',
            'file'=>'desgin.png',
            'price'=>12

        ]);
        Service::create([
            'name' => 'الدعم الفني',
            'description' =>'الدعم الفني',
            'file'=>'tsupport.png',
            'price'=>12

        ]);
    }
}
