<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Package_Plan;
use Illuminate\Database\Seeder;
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
        Package_Plan::create([
            'package_id'=>1,
            'plan_id' =>1]);
            Package_Plan::create([
                'package_id'=>1,
                'plan_id' =>2]);
                Package_Plan::create([
                    'package_id'=>1,
                    'plan_id' =>3]);
                    Package_Plan::create([
                        'package_id'=>1,
                        'plan_id' =>4]);
            Package_Plan::create([
                'package_id'=>2,
                'plan_id' =>1]);
                Package_Plan::create([
                    'package_id'=>2,
                    'plan_id' =>2]);
                    Package_Plan::create([
                        'package_id'=>2,
                        'plan_id' =>3]);
                        Package_Plan::create([
                            'package_id'=>2,
                            'plan_id' =>4]);
                            Package_Plan::create([
                                'package_id'=>2,
                                'plan_id' =>5]);
                                Package_Plan::create([
                                    'package_id'=>2,
                                    'plan_id' =>6]);
                                    Package_Plan::create([
                                        'package_id'=>2,
                                        'plan_id' =>7]);
          
                                        Package_Plan::create([
                                            'package_id'=>3,
                                            'plan_id' =>1]);
                                            Package_Plan::create([
                                                'package_id'=>3,
                                                'plan_id' =>2]);
                                                Package_Plan::create([
                                                    'package_id'=>3,
                                                    'plan_id' =>3]);
                                                    Package_Plan::create([
                                                        'package_id'=>3,
                                                        'plan_id' =>4]);
                                                        Package_Plan::create([
                                                            'package_id'=>3,
                                                            'plan_id' =>5]);
                                                            Package_Plan::create([
                                                                'package_id'=>3,
                                                                'plan_id' =>6]);


    }
}
