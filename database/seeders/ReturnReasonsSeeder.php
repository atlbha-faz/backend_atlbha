<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ReturnReason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReturnReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReturnReason::create([
            'title' => 'المنتج تالف بشكل جزئي',

        ]);
        ReturnReason::create([
            'title' => 'المنتج تالف بشكل كامل.',

        ]);
        ReturnReason::create([
            'title' => 'المنتج لايتطابق مع الوصف الموجود في الموقع.',
        ]);
        ReturnReason::create([
            'title' => 'المنتج غير كامل.',
        ]);
        ReturnReason::create([
            'title' => 'اخرى',
        ]);
    }
}
