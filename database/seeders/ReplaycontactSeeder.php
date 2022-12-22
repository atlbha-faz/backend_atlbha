<?php

namespace Database\Seeders;

use App\Models\Replaycontact;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReplaycontactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Replaycontact::create([
            'subject' => 'الرد',
            'message' => 'صديقنا التاجر، باقي 20يوم على انتهاء اشتراكك
            تواصل مع الدعم الفني للحصول على كود خصم لتجديد اشتراكك',
            'contact_id' => 1,
            
        ]);
    }
}