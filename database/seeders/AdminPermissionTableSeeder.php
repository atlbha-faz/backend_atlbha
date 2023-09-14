<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     
        Permission::create(['name' => 'admin.mainpage',
        'action_type' => 'admin.mainpage',
'name_ar'=>'الرئيسية' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.mainpage.index',
        'action_type' => 'index',
'name_ar'=>'عرض' ,
'parent_id'=>1,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 3

Permission::create(['name' => 'admin.etlobha',
        'action_type' => 'admin.etlobha',
     
'name_ar'=>'سوق اطلبها' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.etlobha.index',
        'action_type' => 'index',
     
'name_ar'=>'عرض' ,
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.etlobha.store',
        'action_type' => 'store',
     
'name_ar'=>'إضافة' ,
'parent_id'=>3,
'type'=>'admin']);

Permission::create(['name' => 'admin.etlobha.update',
        'action_type' => 'update',
     
'name_ar'=>'تعديل' ,
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.etlobha.show',
        'action_type' => 'show',
     
'name_ar'=>'عرض' ,
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.etlobha.deleteall',
        'action_type' => 'deleteall',
'name_ar'=>'حذف' ,
'parent_id'=> 3,
'type'=>'admin']);
Permission::create(['name' => 'admin.etlobha.changeStatusall',
        'action_type' => 'changeStatusall',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 3,
'type'=>'admin']);
Permission::create(['name' => 'admin.etlobha.specialStatus',
        'action_type' => 'specialStatus',
'name_ar'=>'مميز / غير مميز' ,
'parent_id'=> 3,
'type'=>'admin']);
Permission::create(['name' => 'admin.etlobha.statistics',
        'action_type' => 'statistics',
'name_ar'=>'احصائيات المنتج' ,
'parent_id'=> 3,
'type'=>'admin']);


//////////////////////////////////////////////////////////////// 9


Permission::create(['name' => 'admin.stock',
        'action_type' => 'admin.stock',
     
'name_ar'=>'المخزون ' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.stock.index',
        'action_type' => 'index',
     
'name_ar'=>'عرض' ,
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.stock.store',
        'action_type' => 'store',
     
'name_ar'=>'إضافة' ,
'parent_id'=>3,
'type'=>'admin']);

Permission::create(['name' => 'admin.stock.update',
        'action_type' => 'update',
     
'name_ar'=>'تعديل' ,
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.stock.addToStore',
        'action_type' => 'addToStore',
     
'name_ar'=>'إضافة الى السوق' ,
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.stock.deleteall',
        'action_type' => 'deleteall',
'name_ar'=>'حذف' ,
'parent_id'=> 3,
'type'=>'admin']);
Permission::create(['name' => 'admin.stock.importStockProducts',
        'action_type' => 'importStockProducts',
'name_ar'=>'تصدير ملف' ,
'parent_id'=> 3,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 9

Permission::create(['name' => 'admin.platform',
        'action_type' => 'admin.platform',
     
'name_ar'=>'السوق العام' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.platform.index',
        'action_type' => 'index',
     
'name_ar'=>'عرض' ,
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.platform.store',
        'action_type' => 'store',
     
'name_ar'=>'إضافة' ,
'parent_id'=>3,
'type'=>'admin']);

Permission::create(['name' => 'admin.platform.update',
        'action_type' => 'update',
     
'name_ar'=>'تعديل' ,
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.platform.destroy',
        'action_type' => 'destroy',
     
'name_ar'=>'حذف ',
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.platform.changePlatformStatus',
        'action_type' => 'changePlatformStatus',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 3,
'type'=>'admin']);
Permission::create(['name' => 'admin.platform.show',
        'action_type' => 'show',
'name_ar'=>'عرض',
'parent_id'=> 3,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 9




Permission::create(['name' => 'admin.activity',
        'action_type' => 'admin.activity',
     
'name_ar'=>'نشاط المتاجر' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.activity.index',
        'action_type' => 'index',
     
'name_ar'=>'عرض' ,
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.activity.store',
        'action_type' => 'store',
     
'name_ar'=>'إضافة' ,
'parent_id'=>3,
'type'=>'admin']);

Permission::create(['name' => 'admin.activity.update',
        'action_type' => 'update',
     
'name_ar'=>'تعديل' ,
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.activity.destroy',
        'action_type' => 'destroy',
     
'name_ar'=>'حذف ',
'parent_id'=>3,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 9

Permission::create(['name' => 'admin.store',
        'action_type' => 'admin.store', 
'name_ar'=>'المتاجر' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.store.index',
        'action_type' => 'index',  
'name_ar'=>'عرض' ,
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.store.store',
        'action_type' => 'store',
     
'name_ar'=>'إضافة' ,
'parent_id'=>3,
'type'=>'admin']);

// Permission::create(['name' => 'admin.store.update',
//         'action_type' => 'update',
     
// 'name_ar'=>'تعديل' ,
// 'parent_id'=>3,
// 'type'=>'admin']);
Permission::create(['name' => 'admin.store.destroy',
        'action_type' => 'destroy',
     
'name_ar'=>'حذف ',
'parent_id'=>3,
'type'=>'admin']);
Permission::create(['name' => 'admin.store.changeSatusall',
        'action_type' => 'changeSatusall',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 3,
'type'=>'admin']);
Permission::create(['name' => 'admin.store.specialStatus',
        'action_type' => 'specialStatus',
'name_ar'=>'مميز / غير مميز' ,
'parent_id'=> 3,
'type'=>'admin']);
Permission::create(['name' => 'admin.store.show',
        'action_type' => 'show',
'name_ar'=>'عرض',
'parent_id'=> 3,
'type'=>'admin']);



//////////////////////////////////////////////////////////////// 9
Permission::create(['name' => 'admin.product',
        'action_type' => 'product',
'name_ar'=>'المنتجات' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.product.index',
        'action_type' => 'index',
'name_ar'=>'عرض' ,
'parent_id'=>9,
'type'=>'admin']);

Permission::create(['name' => 'admin.product.show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>9,
'type'=>'admin']);
Permission::create(['name' => 'admin.product.deleteall',
        'action_type' => 'deleteall',
'name_ar'=>'حذف' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.product.changeSatusall',
        'action_type' => 'changeSatusall',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.product.addNote',
        'action_type' => 'addNote',
'name_ar'=>'  إضافة ملاحظة ' ,
'parent_id'=> 9,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 15
Permission::create(['name' => 'admin.verification',
        'action_type' => 'verification',
'name_ar'=>'التوثيق' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.verification.index',
        'action_type' => 'index',
'name_ar'=>'  عرض التوثيق'   ,
'parent_id'=>9,
'type'=>'admin']);

Permission::create(['name' => 'admin.verification.show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>9,
'type'=>'admin']);
Permission::create(['name' => 'admin.verification.deleteall',
        'action_type' => 'deleteall',
'name_ar'=>'حذف' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.verification.changeSatusall',
        'action_type' => 'changeSatusall',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.verification.addNote',
        'action_type' => 'addNote',
'name_ar'=>'  إضافة ملاحظة ' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.verification.acceptVerification',
        'action_type' => 'acceptVerification',
'name_ar'=>'قبول التوثيق' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.verification.rejectVerification',
        'action_type' => 'rejectVerification',
'name_ar'=>' رفض التوثيق' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.verification.verification_update',
        'action_type' => 'verification_update',
'name_ar'=>'  تعديل التوثيق' ,
'parent_id'=> 9,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 9
Permission::create(['name' => 'admin.package',
        'action_type' => 'package',
'name_ar'=>'الباقات' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.package.index',
        'action_type' => 'index',
'name_ar'=>' عرض الباقات' ,
'parent_id'=>9,
'type'=>'admin']);
Permission::create(['name' => 'admin.package.store',
        'action_type' => 'store',
'name_ar'=>'  إضافة  ' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.package.update',
        'action_type' => 'update',
'name_ar'=>'  تعديل  ' ,
'parent_id'=> 9,
'type'=>'admin']);

Permission::create(['name' => 'admin.package.show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>9,
'type'=>'admin']);

Permission::create(['name' => 'admin.package.changeSatusall',
        'action_type' => 'changeSatusall',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 9,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 9
Permission::create(['name' => 'admin.subscriptions',
        'action_type' => 'subscriptions',
'name_ar'=>'الاشتراكات الحالية' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.subscriptions.index',
        'action_type' => 'index',
'name_ar'=>'عرض الاشتراكات الحالية ' ,
'parent_id'=>9,
'type'=>'admin']);



Permission::create(['name' => 'admin.subscriptions.changeSatusall',
        'action_type' => 'changeSatusall',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 9,
'type'=>'admin']);

Permission::create(['name' => 'admin.subscriptions.deleteall',
        'action_type' => 'deleteall',
'name_ar'=>'حذف',
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.subscriptions.addNote',
        'action_type' => 'addNote',
'name_ar'=>'  إضافة تنبية ' ,
'parent_id'=> 9,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 9
Permission::create(['name' => 'admin.service',
        'action_type' => 'service',
'name_ar'=>'الخدمات' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.service.index',
        'action_type' => 'index',
'name_ar'=>'عرض الخدمات ' ,
'parent_id'=>9,
'type'=>'admin']);


Permission::create(['name' => 'admin.service.deleteall',
        'action_type' => 'deleteall',
'name_ar'=>'حذف',
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.service.showdetail',
        'action_type' => 'showdetail',
'name_ar'=>'  عرض التفاصيل ' ,
'parent_id'=> 9,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 9
Permission::create(['name' => 'admin.course',
        'action_type' => 'course',
'name_ar'=>'أكاديمية أطلبها' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.course.index',
        'action_type' => 'index',
'name_ar'=>' عرض الدورات التدريبية' ,
'parent_id'=>9,
'type'=>'admin']);
Permission::create(['name' => 'admin.course.store',
        'action_type' => 'store',
'name_ar'=>'  إضافة  ' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.course.update',
        'action_type' => 'update',
'name_ar'=>'  تعديل  ' ,
'parent_id'=> 9,
'type'=>'admin']);

Permission::create(['name' => 'admin.course.show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>9,
'type'=>'admin']);

Permission::create(['name' => 'admin.course.destroy',
        'action_type' => 'destroy',
'name_ar'=>'حذف' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.course.addvideo',
        'action_type' => 'addvideo',
'name_ar'=>'إضافة فيديو' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.course.deletevideo',
        'action_type' => 'deletevideo',
'name_ar'=>' حذف فيديو' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.course.deleteunit',
        'action_type' => 'deleteunit',
'name_ar'=>'حذف وحدة' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.explainvideo.index',
        'action_type' => 'index',
'name_ar'=>' عرض الشروحات' ,
'parent_id'=>9,
'type'=>'admin']);
Permission::create(['name' => 'admin.explainvideo.store',
        'action_type' => 'store',
'name_ar'=>'  إضافة الشرح ' ,
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.explainvideo.update',
        'action_type' => 'update',
'name_ar'=>'  تعديل الشرح ' ,
'parent_id'=> 9,
'type'=>'admin']);

Permission::create(['name' => 'admin.explainvideo.show',
        'action_type' => 'show',
'name_ar'=>'عرض الشرح' ,
'parent_id'=>9,
'type'=>'admin']);

Permission::create(['name' => 'admin.explainvideo.destroy',
        'action_type' => 'destroy',
'name_ar'=>'حذف الشرح' ,
'parent_id'=> 9,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 50

Permission::create(['name' => 'admin.homepage',
        'action_type' => 'homepage',
'name_ar'=>'القالب' ,
'parent_id'=>null,
'type'=>'admin']);


Permission::create(['name' => 'admin.homepage.index',
        'action_type' => 'index',
'name_ar'=>'عرض القالب ' ,
'parent_id'=>50,
'type'=>'admin']);
Permission::create(['name' => 'admin.homepage.logoUpdate',
        'action_type' => 'logoUpdate',
'name_ar'=>'تعديل الشعار' ,
'parent_id'=>50,
'type'=>'admin']);

Permission::create(['name' => 'admin.homepage.banarUpdate',
        'action_type' => 'banarUpdate',
'name_ar'=>'تعديل البانرات' ,
'parent_id'=>50,
'type'=>'admin']);

Permission::create(['name' => 'admin.homepage.sliderUpdate',
        'action_type' => 'sliderUpdate',
'name_ar'=>'تعديل السالايدرات' ,
'parent_id'=>50,
'type'=>'admin']);
Permission::create(['name' => 'admin.section.index',
        'action_type' => 'index',
'name_ar'=>'عرض الاقسام' ,
'parent_id'=>50,
'type'=>'admin']);
Permission::create(['name' => 'admin.section.sectionupdate',
        'action_type' => 'sectionupdate',
'name_ar'=>'تعديل الاقسام' ,
'parent_id'=>50,
'type'=>'admin']);
Permission::create(['name' => 'admin.section.changestatus',
        'action_type' => 'changestatus',
'name_ar'=>'تغيير حالة الاقسام' ,
'parent_id'=>50,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 42

Permission::create(['name' => 'admin.page',
        'action_type' => 'page',
'name_ar'=>'الصفحات' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.page.show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>42,
'type'=>'admin']);
Permission::create(['name' => 'admin.page.store',
        'action_type' => 'store',
'name_ar'=>'إضافة' ,
'parent_id'=>42,
'type'=>'admin']);
Permission::create(['name' => 'admin.page.publish',
        'action_type' => 'publish',
'name_ar'=>'نشر' ,
'parent_id'=>42,
'type'=>'admin']);

Permission::create(['name' => 'admin.page.update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>42,
'type'=>'admin']);
Permission::create(['name' => 'admin.page.deleteall',
        'action_type' => 'deleteall',
'name_ar'=>'حذف',
'parent_id'=> 9,
'type'=>'admin']);
Permission::create(['name' => 'admin.page.changesatusall',
        'action_type' => 'changesatusall',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 42,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 79

Permission::create(['name' => 'admin.user',
        'action_type' => 'user',
'name_ar'=>'المستخدمين' ,
'parent_id'=>null,
'type'=>'admin']);
Permission::create(['name' => 'admin.user.index',
        'action_type' => 'index',
'name_ar'=>'عرض المستخدمين' ,
'parent_id'=>79,
'type'=>'admin']);
Permission::create(['name' => 'admin.user.show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>79,
'type'=>'admin']);
Permission::create(['name' => 'admin.user.store',
        'action_type' => 'store',
'name_ar'=>'إضافة' ,
'parent_id'=>79,
'type'=>'admin']);

Permission::create(['name' => 'admin.user.update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>79,
'type'=>'admin']);
Permission::create(['name' => 'admin.user.deleteall',
        'action_type' => 'deleteall',
'name_ar'=>'حذف' ,
'parent_id'=> 79,
'type'=>'admin']);
Permission::create(['name' => 'admin.user.changesatusall',
        'action_type' => 'changesatusall',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 79,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 79

Permission::create(['name' => 'admin.role',
        'action_type' => 'role',
'name_ar'=>'الادوار' ,
'parent_id'=>null,
'type'=>'admin']);
Permission::create(['name' => 'admin.role.index',
        'action_type' => 'index',
'name_ar'=>'عرض الادوار' ,
'parent_id'=>79,
'type'=>'admin']);
Permission::create(['name' => 'admin.role.show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>79,
'type'=>'admin']);
Permission::create(['name' => 'admin.role.store',
        'action_type' => 'store',
'name_ar'=>'إضافة' ,
'parent_id'=>79,
'type'=>'admin']);

Permission::create(['name' => 'admin.role.update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>79,
'type'=>'admin']);
Permission::create(['name' => 'admin.role.destroy',
        'action_type' => 'destroy',
'name_ar'=>'حذف' ,
'parent_id'=>79,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 19

Permission::create(['name' => 'admin.coupon',
        'action_type' => 'coupon',
'name_ar'=>'الكوبونات' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.coupon.index',
        'action_type' => 'index',
'name_ar'=>'عرض الكوبونات' ,
'parent_id'=>19,
'type'=>'admin']);
Permission::create(['name' => 'admin.coupon.show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>19,
'type'=>'admin']);
Permission::create(['name' => 'admin.coupon.store',
        'action_type' => 'store',
'name_ar'=>'إضافة' ,
'parent_id'=>19,
'type'=>'admin']);

Permission::create(['name' => 'admin.coupon.update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>19,
'type'=>'admin']);
Permission::create(['name' => 'admin.coupon.deleteall',
        'action_type' => 'deleteall',
'name_ar'=>'حذف' ,
'parent_id'=> 19,
'type'=>'admin']);
Permission::create(['name' => 'admin.coupon.changesatusall',
        'action_type' => 'changesatusall',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 19,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 19

Permission::create(['name' => 'admin.marketer',
        'action_type' => 'marketer',
'name_ar'=>'المندوبين' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.marketer.index',
        'action_type' => 'index',
'name_ar'=>'عرض المندوبين' ,
'parent_id'=>19,
'type'=>'admin']);
Permission::create(['name' => 'admin.marketer.show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>19,
'type'=>'admin']);
Permission::create(['name' => 'admin.marketer.store',
        'action_type' => 'store',
'name_ar'=>'إضافة' ,
'parent_id'=>19,
'type'=>'admin']);

Permission::create(['name' => 'admin.marketer.update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>19,
'type'=>'admin']);
Permission::create(['name' => 'admin.marketer.deleteall',
        'action_type' => 'deleteall',
'name_ar'=>'حذف' ,
'parent_id'=> 19,
'type'=>'admin']);
Permission::create(['name' => 'admin.marketer.changesatusall',
        'action_type' => 'changesatusall',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 19,
'type'=>'admin']);
Permission::create(['name' => 'admin.marketer.registration_marketer_show',
        'action_type' => 'registration_marketer_show',
'name_ar'=>'عرض حالة تسجيل المندوبين' ,
'parent_id'=> 19,
'type'=>'admin']);
Permission::create(['name' => 'admin.marketer.registrationmarketer',
        'action_type' => 'registrationmarketer',
'name_ar'=>'تعديل حالة التسجيل' ,
'parent_id'=> 19,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 19

Permission::create(['name' => 'admin.category',
        'action_type' => 'category',
'name_ar'=>'تصنيفات السوق' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.category.index',
        'action_type' => 'index',
'name_ar'=>'   عرض تصنيفات السوق' ,
'parent_id'=>19,
'type'=>'admin']);
Permission::create(['name' => 'admin.category.show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>19,
'type'=>'admin']);
Permission::create(['name' => 'admin.category.store',
        'action_type' => 'store',
'name_ar'=>'إضافة' ,
'parent_id'=>19,
'type'=>'admin']);

Permission::create(['name' => 'admin.category.update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>19,
'type'=>'admin']);
Permission::create(['name' => 'admin.category.deleteall',
        'action_type' => 'deleteall',
'name_ar'=>'حذف' ,
'parent_id'=> 19,
'type'=>'admin']);
Permission::create(['name' => 'admin.category.changesatusall',
        'action_type' => 'changesatusall',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 19,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 19

Permission::create(['name' => 'admin.storecategory',
        'action_type' => 'storecategory',
'name_ar'=>'تصنيفات المتاجر' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'admin.storecategory.index',
        'action_type' => 'index',
'name_ar'=>'   عرض تصنيفات المتاجر' ,
'parent_id'=>19,
'type'=>'admin']);
Permission::create(['name' => 'admin.storecategory.show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>19,
'type'=>'admin']);
Permission::create(['name' => 'admin.storecategory.store',
        'action_type' => 'store',
'name_ar'=>'إضافة' ,
'parent_id'=>19,
'type'=>'admin']);

Permission::create(['name' => 'admin.storecategory.update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>19,
'type'=>'admin']);
Permission::create(['name' => 'admin.storecategory.deleteall',
        'action_type' => 'deleteall',
'name_ar'=>'حذف' ,
'parent_id'=> 19,
'type'=>'admin']);
Permission::create(['name' => 'admin.storecategory.changesatusall',
        'action_type' => 'changesatusall',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 19,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 15

Permission::create(['name' => 'orders',
        'action_type' => 'orders',
'name_ar'=>'الطلبات' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'orders-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>15,
'type'=>'admin']);

Permission::create(['name' => 'orders-update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>15,
'type'=>'admin']);
Permission::create(['name' => 'orders-delete',
        'action_type' => 'delete',
'name_ar'=>'حذف' ,
'parent_id'=> 15,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 25

Permission::create(['name' => 'abandoned-carts',
        'action_type' => 'abandoned-carts',
'name_ar'=>'السلات المتروكة' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'abandoned-carts-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>29,
'type'=>'admin']);
Permission::create(['name' => 'abandoned-carts-delete',
        'action_type' => 'delete',
'name_ar'=>'حذف' ,
'parent_id'=> 29,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 32

Permission::create(['name' => 'keywords',
        'action_type' => 'keywords',
'name_ar'=>'الكلمات المفتاحية' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'keywords-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>32,
'type'=>'admin']);

Permission::create(['name' => 'keywords-update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>32,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 35

Permission::create(['name' => 'celebrity-marketings',
        'action_type' => 'celebrity-marketings',
'name_ar'=>'التسويق عبر المشاهير' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'celebrity-marketings-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>35,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 37

Permission::create(['name' => 'ratings',
        'action_type' => 'ratings',
'name_ar'=>'التقييمات' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'ratings-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>37,
'type'=>'admin']);
Permission::create(['name' => 'ratings-add',
        'action_type' => 'add',
'name_ar'=>'إضافة' ,
'parent_id'=>37,
'type'=>'admin']);

Permission::create(['name' => 'ratings-delete',
        'action_type' => 'delete',
'name_ar'=>'حذف' ,
'parent_id'=> 37,
'type'=>'admin']);
Permission::create(['name' => 'ratings-activate',
        'action_type' => 'activate',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 37,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 50

Permission::create(['name' => 'template',
        'action_type' => 'template',
'name_ar'=>'القالب' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'template-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>50,
'type'=>'admin']);

Permission::create(['name' => 'template-update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>50,
'type'=>'admin']);


//////////////////////////////////////////////////////////////// 56

Permission::create(['name' => 'socialmedia',
        'action_type' => 'socialmedia',
'name_ar'=>'صفحات التواصل' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'socialmedia-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>56,
'type'=>'admin']);

Permission::create(['name' => 'socialmedia-update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>56,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 59

Permission::create(['name' => 'package-upgrade',
        'action_type' => 'package-upgrade',
'name_ar'=>'ترقية الباقة' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'package-upgrade-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>59,
'type'=>'admin']);

Permission::create(['name' => 'package-upgrade-update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>59,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 62

Permission::create(['name' => 'technical-support',
        'action_type' => 'technical-support',
'name_ar'=>'الدعم الفني' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'technical-support-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>62,
'type'=>'admin']);

Permission::create(['name' => 'technical-support-update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>62,
'type'=>'admin']);
Permission::create(['name' => 'technical-support-delete',
        'action_type' => 'delete',
'name_ar'=>'حذف' ,
'parent_id'=> 62,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 66

Permission::create(['name' => 'shipping-companies',
        'action_type' => 'shipping-companies',
'name_ar'=>'شركات الشحن' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'shipping-companies-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>66,
'type'=>'admin']);

Permission::create(['name' => 'shipping-companies-activate',
        'action_type' => 'activate',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 66,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 69

Permission::create(['name' => 'payments-gateways',
        'action_type' => 'payments-gateways',
'name_ar'=>'بوابات الدفع' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'payments-gateways-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>69,
'type'=>'admin']);

Permission::create(['name' => 'payments-gateways-update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>69,
'type'=>'admin']);

Permission::create(['name' => 'payments-gateways-activate',
        'action_type' => 'activate',
'name_ar'=>'تفعيل / تعطيل' ,
'parent_id'=> 69,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 73

Permission::create(['name' => 'basic-data',
        'action_type' => 'basic-data',
'name_ar'=>'البيانات الأساسية' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'basic-data-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>73,
'type'=>'admin']);

Permission::create(['name' => 'basic-data-update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>73,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 76

Permission::create(['name' => 'maintenance-mode',
        'action_type' => 'maintenance-mode',
'name_ar'=>'وضع الصيانة' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'maintenance-mode-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>76,
'type'=>'admin']);

Permission::create(['name' => 'maintenance-mode-update',
        'action_type' => 'update',
'name_ar'=>'تعديل' ,
'parent_id'=>76,
'type'=>'admin']);


//////////////////////////////////////////////////////////////// 90

Permission::create(['name' => 'notifications',
        'action_type' => 'notifications',
'name_ar'=>'الاشعارات' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'notifications-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>90,
'type'=>'admin']);
Permission::create(['name' => 'notifications-delete',
        'action_type' => 'delete',
'name_ar'=>'حذف' ,
'parent_id'=> 90,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 93

Permission::create(['name' => 'customers',
        'action_type' => 'customers',
'name_ar'=>'العملاء' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'customers-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>93,
'type'=>'admin']);
Permission::create(['name' => 'customers-add',
        'action_type' => 'add',
'name_ar'=>'إضافة' ,
'parent_id'=>93,
'type'=>'admin']);

Permission::create(['name' => 'customers-delete',
        'action_type' => 'delete',
'name_ar'=>'حذف' ,
'parent_id'=> 93,
'type'=>'admin']);
//////////////////////////////////////////////////////////////// 97

Permission::create(['name' => 'platform-services',
        'action_type' => 'platform-services',
'name_ar'=>'خدمات المنصة' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'platform-services-add',
        'action_type' => 'add',
'name_ar'=>'إضافة' ,
'parent_id'=>97,
'type'=>'admin']);

//////////////////////////////////////////////////////////////// 99

Permission::create(['name' => 'reports',
        'action_type' => 'reports',
'name_ar'=>'التقارير' ,
'parent_id'=>null,
'type'=>'admin']);

Permission::create(['name' => 'reports-show',
        'action_type' => 'show',
'name_ar'=>'عرض' ,
'parent_id'=>99,
'type'=>'admin']);





}
}

   

