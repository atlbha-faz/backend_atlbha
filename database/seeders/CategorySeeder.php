<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Category::create([
        'number' =>0001,
         'name' => 'أجهزة كمبيوتر',
         'icon'=>'cat.png',
         'parent_id'=>null,
        'for'=>'store',


        ]);
        Category::create([
            'number' =>0002,
             'name' => 'جوالات',
             'parent_id'=>1,
            'for'=>'store',

            ]);

            Category::create([
                'number' =>0003,
                 'name' => 'أجهزة كمبيوتر',
                 'icon'=>'cat.png',
                 'parent_id'=>null,
                'for'=>'store',
                'store_id'=>1


                ]);
                Category::create([
                    'number' =>0004,
                     'name' => 'جوالات',
                     'parent_id'=>3,
                    'for'=>'store',
                    'store_id'=>1
                    ]);

                  Category::create([
                        'number' =>0005,
                         'name' => 'هدايا والعاب',
                         'parent_id'=>null,
                         'icon'=>'cat.png',
                        'for'=>'store',
                        'store_id'=>2


                        ]);
     Category::create([
                            'number' =>0006,
                             'name' => 'العاب اطفال',
                             'parent_id'=>5,
                            'for'=>'store',
                            'store_id'=>2
                            ]);
    }
}
