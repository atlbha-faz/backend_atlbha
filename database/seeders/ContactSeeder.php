<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Contact::create([
            'subject' => 'معالجة مشكلة الدومين',
            'message' => 'معالجة مشكلة الدومين',
            'store_id' => 1,
            
        ]);
    }
}