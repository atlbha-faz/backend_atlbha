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
             'sku' => '1223a',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h',
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
        
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a1',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b2',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c3',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d4',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e5',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h6',
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
                 'sku' => '1257',
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
                 'sku' => '1268',
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
        
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a9',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b10',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c11',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d12',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e13',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h14',
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
                 'sku' => '12515',
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
                 'sku' => '12616',
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
        
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a17',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b18',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c19',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d20',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e21',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h22',
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
                 'sku' => '12523',
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
                 'sku' => '12624',
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
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a25',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b26',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c27',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d28',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e29',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h30',
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
                 'sku' => '12531',
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
                 'sku' => '12632',
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
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a33',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b34',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c35',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d36',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e37',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h38',
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
                 'sku' => '12539',
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
                 'sku' => '12640',
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
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a41',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b42',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c43',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d44',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e45',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h46',
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
                 'sku' => '12547',
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
                 'sku' => '12648',
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
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a49',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b50',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c51',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d52',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e53',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h54',
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
                 'sku' => '12555',
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
                 'sku' => '12656',
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
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a57',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b58',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c59',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d60',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e61',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h62',
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
                 'sku' => '12563',
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
                 'sku' => '12664',
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
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a65',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b66',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c67',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d68',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e69',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h70',
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
                 'sku' => '12571',
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
                 'sku' => '12672',
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
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a73',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b74',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c75',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d76',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e77',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h78',
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
                 'sku' => '12579',
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
                 'sku' => '12680',
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
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a81',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b82',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c83',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d84',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e85',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h86',
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
                 'sku' => '12587',
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
                 'sku' => '12688',
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
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a89',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b90',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c91',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d92',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e93',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h94',
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
                 'sku' => '12595',
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
                 'sku' => '12696',
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
                'store_id'=>4,
                'special'=>'special',
                ]);
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a97',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b98',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c99',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d10',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e101',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h102',
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
                'store_id'=>5,
                'special'=>'special'
                ]);
                Product::create([
                'name' =>'جوال',
                 'sku' => '125103',
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
                'store_id'=>6,
                'special'=>'special',
                ]);
                Product::create([
                'name' =>'لعبة اطفال',
                 'sku' => '126104',
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
                'store_id'=>7,
                'special'=>'special',
                ]);
         Product::create([
            'name' =>'سماعة هيدفدون أصلية',
             'sku' => '1223a105',
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
                'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                 'sku' => '1223b106',
                 'for'=>'etlobha',
                'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                    'name' =>'ايفون 13 برو ازرق',
                     'sku' => '1223c107',
                     'for'=>'etlobha',
                    'description'=>'ايفون 13 برو ازرق',
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
                        'name' =>'حقيبة هاند باج ماركة اصلية',
                         'sku' => '1223d108',
                         'for'=>'etlobha',
                        'description'=>'حقيبة هاند باج ماركة اصلية',
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
                            'name' =>'ساعة يد ماركة سكيمي ضد الماء',
                             'sku' => '1223e109',
                             'for'=>'etlobha',
                            'description'=>'ساعة يد ماركة سكيمي ضد الماء',
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
                 'sku' => '1233h110',
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
                'store_id'=>8,
                'special'=>'special'
                ]);
                Product::create([
                'name' =>'جوال',
                 'sku' => '125111',
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
                'store_id'=>9,
                'special'=>'special',
                ]);
                Product::create([
                'name' =>'لعبة اطفال',
                 'sku' => '126112',
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
                'store_id'=>10,
                'special'=>'special',
                ]);
    }
}
