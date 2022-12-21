<?php

namespace Database\Factories;
use Faker\Factory as Faker;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
       $faker= Faker::create('ar_JO');

        return [
          'name' => "التاجرالمبتدأ",
          'monthly_price' =>$faker->randomDigit,
          'yearly_price' => $faker->randomDigit,
          'discount' =>$faker->randomDigit,



        ];

    }
}
