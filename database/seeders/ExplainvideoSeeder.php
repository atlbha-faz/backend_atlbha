<?php

namespace Database\Seeders;

use App\Models\ExplainVideos;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExplainvideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       ExplainVideos::create([
        'title'=>'video1',
        'video'=>'video.mp4',
        'thumbnail'=>'thumbail.jpg',
        'duration' => '20:00:00',
        'user_id'=>1
       ]);
       ExplainVideos::create([
         'title'=>'video1',
        'video'=>'video.mp4',
        'duration' => '20:00:00',
        'thumbnail'=>'thumbail.jpg',
        'user_id'=>1
       ]);
       ExplainVideos::create([
       'title'=>'video1',
        'video'=>'video.mp4',
        'duration' => '20:00:00',
        'thumbnail'=>'thumbail.jpg',
        'user_id'=>1
       ]);
    }
}
