<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Unit::create([
        'title'=>'الوحدة الاولى',
        'file' => 'file.pdf',
        'course_id'=>1
       ]);
         Unit::create([
        'title'=>'الوحدة الثانيه',
        'file' => 'file.pdf',
        'course_id'=>1
       ]);
          Unit::create([
        'title'=>'الوحدة الاولى',
        'file' => 'file.pdf',
        'course_id'=>2
       ]);

    }
}
