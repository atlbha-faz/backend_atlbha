<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OptiontSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          Option::create([
            'type' =>'color',
             'title' => 'اللون',
             'value'=>'اخضر,ازرق',
            'product_id'=>1,

            ]);
             Option::create([
            'type' =>'brand',
             'title' => 'ماركة ',
             'value'=>'ماركه1',
            'product_id'=>1,

            ]);
    }
}
