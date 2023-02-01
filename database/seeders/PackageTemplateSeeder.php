<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Package_Template;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pt1= new Package_Template([
           'package_id'=>1,
           'template_id'=>1 
        ]);
        $pt1->save();
        $pt2= new Package_Template([
            'package_id'=>2,
            'template_id'=>2 
         ]);
         $pt2->save();
         $pt3= new Package_Template([
            'package_id'=>3,
            'template_id'=>3
         ]);
         $pt3->save();
       
    }
}
