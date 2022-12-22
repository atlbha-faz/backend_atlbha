<?php

namespace Database\Seeders;

use App\Models\Paymenttype;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymenttypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Paymenttype::create([
            'name' => 'paypal',
            'image' => 'p.png',
        ]);
    }
}