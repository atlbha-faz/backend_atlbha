<?php

namespace Database\Seeders;

use App\Models\Websiteorder;
use App\Models\Service_Websiteorder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WebsiteorderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Websiteorder::create([
            'order_number' => '0001',
            'type' => 'service',
            'status' =>'accept',
            'store_id' => 1,
        ]);
   
        Service_Websiteorder::create([
            'service_id' =>1,
            'websiteorder_id'=>1,
            'status'=>'accept',

        ]);
        Service_Websiteorder::create([
            'service_id' =>2,
            'websiteorder_id'=>1,
            'status'=>'accept',
          
        ]);
    }
}
