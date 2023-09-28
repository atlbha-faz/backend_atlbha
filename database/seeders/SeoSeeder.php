<?php

namespace Database\Seeders;

use App\Models\Seo;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Seo::create([
           'google_analytics' => "goog",
            'pixel_ads' => $this->pixel_ads,
            'metatags' => $this->metatags,
            'key_words' => 'الكلمات المفتاحية ',

        ]);
          Seo::create([
            'index_page_title' => 'عنوان الصفحة الرئيسية',
            'index_page_description' => 'وصف الصفحة الرئيسية ',
            'key_words' => 'الكلمات المفتاحية ',
            'show_pages' => 'short_link',
            'link' => 'ربط جوجل انليتكس Analytics Goo',
            'robots' => 'إعدادات ملف Robots ',
            'store_id'=>1
        ]);
            Seo::create([
            'index_page_title' => 'عنوان الصفحة الرئيسية',
            'index_page_description' => 'وصف الصفحة الرئيسية ',
            'key_words' => 'الكلمات المفتاحية ',
            'show_pages' => 'short_link',
            'link' => 'ربط جوجل انليتكس Analytics Goo',
            'robots' => 'إعدادات ملف Robots ',
            'store_id'=>2
        ]);
    }
}
