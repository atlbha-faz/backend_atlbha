<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TemplateFactoryeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        {
        // Package::factory()->count(1)->create();
        Template::factory()->count(1)->create();
        // Get all the roles attaching up to 3 random roles to each user
        $templates = Template::all();

        // Populate the pivot table
        Package::all()->each(function ($package) use ($templates) {
      $package->templates()->attach(
        $templates->random(rand(1, 1))->pluck('id')->toArray()
             );
        });
    }
    }
}
