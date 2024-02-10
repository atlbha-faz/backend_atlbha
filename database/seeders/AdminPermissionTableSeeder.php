<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

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
            'name_ar' => 'الرئيسية',
            'guard_name' => 'api',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.mainpage.index',
            'action_type' => 'index',
            'name_ar' => 'عرض',
            'guard_name' => 'api',
            'parent_id' => 1,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 3

        Permission::create(['name' => 'admin.etlobha',
            'action_type' => 'admin.etlobha',

            'name_ar' => 'سوق اطلبها',
            'guard_name' => 'api',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.etlobha.index',
            'action_type' => 'index',

            'name_ar' => ' عرض الكل',
            'guard_name' => 'api',
            'parent_id' => 3,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.etlobha.store',
            'action_type' => 'store',

            'name_ar' => 'إضافة',
            'guard_name' => 'api',
            'parent_id' => 3,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.etlobha.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 3,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.etlobha.show',
            'action_type' => 'show',

            'name_ar' => 'عرض',
            'guard_name' => 'api',
            'parent_id' => 3,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.etlobha.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 3,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.etlobha.changeStatusall',
            'action_type' => 'changeStatusall',
            'guard_name' => 'api',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 3,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.etlobha.specialStatus',
            'action_type' => 'specialStatus',
            'guard_name' => 'api',
            'name_ar' => 'مميز / غير مميز',
            'parent_id' => 3,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.etlobha.statistics',
            'action_type' => 'statistics',
            'guard_name' => 'api',
            'name_ar' => 'احصائيات المنتج',
            'parent_id' => 3,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 12

        Permission::create(['name' => 'admin.stock',
            'action_type' => 'admin.stock',
            'guard_name' => 'api',
            'name_ar' => 'المخزون ',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.stock.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => ' عرض الكل',
            'parent_id' => 12,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.stock.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => 'إضافة',
            'parent_id' => 12,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.stock.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 12,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.stock.addToStore',
            'action_type' => 'addToStore',
            'guard_name' => 'api',
            'name_ar' => 'إضافة الى السوق',
            'parent_id' => 12,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.stock.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف',
            'parent_id' => 12,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.stock.importStockProducts',
            'action_type' => 'importStockProducts',
            'guard_name' => 'api',
            'name_ar' => 'تصدير ملف',
            'parent_id' => 12,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 19

        Permission::create(['name' => 'admin.platform',
            'action_type' => 'admin.platform',
            'guard_name' => 'api',
            'name_ar' => 'السوق العام',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.platform.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => ' عرض الكل',
            'parent_id' => 19,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.platform.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => 'إضافة',
            'parent_id' => 19,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.platform.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 19,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.platform.destroy',
            'action_type' => 'destroy',
            'guard_name' => 'api',
            'name_ar' => 'حذف ',
            'parent_id' => 19,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.platform.changePlatformStatus',
            'action_type' => 'changePlatformStatus',
            'guard_name' => 'api',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 19,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.platform.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 19,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 25

        Permission::create(['name' => 'admin.adminOrder',
            'action_type' => 'admin.adminOrder',
            'guard_name' => 'api',
            'name_ar' => 'طلبات سوق اطلبها',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.adminOrder.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => ' عرض الكل',
            'parent_id' => 25,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.adminOrder.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 25,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.adminOrder.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'حذف ',
            'parent_id' => 25,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 29

        Permission::create(['name' => 'admin.store',
            'action_type' => 'admin.store',
            'name_ar' => 'المتاجر',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.store.index',
            'action_type' => 'index',
            'name_ar' => ' عرض الكل',
            'parent_id' => 29,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.store.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => 'إضافة',
            'parent_id' => 29,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.store.updateProfile',
            'action_type' => 'update',

            'name_ar' => ' تعديل كلمة المرور',
            'parent_id' => 6,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.store.destroy',
            'action_type' => 'destroy',
            'guard_name' => 'api',
            'name_ar' => 'حذف ',
            'parent_id' => 29,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.store.changeSatusall',
            'action_type' => 'changeSatusall',
            'guard_name' => 'api',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 29,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.store.specialStatus',
            'action_type' => 'specialStatus',
            'guard_name' => 'api',
            'name_ar' => 'مميز / غير مميز',
            'parent_id' => 29,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.store.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 29,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.store.loginStore',
            'action_type' => 'loginStore',
            'guard_name' => 'api',
            'name_ar' => 'دخول المتاجر',
            'parent_id' => 29,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 36
        Permission::create(['name' => 'admin.product',
            'action_type' => 'product',
            'guard_name' => 'api',
            'name_ar' => 'المنتجات',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.product.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => ' عرض الكل',
            'parent_id' => 36,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.product.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 36,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.product.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 36,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.product.changeSatusall',
            'action_type' => 'changeSatusall',
            'guard_name' => 'api',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 36,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.product.addNote',
            'action_type' => 'addNote',
            'guard_name' => 'api',
            'name_ar' => '  إضافة ملاحظة ',
            'parent_id' => 36,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 42
        Permission::create(['name' => 'admin.verification',
            'action_type' => 'verification',
            'guard_name' => 'api',
            'name_ar' => 'التوثيق',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.verification.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => '  عرض التوثيق',
            'parent_id' => 42,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.verification.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 42,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.verification.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 42,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.verification.changeSatusall',
            'action_type' => 'changeSatusall',
            'guard_name' => 'api',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 42,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.verification.addNote',
            'action_type' => 'addNote',
            'guard_name' => 'api',
            'name_ar' => '  إضافة ملاحظة ',
            'parent_id' => 42,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.verification.acceptVerification',
            'action_type' => 'acceptVerification',
            'guard_name' => 'api',
            'name_ar' => 'قبول التوثيق',
            'parent_id' => 42,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.verification.rejectVerification',
            'action_type' => 'rejectVerification',
            'guard_name' => 'api',
            'name_ar' => ' رفض التوثيق',
            'parent_id' => 42,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.verification.verification_update',
            'action_type' => 'verification_update',
            'guard_name' => 'api',
            'name_ar' => '  تعديل التوثيق',
            'parent_id' => 42,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 50
        Permission::create(['name' => 'admin.package',
            'action_type' => 'package',
            'guard_name' => 'api',
            'name_ar' => 'الباقات',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.package.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => ' عرض الباقات',
            'parent_id' => 50,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.package.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => '  إضافة  ',
            'parent_id' => 50,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.package.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => '  تعديل  ',
            'parent_id' => 50,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.package.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 50,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.package.changeSatusall',
            'action_type' => 'changeSatusall',
            'guard_name' => 'api',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 50,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 56
        Permission::create(['name' => 'admin.subscriptions',
            'action_type' => 'subscriptions',
            'guard_name' => 'api',
            'name_ar' => 'الاشتراكات الحالية',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.subscriptions.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض الاشتراكات الحالية ',
            'parent_id' => 56,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.subscriptions.changeSatusall',
            'action_type' => 'changeSatusall',
            'guard_name' => 'api',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 56,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.subscriptions.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 56,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.subscriptions.addAlert',
            'action_type' => 'addAlert',
            'guard_name' => 'api',
            'name_ar' => '  إضافة تنبية ',
            'parent_id' => 56,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 61
        Permission::create(['name' => 'admin.service',
            'action_type' => 'service',
            'guard_name' => 'api',
            'name_ar' => 'الخدمات',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.service.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض الخدمات ',
            'parent_id' => 61,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.service.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => ' إضافة ',
            'parent_id' => 61,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.service.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => ' التعديل ',
            'parent_id' => 61,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.service.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 61,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.service.showdetail',
            'action_type' => 'showdetail',
            'guard_name' => 'api',
            'name_ar' => '  عرض التفاصيل ',
            'parent_id' => 61,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 65
        Permission::create(['name' => 'admin.course',
            'action_type' => 'course',
            'guard_name' => 'api',
            'name_ar' => 'أكاديمية أطلبها',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.course.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => ' عرض الدورات التدريبية',
            'parent_id' => 65,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.course.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => '  إضافة  ',
            'parent_id' => 65,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.course.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => '  تعديل  ',
            'parent_id' => 65,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.course.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 65,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.course.destroy',
            'action_type' => 'destroy',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 65,
            'type' => 'admin']);
// Permission::create(['name' => 'admin.course.addvideo',
//         'action_type' => 'addvideo',
//         'guard_name'=>'api',
// 'name_ar'=>'إضافة فيديو' ,
// 'parent_id'=> 65,
// 'type'=>'admin']);
// Permission::create(['name' => 'admin.course.deletevideo',
//         'action_type' => 'deletevideo',
//         'guard_name'=>'api',
// 'name_ar'=>' حذف فيديو' ,
// 'parent_id'=> 65,
// 'type'=>'admin']);
        Permission::create(['name' => 'admin.course.deleteunit',
            'action_type' => 'deleteunit',
            'guard_name' => 'api',
            'name_ar' => 'حذف وحدة',
            'parent_id' => 65,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.explainvideo.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => ' عرض الشروحات',
            'parent_id' => 65,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.explainvideo.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => '  إضافة الشرح ',
            'parent_id' => 65,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.explainvideo.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => '  تعديل الشرح ',
            'parent_id' => 65,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.explainvideo.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض الشرح',
            'parent_id' => 65,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.explainvideo.destroy',
            'action_type' => 'destroy',
            'guard_name' => 'api',
            'name_ar' => 'حذف الشرح',
            'parent_id' => 65,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 79

        Permission::create(['name' => 'admin.homepage',
            'action_type' => 'homepage',
            'guard_name' => 'api',
            'name_ar' => 'القالب',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.homepage.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض القالب ',
            'parent_id' => 79,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.homepage.logoUpdate',
            'action_type' => 'logoUpdate',
            'guard_name' => 'api',
            'name_ar' => 'تعديل الشعار',
            'parent_id' => 79,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.homepage.banarUpdate',
            'action_type' => 'banarUpdate',
            'guard_name' => 'api',
            'name_ar' => 'تعديل البانرات',
            'parent_id' => 79,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.homepage.sliderUpdate',
            'action_type' => 'sliderUpdate',
            'guard_name' => 'api',
            'name_ar' => 'تعديل السالايدرات',
            'parent_id' => 79,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.section.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض الاقسام',
            'parent_id' => 79,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.section.sectionupdate',
            'action_type' => 'sectionupdate',
            'guard_name' => 'api',
            'name_ar' => 'تعديل الاقسام',
            'parent_id' => 79,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.section.changestatus',
            'action_type' => 'changestatus',
            'guard_name' => 'api',
            'name_ar' => 'تغيير حالة الاقسام',
            'parent_id' => 79,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 87

        Permission::create(['name' => 'admin.page',
            'action_type' => 'page',
            'guard_name' => 'api',
            'name_ar' => 'الصفحات',
            'parent_id' => null,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.page.index',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => ' عرض الكل ',
            'parent_id' => 87,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.page.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 87,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.page.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => 'إضافة',
            'parent_id' => 87,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.page.publish',
            'action_type' => 'publish',
            'guard_name' => 'api',
            'name_ar' => 'نشر',
            'parent_id' => 87,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.page.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 87,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.page.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 9,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.page.changesatusall',
            'action_type' => 'changesatusall',
            'guard_name' => 'api',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 87,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 93

        Permission::create(['name' => 'admin.user',
            'action_type' => 'user',
            'name_ar' => 'المستخدمين',
            'guard_name' => 'api',
            'parent_id' => null,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.user.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض المستخدمين',
            'parent_id' => 93,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.user.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 93,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.user.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => 'إضافة',
            'parent_id' => 93,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.user.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 93,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.user.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 93,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.user.changesatusall',
            'action_type' => 'changesatusall',
            'guard_name' => 'api',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 93,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 100

        Permission::create(['name' => 'admin.role',
            'action_type' => 'role',
            'guard_name' => 'api',
            'name_ar' => 'الادوار',
            'parent_id' => null,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.role.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض الادوار',
            'parent_id' => 100,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.role.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 100,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.role.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => 'إضافة',
            'parent_id' => 100,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.role.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 100,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.role.destroy',
            'action_type' => 'destroy',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 100,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 106

        Permission::create(['name' => 'admin.coupon',
            'action_type' => 'coupon',
            'guard_name' => 'api',
            'name_ar' => 'الكوبونات',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.coupon.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض الكوبونات',
            'parent_id' => 106,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.coupon.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 106,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.coupon.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => 'إضافة',
            'parent_id' => 106,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.coupon.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 106,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.coupon.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 106,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.coupon.changesatusall',
            'action_type' => 'changesatusall',
            'guard_name' => 'api',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 106,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 113

        Permission::create(['name' => 'admin.marketer',
            'action_type' => 'marketer',
            'guard_name' => 'api',
            'name_ar' => 'المندوبين',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.marketer.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض المندوبين',
            'parent_id' => 113,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.marketer.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 113,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.marketer.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => 'إضافة',
            'parent_id' => 113,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.marketer.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 113,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.marketer.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 113,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.marketer.changesatusall',
            'action_type' => 'changesatusall',
            'guard_name' => 'api',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 113,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.marketer.registration_marketer_show',
            'action_type' => 'registration_marketer_show',
            'guard_name' => 'api',
            'name_ar' => 'عرض حالة تسجيل المندوبين',
            'parent_id' => 113,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.marketer.registrationmarketer',
            'action_type' => 'registrationmarketer',
            'guard_name' => 'api',
            'name_ar' => 'تعديل حالة التسجيل',
            'parent_id' => 113,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 122

        Permission::create(['name' => 'admin.category',
            'action_type' => 'category',
            'guard_name' => 'api',
            'name_ar' => 'التصنيفات والنشاطات ',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.category.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => '   عرض تصنيفات السوق',
            'parent_id' => 122,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.category.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 122,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.category.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => 'إضافة',
            'parent_id' => 122,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.category.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 122,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.category.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 122,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.category.changesatusall',
            'action_type' => 'changesatusall',
            'guard_name' => 'api',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 122,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 129

// Permission::create(['name' => 'admin.storecategory',
//         'action_type' => 'storecategory',
//         'guard_name'=>'api',
// 'name_ar'=>'تصنيفات المتاجر' ,
// 'parent_id'=>null,
// 'type'=>'admin']);

// Permission::create(['name' => 'admin.storecategory.index',
//         'action_type' => 'index',
//         'guard_name'=>'api',
// 'name_ar'=>'   عرض تصنيفات المتاجر' ,
// 'parent_id'=>129,
// 'type'=>'admin']);
// Permission::create(['name' => 'admin.storecategory.show',
//         'action_type' => 'show',
//         'guard_name'=>'api',
// 'name_ar'=>'عرض' ,
// 'parent_id'=>129,
// 'type'=>'admin']);
// Permission::create(['name' => 'admin.storecategory.store',
//         'action_type' => 'store',
//         'guard_name'=>'api',
// 'name_ar'=>'إضافة' ,
// 'parent_id'=>129,
// 'type'=>'admin']);

// Permission::create(['name' => 'admin.storecategory.update',
//         'action_type' => 'update',
//         'guard_name'=>'api',
// 'name_ar'=>'تعديل' ,
// 'parent_id'=>129,
// 'type'=>'admin']);
// Permission::create(['name' => 'admin.storecategory.deleteall',
//         'action_type' => 'deleteall',
//         'guard_name'=>'api',
// 'name_ar'=>'حذف' ,
// 'parent_id'=> 129,
// 'type'=>'admin']);
// Permission::create(['name' => 'admin.storecategory.changesatusall',
//         'action_type' => 'changesatusall',
//         'guard_name'=>'api',
// 'name_ar'=>'تفعيل / تعطيل' ,
// 'parent_id'=> 129,
// 'type'=>'admin']);

//////////////////////////////////////////////////////////////// 136

        Permission::create(['name' => 'admin.websiteorder',
            'action_type' => 'websiteorder',
            'guard_name' => 'api',
            'name_ar' => 'الطلبات',
            'parent_id' => null,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.websiteorder.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 136,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.websiteorder.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 136,
            'type' => 'admin']);

// Permission::create(['name' => 'admin.websiteorder.update',
//         'action_type' => 'update',
//         'guard_name'=>'api',
// 'name_ar'=>'تعديل' ,
// 'parent_id'=>136,
// 'type'=>'admin']);
        Permission::create(['name' => 'admin.wbsiteorder.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 136,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.websiteorder.acceptService',
            'action_type' => 'acceptService',
            'guard_name' => 'api',
            'name_ar' => 'قبول الخدمة',
            'parent_id' => 136,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.websiteorder.rejectService',
            'action_type' => 'rejectService',
            'guard_name' => 'api',
            'name_ar' => 'رفض الخدمة',
            'parent_id' => 136,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 143

        Permission::create(['name' => 'admin.technicalsupport',
            'action_type' => 'technicalsupport',
            'guard_name' => 'api',
            'name_ar' => 'الدعم الفني',
            'parent_id' => null,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.technicalsupport.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 143,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.technicalsupport.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 143,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.technicalsupport.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 143,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.technicalsupport.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 143,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 148

        Permission::create(['name' => 'admin.atlobhaContact',
            'action_type' => 'atlobhaContact',
            'guard_name' => 'api',
            'name_ar' => 'تواصل معنا',
            'parent_id' => null,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.atlobhaContact.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 148,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.atlobhaContact.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 148,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.atlobhaContact.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 148,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.atlobhaContact.changeStatus',
            'action_type' => 'changeStatus',
            'guard_name' => 'api',
            'name_ar' => 'تعديل الحالة',
            'parent_id' => 148,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 153
        Permission::create(['name' => 'admin.paymenttype',
            'action_type' => 'paymenttype',
            'guard_name' => 'api',
            'name_ar' => 'شركات الدفع ',
            'parent_id' => null,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.paymenttype.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 153,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.paymenttype.changeStatus',
            'action_type' => 'changeStatus',
            'guard_name' => 'api',
            'name_ar' => 'تعديل الحالة',
            'parent_id' => 153,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 156
        Permission::create(['name' => 'admin.shippingtype',
            'action_type' => 'shippingtype',
            'guard_name' => 'api',
            'name_ar' => 'شركات الشحن ',
            'parent_id' => null,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.shippingtype.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 156,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.shippingtype.changeStatus',
            'action_type' => 'changeStatus',
            'guard_name' => 'api',
            'name_ar' => 'تعديل الحالة',
            'parent_id' => 156,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 158
        Permission::create(['name' => 'admin.setting',
            'action_type' => 'setting',
            'guard_name' => 'api',
            'name_ar' => ' الاعدادات ',
            'parent_id' => null,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.setting.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 158,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.setting.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل ',
            'parent_id' => 158,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 161
        Permission::create(['name' => 'admin.country',
            'action_type' => 'country',
            'guard_name' => 'api',
            'name_ar' => ' الدول',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.country.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => '   عرض الدول',
            'parent_id' => 161,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.country.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 161,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.country.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => 'إضافة',
            'parent_id' => 161,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.country.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 161,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.country.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 161,
            'type' => 'admin']);

//////////////////////////////////////////////////////////////// 167
        Permission::create(['name' => 'admin.city',
            'action_type' => 'city',
            'guard_name' => 'api',
            'name_ar' => ' المدن',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.city.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => '   عرض المدن',
            'parent_id' => 167,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.city.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 167,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.city.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => 'إضافة',
            'parent_id' => 167,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.city.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 167,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.city.deleteall',
            'action_type' => 'deleteall',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 167,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 173
        Permission::create(['name' => 'admin.currency',
            'action_type' => 'currency',
            'guard_name' => 'api',
            'name_ar' => ' العملات',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.currency.index',
            'action_type' => 'index',
            'guard_name' => 'api',
            'name_ar' => '   عرض العملات',
            'parent_id' => 173,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.currency.show',
            'action_type' => 'show',
            'guard_name' => 'api',
            'name_ar' => 'عرض',
            'parent_id' => 173,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.currency.store',
            'action_type' => 'store',
            'guard_name' => 'api',
            'name_ar' => 'إضافة',
            'parent_id' => 173,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.currency.update',
            'action_type' => 'update',
            'guard_name' => 'api',
            'name_ar' => 'تعديل',
            'parent_id' => 173,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.currency.destroy',
            'action_type' => 'destroy',
            'guard_name' => 'api',
            'name_ar' => 'حذف',
            'parent_id' => 173,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.currency.changeSatusall',
            'action_type' => 'changeSatusall',
            'name_ar' => 'تعديل الحالة',
            'parent_id' => 173,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 180
        Permission::create(['name' => 'admin.email',
            'action_type' => 'email',
            'name_ar' => ' الايميلات',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.email.index',
            'action_type' => 'index',
            'name_ar' => '   عرض الايميلات',
            'parent_id' => 180,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.email.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 180,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.email.store',
            'action_type' => 'store',
            'name_ar' => 'إضافة',
            'parent_id' => 180,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.email.deleteEmailAll',
            'action_type' => 'deleteEmailAll',
            'name_ar' => 'حذف',
            'parent_id' => 180,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 185
        Permission::create(['name' => 'admin.websitesocialmedia',
            'action_type' => 'websitesocialmedia',
            'name_ar' => ' التواصل الاجتماعي',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.websitesocialmedia.index',
            'action_type' => 'index',
            'name_ar' => 'عرض وسائل التواصل الاجتماعي ',
            'parent_id' => 185,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.websitesocialmedia.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 185,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.websitesocialmedia.store',
            'action_type' => 'store',
            'name_ar' => 'إضافة',
            'parent_id' => 185,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.websitesocialmedia.destroy',
            'action_type' => 'destroy',
            'name_ar' => 'حذف',
            'parent_id' => 185,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.websitesocialmedia.changeStatus',
            'action_type' => 'changeStatus',
            'name_ar' => 'تعديل الحالة',
            'parent_id' => 185,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 191
        Permission::create(['name' => 'admin.registration',
            'action_type' => 'registration',
            'name_ar' => 'حالة التسجيل',
            'parent_id' => null,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.registration.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 191,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.registration.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل ',
            'parent_id' => 191,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 194
        Permission::create(['name' => 'admin.notification',
            'action_type' => 'notification',
            'name_ar' => 'الإشعارات',
            'parent_id' => null,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.notification.index',
            'action_type' => 'index',
            'name_ar' => 'عرض',
            'parent_id' => 194,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.notification.read',
            'action_type' => 'read',
            'name_ar' => 'قراءة الاشعار ',
            'parent_id' => 194,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.notification.show',
            'action_type' => 'show',
            'name_ar' => 'عرض الاشعار ',
            'parent_id' => 194,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.notification.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف الاشعار ',
            'parent_id' => 194,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 199
        Permission::create(['name' => 'admin.seo',
            'action_type' => 'admin.seo',

            'name_ar' => 'تحسينات السيو ',
            'parent_id' => null,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.seo.index',
            'action_type' => 'index',

            'name_ar' => 'عرض',
            'parent_id' => 199,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.seo.store',
            'action_type' => 'store',

            'name_ar' => 'إضافة',
            'parent_id' => 199,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.seo.updateGoogleAnalytics',
            'action_type' => 'update',

            'name_ar' => 'تعديل',
            'parent_id' => 199,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.seo.show',
            'action_type' => 'show',

            'name_ar' => 'عرض',
            'parent_id' => 199,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.seo.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف',
            'parent_id' => 199,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.seo.changeStatusall',
            'action_type' => 'changeStatusall',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 199,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.seo.specialStatus',
            'action_type' => 'specialStatus',
            'name_ar' => 'مميز / غير مميز',
            'parent_id' => 199,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.seo.statistics',
            'action_type' => 'statistics',
            'name_ar' => 'احصائيات المنتج',
            'parent_id' => 199,
            'type' => 'admin']);
//////////////////////////////////////////////////////////////// 208
        Permission::create(['name' => 'admin.comment',
            'action_type' => 'admin.comment',
            'name_ar' => ' تقييمات المنصة',
            'parent_id' => null,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.comment.index',
            'action_type' => 'index',
            'name_ar' => 'عرض',
            'parent_id' => 208,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.comment.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 208,
            'type' => 'admin']);

        Permission::create(['name' => 'admin.comment.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف',
            'parent_id' => 208,
            'type' => 'admin']);
        Permission::create(['name' => 'admin.comment.changeSatusall',
            'action_type' => 'changeSatusall',
            'name_ar' => 'تعديل الحالة',
            'parent_id' => 208,
            'type' => 'admin']);
    }
}
