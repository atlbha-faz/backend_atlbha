<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          Comment::create([
            'comment_text' => 'منتج جيد',
            'rateing' => 4,
            'user_id' => 4,
            'product_id'=>1
        ]);
    }
}
