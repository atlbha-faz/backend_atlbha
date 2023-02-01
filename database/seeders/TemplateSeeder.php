<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Template::create([
            'name' => 'القالب الاول',
        ]);
        Template::create([
            'name' => 'القالب الثاني',
        ]);
        Template::create([
            'name' => 'القالب الثالث',
        ]);
    }
}
