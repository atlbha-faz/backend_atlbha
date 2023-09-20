<?php

namespace Database\Seeders;

// use App\Models\Theme;
use Illuminate\Database\Seeder;
use App\Models\Theme as ThemeModels;

class Theme extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ThemeModels::create([
            'searchBorder' => "#e5e5e5",                                      
            'searchBg' =>"#ffffff",
            'categoriesBg' => "#02466a",
            'menuBg' =>"#1dbbbe",
            'layoutBg' => "#ffffff",
            'iconsBg' =>"#1dbbbe",
            'productBorder' => "#ededed",
            'productBg' =>"#ffffff",
            'filtersBorder' => "#f0f0f0",
            'filtersBg' => "#ffffff",
            'mainButtonBg' =>"#1dbbbe",
            'mainButtonBorder' => "#1dbbbe",
            'subButtonBg' => "#02466a",
            'subButtonBorder' => "#02466a",
            'footerBorder' => "#ebebeb",
            'footerBg' => "#ffffff",
            'store_id'=>null
        ]);
        ThemeModels::create([
            'searchBorder' => "#e5e5e5",                                      
            'searchBg' =>"#ffffff",
            'categoriesBg' => "#02466a",
            'menuBg' =>"#1dbbbe",
            'layoutBg' => "#ffffff",
            'iconsBg' =>"#1dbbbe",
            'productBorder' => "#ededed",
            'productBg' =>"#ffffff",
            'filtersBorder' => "#f0f0f0",
            'filtersBg' => "#ffffff",
            'mainButtonBg' =>"#1dbbbe",
            'mainButtonBorder' => "#1dbbbe",
            'subButtonBg' => "#02466a",
            'subButtonBorder' => "#02466a",
            'footerBorder' => "#ebebeb",
            'footerBg' => "#ffffff",
            'store_id'=>170
        ]);
        
      
    }
}
