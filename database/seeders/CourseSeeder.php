<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::create([
            'name'=>'الكورس الاول',
            'description' =>'الكورس الاول',
            'duration' =>'04:00:00',
            'tags' =>'كورس تسويقي , متجر ',
            'user_id'=>1
        ]);
         Course::create([
            'name'=>'الكورس الثاني',
            'description' =>'الكورس الثاني',
            'duration' =>'05:00:00',
            'tags' =>'كورس تسويقي , متجر ',
            'user_id'=>1
        ]);
         Course::create([
            'name'=>'الكورس الثالث',
            'description' =>'الكورس الثالث',
            'duration' =>'09:00:00',
            'tags' =>'كورس تسويقي , متجر ',
            'user_id'=>1
        ]);

    }
}
