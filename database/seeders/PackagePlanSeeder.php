<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackagePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $package=Package::where('id',1)->first()->id;
        $plan=Plan::where('id',1)->first()->id;
        $package_plane =[
            'package_id' => $package,
            'plan_id' => $plan
        ];
        DB::table('packages_plans')->insert($package_plane);

    }
}
