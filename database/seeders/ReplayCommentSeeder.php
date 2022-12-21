<?php

namespace Database\Seeders;

use App\Models\Replaycomment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReplayCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Replaycomment::create([
            'comment_text' => 'شكرا',
            'user_id' => 1,
            'comment_id'=>1
        ]);
    }
}
