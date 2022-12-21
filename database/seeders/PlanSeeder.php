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
            'name' => 'دعم فني ',
        ]);        

        Plan::create([
            'name' => ' منتج 100',
        ]);
        Plan::create([
            'name' => '50 تصنيف',
        ]);
    }
}
