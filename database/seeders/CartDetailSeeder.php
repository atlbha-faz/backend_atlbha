<?php

namespace Database\Seeders;

use App\Models\CartDetail;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CartDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CartDetail::create([
            'cart_id'=>1,
            'product_id'=>1,
            'qty'=>1,
            'price'=>'10',
        ]);
        CartDetail::create([
            'cart_id'=>1,
            'product_id'=>2,
            'qty'=>1,
            'price'=>'10',
        ]);
        CartDetail::create([
            'cart_id'=>2,
            'product_id'=>1,
            'qty'=>1,
            'price'=>'10',
        ]);
        CartDetail::create([
            'cart_id'=>2,
            'product_id'=>2,
            'qty'=>1,
            'price'=>'10',
        ]);
    }
}
