<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\offers_products;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     Offer::create([ // العرض الاول
     'offer_type'=>'If_bought_gets',
     'offer_title' =>'عروض الويكند',
     'offer_view' =>'store_website',
     'start_at' =>'2022-12-11',
     'end_at' =>'2023-01-01',
     'purchase_quantity'=>2,
     'purchase_type'=>'product',
     'get_quantity' =>1,
     'get_type' => 'product',
     'offer1_type'=>'free_product',
    //  'product_id'=>3,
    //  'get_product_id'=>2
     ]);
   offers_products::create([
    'offer_id'=>1,
    'product_id'=>3,
    'type'=>'buy'
   ]);
    offers_products::create([
    'offer_id'=>1,
    'product_id'=>2,
    'type'=>'get'
   ]);

          Offer::create([ //العرض الثاني
     'offer_type'=>'fixed_amount',
     'offer_title' =>'عروض الويكند',
     'offer_view' =>'store_website',
     'start_at' =>'2022-12-11',
     'end_at' =>'2023-01-01',
     'discount_value_offer2'=>80,
     'offer_apply'=>'all',
     'offer_type_minimum' =>'purchase_amount',
     'offer_amount_minimum' => 80,
     'coupon_status'=>1,

     ]);
       Offer::create([ //العرض الثالث
     'offer_type'=>'percent',
     'offer_title' =>'عروض الويكند',
     'offer_view' =>'store_website',
     'start_at' =>'2022-12-11',
     'end_at' =>'2023-01-01',
     'offer_apply'=>'all',
     'offer_type_minimum'=>'purchase_amount',
     'offer_amount_minimum' =>80,
     'discount_value_offer3' => 20,
     'maximum_discount'=>65,
     'coupon_status'=>1
     ]);
    }
}
