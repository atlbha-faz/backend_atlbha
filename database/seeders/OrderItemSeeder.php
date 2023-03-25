<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderItem::create([
            'order_id' => 1,
            'product_id' => 6,
            'store_id' => 1,
            'user_id' => 6,
            'price' => 200,
            'discount' => 0,
            'quantity' => 2,
            'total_price' => 400,
            'order_status' => 'new',
            'payment_status' => 1,

        ]);
      OrderItem::create([
            'order_id' => 1,
            'product_id' => 2,
            'store_id' => 7,
            'user_id' => 6,
            'price' => 300,
            'discount' => 0,
            'quantity' => 2,
            'total_price' => 600,
            'order_status' => 'new',
            'payment_status' => 1,

        ]);
       
       OrderItem::create([
            'order_id' => 2,
            'product_id' => 6,
            'store_id' => 1,
            'user_id' => 6,
            'price' => 200,
            'discount' => 0,
            'quantity' => 2,
            'total_price' => 400,
            'order_status' => 'new',
            'payment_status' => 1,

        ]);
      OrderItem::create([
            'order_id' => 2,
            'product_id' => 8,
            'store_id' => 2,
            'user_id' => 6,
            'price' => 500,
            'discount' => 400,
            'quantity' => 3,
            'total_price' => 600,
            'order_status' => 'new',
            'payment_status' => 1,

        ]);
    }
}
