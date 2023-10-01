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


    }
}
