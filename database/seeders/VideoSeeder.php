<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Video::create([
        'video'=>'video.mp4',
        'name'=>'video.mp4',
        'duration'=>3,

        'unit_id'=>1
       ]);
         Video::create([
        'video'=>'video.mp4',
        'name'=>'video.mp4',
        'duration'=>3,
        'unit_id'=>1
       ]);
         Video::create([
        'video'=>'video.mp4',
        'name'=>'video.mp4',
        'duration'=>3,
        'unit_id'=>1
       ]);
         Video::create([
        'video'=>'video.mp4',
        'name'=>'video.mp4',
        'duration'=>3,
        'unit_id'=>2
       ]);
    }
}
