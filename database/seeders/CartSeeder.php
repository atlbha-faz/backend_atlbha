<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cart::create([
            'user_id'=>7,
            'total'=>20,
            'count'=>2,
            'discount_type'=>'null',
            'discount_value'=>0,
            'discount_total'=>0,
            'free_shipping'=>0,
            'store_id' => 1,
            'created_at'=>'2023-05-01 09:12:10',
            'updated_at'=>'2023-05-01 09:12:10'
        ]);
        Cart::create([
            'user_id'=>8,
            'total'=>20,
            'count'=>2,
            'discount_type'=>'null',
            'discount_value'=>0,
            'discount_total'=>0,
            'free_shipping'=>0,
            'store_id' => 1,
            'created_at'=>'2023-05-01 09:12:10',
            'updated_at'=>'2023-05-01 09:12:10'

        ]);
    }
}
