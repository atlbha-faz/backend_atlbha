<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageFactoryeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::factory()->count(1)->create();
        Plan::factory()->count(3)->create();
        // Get all the roles attaching up to 3 random roles to each user
        $plans = Plan::all();

        // Populate the pivot table
        Package::all()->each(function ($package) use ($plans) {
      $package->plans()->attach(
        $plans->random(rand(1, 3))->pluck('id')->toArray()
             );
        });
    }
}
