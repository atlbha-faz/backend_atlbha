<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Section::create([
            'name'=>' المنتجات المميزة',
            'status'=>'active'
        ]);

         Section::create([
            'name'=>'المتاجر المميزة',
            'status'=>'active'
        ]);
          Section::create([
            'name'=>'القسم الثالث',
            'status'=>'not_active'
        ]);
    }
}
