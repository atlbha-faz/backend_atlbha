<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Page_category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PageFactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::factory()->count(1)->create();
        Page_category::factory()->count(1)->create();
        // Get all the roles attaching up to 3 random roles to each user
        $page_categories=Page_category::all();

        // Populate the pivot table
        Page::all()->each(function ($page) use ($page_categories) {
      $page->page_categories()->attach(
        $page_categories->random(rand(1, 1))->pluck('id')->toArray()
             );
        });
    }
}