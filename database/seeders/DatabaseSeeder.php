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
            AdminSeeder::class,
            StoreSeeder::class,
            // AdminSeeder::class,
            StoreUserSeeder::class,
            CategorySeeder ::class,
            CurrencySeeder::class,
            ProductSeeder::class,
            ServiceSeeder::class,
            PageFactorySeeder::class,
            ContactSeeder::class,
            CouponSeeder::class,
            MaintenanceSeeder::class,
            TechnicalSupportSeeder::class,
            MarketerSeeder::class,
            PaymenttypeSeeder::class,
            PlatformSeeder::class,
            ReplaycontactSeeder::class,
            SeoSeeder::class,
            SettingSeeder::class,
            ShippingtypeSeeder::class,
            website_socialmediaSeeder::class,
            WebsiteorderSeeder::class,
            CourseSeeder::class,
            UnitSeeder::class,
            VideoSeeder::class,
            ExplainvideoSeeder::class,
            ClientSeeder::class,
            OptiontSeeder::class,
            CommentSeeder::class,
            ReplayCommentSeeder::class,
            HomepageSeeder::class,
            OfferSeeder::class
        ]);
    }
}
