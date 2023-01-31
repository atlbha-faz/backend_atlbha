<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223',
             'for'=>'etlobha',
            'description'=>' سماعة هيدفدون أصلية',
            'purchasing_price'=>10,
            'selling_price'=>10,
            'quantity'=>10,
            'less_qty'=>10,
            'stock'=>20,
            'cover'=>'product.png',
            'discount_price'=>0,
            'discount_percent'=>0,
            'category_id' =>1,
            'subcategory_id'=>2,
            'special'=>'special'

            ]);

            Product::create([
                'name' =>'سماعة هيدفدون أصلية',
                 'sku' => '1233',
                 'for'=>'store',
                'description'=>' سماعة هيدفدون أصلية',
                'purchasing_price'=>10,
                'selling_price'=>10,
                'quantity'=>10,
                'less_qty'=>10,
                'stock'=>20,
                'cover'=>'headphone.png',
                'discount_price'=>0,
                'discount_percent'=>0,
                'category_id' =>3,
                'subcategory_id'=>4,
                'store_id'=>1,
                'special'=>'special'
                ]);
                Product::create([
                'name' =>'جوال',
                 'sku' => '125',
                 'for'=>'store',
                'description'=>' جوال',
                'purchasing_price'=>10,
                'selling_price'=>10,
                'quantity'=>10,
                'less_qty'=>10,
                'stock'=>20,
                'cover'=>'phone.jpg',
                'discount_price'=>0,
                'discount_percent'=>0,
                'category_id' =>3,
                'subcategory_id'=>4,
                'store_id'=>1,
                'special'=>'special',
                ]);
                Product::create([
                'name' =>'لعبة اطفال',
                 'sku' => '126',
                 'for'=>'store',
                'description'=>' لعبة اطفال',
                'purchasing_price'=>10,
                'selling_price'=>10,
                'quantity'=>10,
                'less_qty'=>10,
                'stock'=>20,
                'cover'=>'game.jpg',
                'discount_price'=>0,
                'discount_percent'=>0,
                'category_id' =>5,
                'subcategory_id'=>6,
                'store_id'=>2,
                'special'=>'special',
                ]);
    }
}
