<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Image;
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
        
        for($i=1 ; $i<121 ; $i++){
               Image::create([
                'image'=>'game.jpg',
                'product_id'=>$i,
                ]);
        }
    }
}
