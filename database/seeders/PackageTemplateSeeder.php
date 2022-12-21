<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        $package=Package::where('id',1)->first()->id;
        $template=Template::where('id',1)->first()->id;
        $package_template =[
            'package_id' => $package,
            'template_id' => $template
        ];
        DB::table('packages_templates')->insert($package_template);
    }
}
