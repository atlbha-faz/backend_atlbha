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
    //'code','discount_type','total_price','discount','expire_date','total_redemptions','user_redemptions','free_shipping','exception_discount_product','store_id'
    public function run()
    {
        Coupon::create([
            'code' => 'AAD2020',
            'discount_type' => 'fixed',
            'total_price' => 50,
            'discount' => 10,
            'expire_date' => 2022_12_11,
            'total_redemptions' => 1,
            'user_redemptions' => 1,
            'free_shipping' => null,
            'exception_discount_product' => null,
            'store_id' => 1,
            
        ]);
        Coupon::create([
            'code' => 'AAD2040',
            'discount_type' => 'percent',
            'total_price' => 10,
            'discount' => 10,
            'expire_date' => 2022_12_11,
            'total_redemptions' => 1,
            'user_redemptions' => 1,
            'free_shipping' => null,
            'exception_discount_product' => null,
            'store_id' => 1,
            
        ]);
    }
}