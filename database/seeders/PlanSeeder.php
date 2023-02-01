<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
            'name' =>' 100 منتج ',
        ]);
        Plan::create([
            'name' =>' تصنيفات ',
        ]);
        Plan::create([
            'name' =>' دعم فني 24 ',
        ]);
        Plan::create([
            'name' =>' تجربة مجانية ',
        ]);
        Plan::create([
            'name' =>' توفير مخازن ',
        ]);
        Plan::create([
            'name' =>' تخصيص القالب ',
        ]);
        Plan::create([
            'name' =>'خدمات الاستشارة',
        ]);

       
    }
}
