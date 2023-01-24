<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Postcategory;
use App\Models\Page_page_category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Postcategory::create([
            'name' => 'التجارة الالكترونية',

        ]);
        Postcategory::create([
            'name' => ' التسويق الرقمي',

        ]);
        Postcategory::create([
            'name' => ' ادارة الاسواق',
        ]);
        Page::create([
            'title' => 'التجارةالالكترونية',
            'page_content' => 'التجارةالالكترونية',
            'seo_title' => 'عنوان',
            'seo_link'=>'http',
            'seo_desc'=>"this is description",
            'tags'=>'about us',
            'user_id'=>1,
            'image'=>"image.png",
            'postcategory_id'=>1
        ]);
        Page::create([
            'title' => 'التسويق الرقمي',
            'page_content' => 'التسويق الرقمي',
            'seo_title' => 'عنوان',
            'seo_link'=>'http',
            'seo_desc'=>"this is description",
            'tags'=>'about us',
            'user_id'=>1,
            'image'=>"image.png",
            'postcategory_id'=>2
        ]);
        Page::create([
            'title' => 'من نحن',
            'page_content' => 'من نحن',
            'seo_title' => 'عنوان',
            'seo_link'=>'http',
            'seo_desc'=>"this is description",
            'tags'=>'about us',
            'user_id'=>1,
          
        ]);
        Page_page_category::create([
            'page_id'=>1,
            'page_category_id'=>1,
          
           ]);

           Page_page_category::create([
            'page_id'=>2,
            'page_category_id'=>1,
          
           ]);
           Page_page_category::create([
            'page_id'=>3,
            'page_category_id'=>3,
          
           ]);
    }
}
