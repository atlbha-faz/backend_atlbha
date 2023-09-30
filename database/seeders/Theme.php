<?php

namespace Database\Seeders;

// use App\Models\Theme;
use App\Models\Theme as ThemeModels;
use Illuminate\Database\Seeder;

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
            'primaryBg' => "#ffffff",
            'secondaryBg' => "#02466a",
            'headerBg' => "#1dbbbe",
            'layoutBg' => "#ffffff",
            'iconsBg' => "#1dbbbe",
            'productBorder' => "#ededed",
            'productBg' => "#ffffff",
            'filtersBorder' => "#f0f0f0",
            'filtersBg' => "#ffffff",
            'mainButtonBg' => "#1dbbbe",
            'mainButtonBorder' => "#1dbbbe",
            'subButtonBg' => "#02466a",
            'subButtonBorder' => "#02466a",
            'footerBorder' => "#ebebeb",
            'footerBg' => "#ffffff",
            'store_id' => null,
        ]);
        ThemeModels::create([
            'primaryBg' => "#ffffff",
            'secondaryBg' => "#02466a",
            'headerBg' => "#1dbbbe",
            'layoutBg' => "#ffffff",
            'iconsBg' => "#1dbbbe",
            'productBorder' => "#ededed",
            'productBg' => "#ffffff",
            'filtersBorder' => "#f0f0f0",
            'filtersBg' => "#ffffff",
            'mainButtonBg' => "#1dbbbe",
            'mainButtonBorder' => "#1dbbbe",
            'subButtonBg' => "#02466a",
            'subButtonBorder' => "#02466a",
            'footerBorder' => "#ebebeb",
            'footerBg' => "#ffffff",
            'store_id' => 1,
        ]);

    }
}
