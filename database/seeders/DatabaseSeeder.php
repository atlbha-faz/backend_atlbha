<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
     
        $this->call([
            ActivitySeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            PermissionTableSeeder::class,
            CreateUserSeeder::class,
            PlanSeeder::class,
            TemplateSeeder::class,
            AdminSeeder::class,
            CategorySeeder ::class,
            ProductSeeder::class,
            ServiceSeeder::class,
            PageFactorySeeder::class,
        ]);
    }
}