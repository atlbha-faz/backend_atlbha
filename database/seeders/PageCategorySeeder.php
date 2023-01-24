<?php

namespace Database\Seeders;

use App\Models\Page_category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PageCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page_category::create([
            'name' => '  المدونه',
        ]);
        Page_category::create([
            'name' => ' سياسة الخصوصية ',
        ]);
        Page_category::create([
            'name' => ' من نحن',
        ]);
        Page_category::create([
            'name' => ' عام ',
        ]);
      
    }
}
