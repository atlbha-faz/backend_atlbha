<?php

namespace Database\Seeders;

use App\Models\Shippingtype;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShippingtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shippingtype::create([
            'name' => 'Shipping1',
                   ]);
        Shippingtype::create([
                    'name' => 'Shipping2',
                    ]);
    }
}