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
            PackageFactoryeSeeder::class,
            TemplateFactoryeSeeder::class,
            StoreSeeder::class,
            AdminSeeder::class,
            StoreUserSeeder::class,
            CategorySeeder ::class,
            ProductSeeder::class,
            ServiceSeeder::class,
            CourseSeeder::class,
            UnitSeeder::class,
            VideoSeeder::class,
            ExplainvideoSeeder::class,
            ClientSeeder::class,
            OptiontSeeder::class,
            CommentSeeder::class,
            ReplayCommentSeeder::class,
            HomepageSeeder::class,
            OfferSeeder::class,
        ]);
    }
}
