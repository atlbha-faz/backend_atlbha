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
             'sku' => '123',
             'for'=>'etlobha',
            'description'=>' سماعة هيدفدون أصلية',
            'purchasing_price'=>10,
            'selling_price'=>10,
            'quantity'=>1,
            'less_qty'=>10,
            'stock'=>20,
            'cover'=>'image',
            'discount_price'=>0,
            'discount_percent'=>0,
            'category_id' =>1,
            'subcategory_id'=>2,
         
               
            ]);

            Product::create([
                'name' =>'سماعة هيدفدون أصلية',
                 'sku' => '123',
                 'for'=>'store',
                'description'=>' سماعة هيدفدون أصلية',
                'purchasing_price'=>10,
                'selling_price'=>10,
                'quantity'=>1,
                'less_qty'=>10,
                'stock'=>20,
                'cover'=>'image',
                'discount_price'=>0,
                'discount_percent'=>0,
                'category_id' =>3,
                'subcategory_id'=>4,
                'store_id'=>1
                   
                ]);
    }
}