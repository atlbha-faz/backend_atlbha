<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        Theme::create([
            'searchBorder' => "#8235DC",
            'searchBg' =>"#8235DC",
            'categoriesBg' => "#8235DC",
            'menuBg' =>"#8235DC",
            'layoutBg' => "#8235DC",
            'iconsBg' =>"#8235DC",
            'productBorder' => "#8235DC",
            'productBg' =>"#8235DC",
            'filtersBorder' => "#8235DC",
            'filtersBg' => "#8235DC",
            'mainButtonBg' =>"#8235DC",
            'mainButtonBorder' => "#8235DC",
            'subButtonBg' => "#8235DC",
            'subButtonBorder' => "#8235DC",
            'footerBorder' => "#8235DC",
            'footerBg' => "#8235DC",
            'store_id'=>null
        ]);
    }
}
