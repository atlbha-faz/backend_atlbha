<?php

namespace Database\Seeders;

use App\Models\Paymenttype;
use App\Models\paymenttype_store;
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
        Paymenttype::create([
            'name' => 'masterCard',
            'image' => 'm.png',
        ]);
        paymenttype_store::create([
            'paymentype_id' => 1,
            'store_id' => 1,
        ]);
        paymenttype_store::create([
            'paymentype_id' => 2,
            'store_id' => 1,
        ]);
    }
}
