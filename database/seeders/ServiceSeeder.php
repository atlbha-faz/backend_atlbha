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
            'file'=>'aa',
            'price'=>12
           
        ]);
        Service::create([
            'name' => 'الدعم الفني',
            'description' =>'الدعم الفني',
            'file'=>'aa',
            'price'=>12
           
        ]);
    }
}