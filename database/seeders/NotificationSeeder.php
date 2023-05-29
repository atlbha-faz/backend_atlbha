<?php

namespace Database\Seeders;
use Notification;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Notifications\verificationNotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $data=[
            'message' => 'تم تفعيل وسيلة دفع جديدة',
            'store_id' =>1,
            'user_id'=>1,
            'type'=>"store_payment",
            'object_id'=>1
        ];
      $data1=[
        'message' => 'تم تفعيل وسيلة دفع جديدة',
        'store_id' =>1,
            'user_id'=>1,
            'type'=>"store_payment",
            'object_id'=>1
        ];
        $user = User::where('store_id',1)->first();
         Notification::send($user, new verificationNotification($data));
        //  Notification::route('mail', "rawaa.faz.it@gmail.com")->notify(new verificationNotification($data));
        //  Notification::route('mail', "rawaa.faz.it@gmail.com")->notify(new verificationNotification($data1));
         Notification::send($user, new verificationNotification($data1));

         $data2=[
            'message' => 'إستفسار حول دعم السرفر',
            'store_id' =>1,
            'user_id'=>1,
            'type'=>"ask",
            'object_id'=>1
        ];
      $data3=[
        'message' => 'قبول متجر نون',
        'store_id' =>2,
            'user_id'=>1,
            'type'=>"request",
            'object_id'=>1
        ];
        $user = User::where('store_id',null)->where('user_type','admin_employee')->first();
         Notification::send($user, new verificationNotification($data2));
         Notification::send($user, new verificationNotification($data3));
    }
}
