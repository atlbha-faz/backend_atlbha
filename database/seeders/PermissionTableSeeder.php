<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
     Permission::create(['name' => 'show_users_of_admin',
              'name_ar'=>'عرض المستخدمين' ,
              'parent_id'=>null,
            'type'=>'admin']);
            
    Permission::create(['name' => 'show_users_of_store',
            'name_ar'=>'عرض المستخدمين' ,
            'parent_id'=>null,
          'type'=>'store']);
          Permission::create(['name' => 'home_of_admin',
          'name_ar'=>'الرئيسية' ,
          'parent_id'=>null,
        'type'=>'admin']);
        
Permission::create(['name' => 'home_of_store',
        'name_ar'=>'الرئيسية' ,
        'parent_id'=>null,
      'type'=>'store']);
      Permission::create(['name' => 'users_of_admin',
      'name_ar'=>'المستخدمين' ,
      'parent_id'=>null,
    'type'=>'admin']);
    
Permission::create(['name' => 'users_of_store',
    'name_ar'=>'المستخدمين' ,
    'parent_id'=>null,
  'type'=>'store']);
Permission::create(['name' => 'number_users_of_admin',
          'name_ar'=>'عدد المستخدمين' ,
           'parent_id'=> 1,
            'type'=>'admin']);
 Permission::create(['name' => 'number_users_of_store',
            'name_ar'=>'عدد المستخدمين' ,
            'parent_id'=> 2,
          'type'=>'store']);
          Permission::create(['name' => 'phonenumber_of_admin',
          'name_ar'=>'رقم الجوال',
           'parent_id'=> 1,
            'type'=>'admin']);
 Permission::create(['name' => 'phonenumber_of_store',
            'name_ar'=>'رقم الجوال',
            'parent_id'=> 2,
          'type'=>'store']);
Permission::create(['name' => 'email_of_admin',
          'name_ar'=>'البريد الالكتروني' ,
           'parent_id'=> 1,
            'type'=>'admin']);
 Permission::create(['name' => 'email_of_store',
            'name_ar'=>'البريد الالكتروني',
            'parent_id'=> 2,
          'type'=>'store']);   
 Permission::create(['name' => 'summary_of_admin',
          'name_ar'=>'الملخص' ,
           'parent_id'=> 3,
            'type'=>'admin']);
 Permission::create(['name' => 'summary_of_store',
            'name_ar'=>'الملخص',
            'parent_id'=> 4,
          'type'=>'store']); 
          Permission::create(['name' => 'package_of_admin',
          'name_ar'=>'ملخص طلبات الاشتراكات',
           'parent_id'=> 3,
            'type'=>'admin']);
 Permission::create(['name' => 'package_of_store',
            'name_ar'=>'ملخص طلبات الاشتراكات',
            'parent_id'=> 4,
          'type'=>'store']);      
Permission::create(['name' => 'notifaction_of_admin',
          'name_ar'=>'التنبيهات' ,
           'parent_id'=> 3,
            'type'=>'admin']);
 Permission::create(['name' => 'notifaction_of_store',
            'name_ar'=>'التنبيهات',
            'parent_id'=> 4,
          'type'=>'store']);   
Permission::create(['name' => 'index_users_of_admin',
            'name_ar'=>'عرض' ,
            'parent_id'=> 7,
          'type'=>'admin']);
Permission::create(['name' => 'update_users_of_admin',
          'name_ar'=>'تعديل' ,
          'parent_id'=> 7,
        'type'=>'admin']);
 Permission::create(['name' => 'add_users_of_admin',
        'name_ar'=>'اضافة' ,
        'parent_id'=> 7,
      'type'=>'admin']);
 Permission::create(['name' => 'delete_users_of_admin',
      'name_ar'=>'حذف' ,
      'parent_id'=> 7,
    'type'=>'admin']);

 Permission::create(['name' => 'index_users_of_store',
    'name_ar'=>'عرض' ,
    'parent_id'=> 8,
  'type'=>'store']);
Permission::create(['name' => 'update_users_of_store',
  'name_ar'=>'تعديل' ,
  'parent_id'=> 8,
'type'=>'store']);
Permission::create(['name' => 'add_users_of_store',
'name_ar'=>'اضافة' ,
'parent_id'=> 8,
'type'=>'store']);
Permission::create(['name' => 'delete_users_of_store',
'name_ar'=>'حذف' ,
'parent_id'=> 8,
'type'=>'store']);
Permission::create(['name' => 'index_phonenumber_of_admin',
            'name_ar'=>'عرض' ,
            'parent_id'=> 9,
          'type'=>'admin']);
Permission::create(['name' => 'update_phonenumber_of_admin',
          'name_ar'=>'تعديل' ,
          'parent_id'=> 9,
        'type'=>'admin']);
 Permission::create(['name' => 'add_phonenumber_of_admin',
        'name_ar'=>'اضافة' ,
        'parent_id'=> 9,
      'type'=>'admin']);
 Permission::create(['name' => 'delete_phonenumber_of_admin',
      'name_ar'=>'حذف' ,
      'parent_id'=> 9,
    'type'=>'admin']);

 Permission::create(['name' => 'index_phonenumber_of_store',
    'name_ar'=>'عرض' ,
    'parent_id'=> 10,
  'type'=>'store']);
Permission::create(['name' => 'update_phonenumber_of_store',
  'name_ar'=>'تعديل' ,
  'parent_id'=> 10,
'type'=>'store']);
Permission::create(['name' => 'add_phonenumber_of_store',
'name_ar'=>'اضافة' ,
'parent_id'=> 10,
'type'=>'store']);
Permission::create(['name' => 'delete_phonenumber_of_store',
'name_ar'=>'حذف' ,
'parent_id'=> 10,
'type'=>'store']);
            
Permission::create(['name' => 'index_email_of_admin',
            'name_ar'=>'عرض' ,
            'parent_id'=> 11,
          'type'=>'admin']);
Permission::create(['name' => 'update_email_of_admin',
          'name_ar'=>'تعديل' ,
          'parent_id'=> 11,
        'type'=>'admin']);
 Permission::create(['name' => 'add_email_of_admin',
        'name_ar'=>'اضافة' ,
        'parent_id'=> 11,
      'type'=>'admin']);
 Permission::create(['name' => 'delete_email_of_admin',
      'name_ar'=>'حذف' ,
      'parent_id'=> 11,
    'type'=>'admin']);

 Permission::create(['name' => 'index_email_of_store',
    'name_ar'=>'عرض' ,
    'parent_id'=> 12,
  'type'=>'store']);
Permission::create(['name' => 'update_email_of_store',
  'name_ar'=>'تعديل' ,
  'parent_id'=> 12,
'type'=>'store']);
Permission::create(['name' => 'add_email_of_store',
'name_ar'=>'اضافة' ,
'parent_id'=> 12,
'type'=>'store']);
Permission::create(['name' => 'delete_email_of_store',
'name_ar'=>'حذف' ,
'parent_id'=> 12,
'type'=>'store']);


//////
Permission::create(['name' => 'index_summary_of_admin',
            'name_ar'=>'عرض' ,
            'parent_id'=> 13,
          'type'=>'admin']);
Permission::create(['name' => 'update_summary_of_admin',
          'name_ar'=>'تعديل' ,
          'parent_id'=> 13,
        'type'=>'admin']);
 Permission::create(['name' => 'add_summary_of_admin',
        'name_ar'=>'اضافة' ,
        'parent_id'=> 13,
      'type'=>'admin']);
 Permission::create(['name' => 'delete_summary_of_admin',
      'name_ar'=>'حذف' ,
      'parent_id'=> 13,
    'type'=>'admin']);

 Permission::create(['name' => 'index_summary_of_store',
    'name_ar'=>'عرض' ,
    'parent_id'=> 14,
  'type'=>'store']);
Permission::create(['name' => 'update_summary_of_store',
  'name_ar'=>'تعديل' ,
  'parent_id'=> 14,
'type'=>'store']);
Permission::create(['name' => 'add_summary_of_store',
'name_ar'=>'اضافة' ,
'parent_id'=> 14,
'type'=>'store']);
Permission::create(['name' => 'delete_summary_of_store',
'name_ar'=>'حذف' ,
'parent_id'=> 14,
'type'=>'store']);

///////
Permission::create(['name' => 'index_package_of_admin',
'name_ar'=>'عرض' ,
'parent_id'=> 15,
'type'=>'admin']);
Permission::create(['name' => 'update_package_of_admin',
'name_ar'=>'تعديل' ,
'parent_id'=> 15,
'type'=>'admin']);
Permission::create(['name' => 'add_package_of_admin',
'name_ar'=>'اضافة' ,
'parent_id'=> 15,
'type'=>'admin']);
Permission::create(['name' => 'delete_package_of_admin',
'name_ar'=>'حذف' ,
'parent_id'=> 15,
'type'=>'admin']);

Permission::create(['name' => 'index_package_of_store',
'name_ar'=>'عرض' ,
'parent_id'=> 16,
'type'=>'store']);
Permission::create(['name' => 'update_package_of_store',
'name_ar'=>'تعديل' ,
'parent_id'=> 16,
'type'=>'store']);
Permission::create(['name' => 'add_package_of_store',
'name_ar'=>'اضافة' ,
'parent_id'=> 16,
'type'=>'store']);
Permission::create(['name' => 'delete_package_of_store',
'name_ar'=>'حذف' ,
'parent_id'=> 16,
'type'=>'store']);        
 /////
 Permission::create(['name' => 'index_notifaction_of_admin',
            'name_ar'=>'عرض' ,
            'parent_id'=> 17,
          'type'=>'admin']);
Permission::create(['name' => 'update_notifaction_of_admin',
          'name_ar'=>'تعديل' ,
          'parent_id'=> 17,
        'type'=>'admin']);
 Permission::create(['name' => 'add_notifaction_of_admin',
        'name_ar'=>'اضافة' ,
        'parent_id'=> 17,
      'type'=>'admin']);
 Permission::create(['name' => 'delete_notifaction_of_admin',
      'name_ar'=>'حذف' ,
      'parent_id'=> 17,
    'type'=>'admin']);

 Permission::create(['name' => 'index_notifaction_of_store',
    'name_ar'=>'عرض' ,
    'parent_id'=> 18,
  'type'=>'store']);
Permission::create(['name' => 'update_notifaction_of_store',
  'name_ar'=>'تعديل' ,
  'parent_id'=> 18,
'type'=>'store']);
Permission::create(['name' => 'add_notifaction_of_store',
'name_ar'=>'اضافة' ,
'parent_id'=> 18,
'type'=>'store']);
Permission::create(['name' => 'delete_notifaction_of_store',
'name_ar'=>'حذف' ,
'parent_id'=> 18,
'type'=>'store']);         
  //////       
    }
}