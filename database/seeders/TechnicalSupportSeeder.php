<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TechnicalSupport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TechnicalSupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        TechnicalSupport::create([
            'title' => 'خدمات السيرفر',
            'phonenumber' => '0096650775433',
            'content' => 'ا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد
            النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة
            .عدد الحروف التى يولدها التطبيق',
            'supportstatus' => 'not_finished',
            'type' => 'suggestion',
            'store_id'=>1

        ]);
    }
}
