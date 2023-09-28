<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\DaySeeder;
use Database\Seeders\NotificationSeeder;
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
            RegionSeeder::class,
            CitySeeder::class,
            // PermissionTableSeeder::class,
            CreateUserSeeder::class,
            PackageSeeder::class,
            TemplateSeeder::class,
            PlanSeeder::class,
            PackageTemplateSeeder::class,
            PackagePlanSeeder::class,
            AdminSeeder::class,
            StoreSeeder::class,
            StoreUserSeeder::class,
            CategorySeeder::class,
            CurrencySeeder::class,
            ProductSeeder::class,
            ServiceSeeder::class,
            PageCategorySeeder::class,
            PostCategorySeeder::class,
            ContactSeeder::class,
            CouponSeeder::class,
            MaintenanceSeeder::class,
            MarketerSeeder::class,

            PaymenttypeSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            PlatformSeeder::class,
            ReplaycontactSeeder::class,
            // SeoSeeder::class,
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
            OfferSeeder::class,
            SectionSeeder::class,
            PartnerSeeder::class,
            CartSeeder::class,
            CartDetailSeeder::class,

            NotificationSeeder::class,

            TechnicalSupportSeeder::class,
            PermissionTableSeeder::class,

            NewSeeder::class,
            DaySeeder::class,
            Theme::class,
        ]);

    }
}
