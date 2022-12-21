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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            // ActivitySeeder::class,
            // CountrySeeder::class,
            // CitySeeder::class,
            // PackageFactoryeSeeder::class,
            // TemplateFactoryeSeeder::class,
            // StoreSeeder::class,
            // StoreUserSeeder::class,
            // CourseSeeder::class,
            // UnitSeeder::class,
             VideoSeeder::class,


        ]);
    }
}
