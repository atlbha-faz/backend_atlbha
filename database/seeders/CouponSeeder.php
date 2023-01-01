<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coupon::create([
            'code' => 'AAD2020',
            'discount_type' => 'fixed',
            'total_price' => 50,
            'discount' => 10,
            'expire_date' => 2022_12_11,
            'start_at' => 2022_12_11,
            'total_redemptions' => 1,
            'free_shipping' => 1,
            'exception_discount_product' => 0,
            'store_id' => 1,

        ]);
        Coupon::create([
            'code' => 'AAD2040',
            'discount_type' => 'percent',
            'total_price' => 10,
            'discount' => 10,
            'expire_date' => 2022_12_11,
             'start_at' => 2022_12_11,
            'total_redemptions' => 1,

            'free_shipping' => 0,
            'exception_discount_product' => 0,
            'store_id' => 1,

        ]);
    }
}
