<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Activity::create([
            'name' => 'الالكترونيات',
            'icon' => 'activity.svg',

        ]);
         Activity::create([
            'name' => 'هدايا والعاب',
            'icon' => 'activity.svg',

        ]);
        Activity::create([
            'name' => 'ملابس',
            'icon' => 'activity.svg',
        ]);
    }
}
