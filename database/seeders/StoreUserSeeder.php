<?php

namespace Database\Seeders;

use App\Models\User;

use App\Models\Store;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StoreUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
   $user= User::create([
        'user_id'=>4,
        'name' => 'رقيه',
        'user_name' =>'roqia',
        'email' =>'roqia@gmail.com',
        'password' =>'12345678',
        'gender' =>'female',
        'phonenumber' => '0096650775433',
        'image'=>'image.jpg',
        'user_type'=>'store',
        'country_id' => 1,
        'city_id'=> 1,
        'verified'=>1,
        'store_id'=>1,
      ]);

        $userid =$user->id;
      Store::where('id', 1)->update(['user_id'=> $userid]);
 $user1=User::create([
        'user_id'=>3,
        'name' => 'روعه',
        'user_name' =>'rawaa',
        'email' =>'rawaa@gmail.com',
        'password' =>'12345678',
        'gender' =>'female',
        'phonenumber' => '0096650785433',
        'image'=>'image.jpg',
        'user_type'=>'store',
        'country_id' => 1,
        'city_id'=> 2,
        'verified'=>1,
        'store_id'=>2,
      ]);
         $userid1 =$user1->id;
      Store::where('id', 2)->update(['user_id'=> $userid1]);
      $user2= User::create([
        'user_id'=>5,
        'name' => 'احمد',
        'user_name' =>'ahmed',
        'email' =>'ahmed@gmail.com',
        'password' =>'12345678',
        'gender' =>'male',
        'phonenumber' => '0096650775433',
        'image'=>'image.jpg',
        'user_type'=>'customer',
        'country_id' => 1,
        'city_id'=> 3,
        'verified'=>1,
         'store_id'=>1,
      ]);
    //         $userid2 =$user2->id;
    //   Store::where('id', 1)->update(['user_id'=> $userid2]);
    $user2= User::create([
      'user_id'=>6,
      'name' => 'محمد',
      'user_name' =>'mohammed',
      'email' =>'mohammed@gmail.com',
      'password' =>'12345678',
      'gender' =>'male',
      'phonenumber' => '0096650975433',
      'image'=>'image.jpg',
      'user_type'=>'customer',
      'country_id' => 1,
      'city_id'=> 3,
      'verified'=>1,
       'store_id'=>2,
    ]);

        for($i=4 ; $i<45 ; $i++){
 $user1=User::create([
        'user_id'=>$i+4,
        'name' => 'روعه',
        'user_name' =>'rawaa',
        'email' =>'rawaa'.$i.'@gmail.com',
        'password' =>'12345678',
        'gender' =>'female',
        'phonenumber' => '009665037543'.$i.'',
        'image'=>'image.jpg',
        'user_type'=>'store',
        'country_id' => 1,
        'city_id'=> 2,
        'verified'=>1,
        'store_id'=>2,
      ]);
         $userid1 =$user1->id;
      Store::where('id', $i)->update(['user_id'=> $userid1]);

        }

}

}
