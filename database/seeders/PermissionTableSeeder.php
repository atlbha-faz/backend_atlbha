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

     Permission::create(['name' => 'homepage',
              'name_ar'=>'الرئيسية' ,
              'parent_id'=>null,
            'type'=>'store']);

    Permission::create(['name' => 'show',
            'name_ar'=>'عرض' ,
            'parent_id'=>1,
          'type'=>'store']);
        
        //////////////////////////////////////////////////////////////// 3
        
          Permission::create(['name' => 'categories',
          'name_ar'=>'التصنيفات' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>3,
      'type'=>'store']);
      Permission::create(['name' => 'add',
      'name_ar'=>'إضافة' ,
      'parent_id'=>3,
    'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>3,
  'type'=>'store']);
Permission::create(['name' => 'delete',
          'name_ar'=>'حذف' ,
           'parent_id'=> 3,
            'type'=>'store']);
 Permission::create(['name' => 'activate',
            'name_ar'=>'تفعيل / تعطيل' ,
            'parent_id'=> 3,
          'type'=>'store']);
          
         //////////////////////////////////////////////////////////////// 9
        
          Permission::create(['name' => 'products',
          'name_ar'=>'المنتجات' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>9,
      'type'=>'store']);
      Permission::create(['name' => 'add',
      'name_ar'=>'إضافة' ,
      'parent_id'=>9,
    'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>9,
  'type'=>'store']);
Permission::create(['name' => 'delete',
          'name_ar'=>'حذف' ,
           'parent_id'=> 9,
            'type'=>'store']);
 Permission::create(['name' => 'activate',
            'name_ar'=>'تفعيل / تعطيل' ,
            'parent_id'=> 9,
          'type'=>'store']);
          
         //////////////////////////////////////////////////////////////// 15
        
          Permission::create(['name' => 'orders',
          'name_ar'=>'الطلبات' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>15,
      'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>15,
  'type'=>'store']);
Permission::create(['name' => 'delete',
          'name_ar'=>'حذف' ,
           'parent_id'=> 15,
            'type'=>'store']);
        
         //////////////////////////////////////////////////////////////// 19
        
          Permission::create(['name' => 'copons',
          'name_ar'=>'الكوبونات' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>19,
      'type'=>'store']);
      Permission::create(['name' => 'add',
      'name_ar'=>'إضافة' ,
      'parent_id'=>19,
    'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>19,
  'type'=>'store']);
Permission::create(['name' => 'delete',
          'name_ar'=>'حذف' ,
           'parent_id'=> 19,
            'type'=>'store']);
 Permission::create(['name' => 'activate',
            'name_ar'=>'تفعيل / تعطيل' ,
            'parent_id'=> 19,
          'type'=>'store']);
           //////////////////////////////////////////////////////////////// 25
        
          Permission::create(['name' => 'offers',
          'name_ar'=>'العروض الخاصة' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>25,
      'type'=>'store']);
      Permission::create(['name' => 'add',
      'name_ar'=>'إضافة' ,
      'parent_id'=>25,
    'type'=>'store']);

 Permission::create(['name' => 'activate',
            'name_ar'=>'تفعيل / تعطيل' ,
            'parent_id'=> 25,
          'type'=>'store']);
           //////////////////////////////////////////////////////////////// 29
        
          Permission::create(['name' => 'abandoned-carts',
          'name_ar'=>'السلات المتروكة' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>29,
      'type'=>'store']);
Permission::create(['name' => 'delete',
          'name_ar'=>'حذف' ,
           'parent_id'=> 29,
            'type'=>'store']);
           //////////////////////////////////////////////////////////////// 32
        
          Permission::create(['name' => 'keywords',
          'name_ar'=>'الكلمات المفتاحية' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>32,
      'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>32,
  'type'=>'store']);
           //////////////////////////////////////////////////////////////// 35
        
          Permission::create(['name' => 'celebrity-marketings',
          'name_ar'=>'التسويق عبر المشاهير' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>35,
      'type'=>'store']);
           //////////////////////////////////////////////////////////////// 37
        
          Permission::create(['name' => 'ratings',
          'name_ar'=>'التقييمات' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>37,
      'type'=>'store']);
      Permission::create(['name' => 'add',
      'name_ar'=>'إضافة' ,
      'parent_id'=>37,
    'type'=>'store']);

Permission::create(['name' => 'delete',
          'name_ar'=>'حذف' ,
           'parent_id'=> 37,
            'type'=>'store']);
 Permission::create(['name' => 'activate',
            'name_ar'=>'تفعيل / تعطيل' ,
            'parent_id'=> 37,
          'type'=>'store']);
           //////////////////////////////////////////////////////////////// 42
        
          Permission::create(['name' => 'pages',
          'name_ar'=>'الصفحات' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>42,
      'type'=>'store']);
      Permission::create(['name' => 'add',
      'name_ar'=>'إضافة' ,
      'parent_id'=>42,
    'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>42,
  'type'=>'store']);
Permission::create(['name' => 'delete',
          'name_ar'=>'حذف' ,
           'parent_id'=> 42,
            'type'=>'store']);
 Permission::create(['name' => 'activate',
            'name_ar'=>'تفعيل / تعطيل' ,
            'parent_id'=> 42,
          'type'=>'store']);
           //////////////////////////////////////////////////////////////// 48
        
          Permission::create(['name' => 'academy',
          'name_ar'=>'الأكاديمية' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>48,
      'type'=>'store']);
     
           //////////////////////////////////////////////////////////////// 50
        
          Permission::create(['name' => 'template',
          'name_ar'=>'القالب' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>50,
      'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>50,
  'type'=>'store']);
           //////////////////////////////////////////////////////////////// 53
        
          Permission::create(['name' => 'documentation',
          'name_ar'=>'توثيق المتجر' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>53,
      'type'=>'store']);
      Permission::create(['name' => 'add',
      'name_ar'=>'إضافة' ,
      'parent_id'=>53,
    'type'=>'store']);

           //////////////////////////////////////////////////////////////// 56
        
          Permission::create(['name' => 'socialmedia',
          'name_ar'=>'صفحات التواصل' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>56,
      'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>56,
  'type'=>'store']);

           //////////////////////////////////////////////////////////////// 59
        
          Permission::create(['name' => 'package-upgrade',
          'name_ar'=>'ترقية الباقة' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>59,
      'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>59,
  'type'=>'store']);
           //////////////////////////////////////////////////////////////// 62
        
          Permission::create(['name' => 'technical-support',
          'name_ar'=>'الدعم الفني' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>62,
      'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>62,
  'type'=>'store']);
Permission::create(['name' => 'delete',
          'name_ar'=>'حذف' ,
           'parent_id'=> 62,
            'type'=>'store']);
           //////////////////////////////////////////////////////////////// 66
        
          Permission::create(['name' => 'shipping-companies',
          'name_ar'=>'شركات الشحن' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>66,
      'type'=>'store']);
        
 Permission::create(['name' => 'activate',
            'name_ar'=>'تفعيل / تعطيل' ,
            'parent_id'=> 66,
          'type'=>'store']);
           //////////////////////////////////////////////////////////////// 69
        
          Permission::create(['name' => 'payments-gateways',
          'name_ar'=>'بوابات الدفع' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>69,
      'type'=>'store']);
        
Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>69,
  'type'=>'store']);
        
 Permission::create(['name' => 'activate',
            'name_ar'=>'تفعيل / تعطيل' ,
            'parent_id'=> 69,
          'type'=>'store']);
           //////////////////////////////////////////////////////////////// 73
        
          Permission::create(['name' => 'basic-data',
          'name_ar'=>'البيانات الأساسية' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>73,
      'type'=>'store']);
        
Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>73,
  'type'=>'store']);
           //////////////////////////////////////////////////////////////// 76
        
          Permission::create(['name' => 'maintenance-mode',
          'name_ar'=>'وضع الصيانة' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>76,
      'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>76,
  'type'=>'store']);
           //////////////////////////////////////////////////////////////// 79
        
          Permission::create(['name' => 'users',
          'name_ar'=>'المستخدمين' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>79,
      'type'=>'store']);
      Permission::create(['name' => 'add',
      'name_ar'=>'إضافة' ,
      'parent_id'=>79,
    'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>79,
  'type'=>'store']);
Permission::create(['name' => 'delete',
          'name_ar'=>'حذف' ,
           'parent_id'=> 79,
            'type'=>'store']);
 Permission::create(['name' => 'activate',
            'name_ar'=>'تفعيل / تعطيل' ,
            'parent_id'=> 79,
          'type'=>'store']);
           //////////////////////////////////////////////////////////////// 85
        
          Permission::create(['name' => 'roles',
          'name_ar'=>'الأدوار' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>85,
      'type'=>'store']);
      Permission::create(['name' => 'add',
      'name_ar'=>'إضافة' ,
      'parent_id'=>85,
    'type'=>'store']);

Permission::create(['name' => 'update',
    'name_ar'=>'تعديل' ,
    'parent_id'=>85,
  'type'=>'store']);
Permission::create(['name' => 'delete',
          'name_ar'=>'حذف' ,
           'parent_id'=> 85,
            'type'=>'store']);
           //////////////////////////////////////////////////////////////// 90
        
          Permission::create(['name' => 'notifications',
          'name_ar'=>'الاشعارات' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>90,
      'type'=>'store']);
Permission::create(['name' => 'delete',
          'name_ar'=>'حذف' ,
           'parent_id'=> 90,
            'type'=>'store']);
           //////////////////////////////////////////////////////////////// 93
        
          Permission::create(['name' => 'customers',
          'name_ar'=>'العملاء' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>93,
      'type'=>'store']);
      Permission::create(['name' => 'add',
      'name_ar'=>'إضافة' ,
      'parent_id'=>93,
    'type'=>'store']);

Permission::create(['name' => 'delete',
          'name_ar'=>'حذف' ,
           'parent_id'=> 93,
            'type'=>'store']);
           //////////////////////////////////////////////////////////////// 97
        
          Permission::create(['name' => 'platform-services',
          'name_ar'=>'خدمات المنصة' ,
          'parent_id'=>null,
        'type'=>'store']);

      Permission::create(['name' => 'add',
      'name_ar'=>'إضافة' ,
      'parent_id'=>97,
    'type'=>'store']);

           //////////////////////////////////////////////////////////////// 99
        
          Permission::create(['name' => 'reports',
          'name_ar'=>'التقارير' ,
          'parent_id'=>null,
        'type'=>'store']);

Permission::create(['name' => 'show',
        'name_ar'=>'عرض' ,
        'parent_id'=>99,
      'type'=>'store']);
         
        
    
      
        
    }
}
