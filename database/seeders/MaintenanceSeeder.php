<?php

namespace Database\Seeders;

use App\Models\Maintenance;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Maintenance::create([
            'title' => 'وضع الصيانة',
            'message' => 'هذا الموقع في وضع الصيانة',
            'store_id' => 1,
            
        ]) ;
    }
}