<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Package::create([
            'name' => 'التاجر المبتدأ',
          'monthly_price' =>350,
          'yearly_price' =>1500,
          'discount' => 100,
        ]);
        Package::create([
            'name' => 'العلامة التجارية',
         'monthly_price' =>750,
         'yearly_price' =>2500,
         'discount' =>0,
       ]);
       Package::create([
        'name' => 'التاجر المحترف',
     'monthly_price' =>500,
     'yearly_price' =>1400,
     'discount' => 0,
   ]);
    }
}
