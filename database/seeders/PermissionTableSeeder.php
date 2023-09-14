<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

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
            'action_type' => 'homepage',
            'name_ar' => 'الرئيسية',
            'parent_id' => null,
            'type' => 'store',
        ]);

        Permission::create(['name' => 'homepage.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 1,
            'type' => 'store',
        ]);

//////////////////////////////////////////////////////////////// 3

        Permission::create(['name' => 'store.categories',
            'action_type' => 'categories',
            'name_ar' => 'التصنيفات',
            'parent_id' => null,
            'type' => 'store',
        ]);

        Permission::create(['name' => 'store.categories.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 3,
            'type' => 'store',
        ]);
        Permission::create(['name' => 'store.categories.store',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 3,
            'type' => 'store',
        ]);

        Permission::create(['name' => 'store.categories.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 3,
            'type' => 'store',
        ]);
        Permission::create(['name' => 'store.categories.delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 3,
            'type' => 'store',
        ]);
        Permission::create(['name' => 'store.categories.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 3,
            'type' => 'store',
        ]);
        Permission::create(['name' => 'store.categories.changestatusall',
            'action_type' => 'changestatusalls',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 3,
            'type' => 'store',
        ]);
        Permission::create(['name' => 'store.categories.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف',
            'parent_id' => 3,
            'type' => 'store',
        ]);

        ////////////////////////////////////////////////////////////////

        Permission::create(['name' => 'store.products',
            'action_type' => 'products',
            'name_ar' => 'المنتجات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.products.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 11,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.store',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 11,
            'type' => 'store']);

        Permission::create(['name' => 'store.products.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 11,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 11,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 11,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.changestatusall',
            'action_type' => 'changestatusall',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 11,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف',
            'parent_id' => 11,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.importfile',
            'action_type' => 'importfile',
            'name_ar' => 'رفع ملف',
            'parent_id' => 11,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.duplicateproduct',
            'action_type' => 'duplicateproduct',
            'name_ar' => 'تكرار منتج',
            'parent_id' => 11,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.deleteimport',
            'action_type' => 'deleteimport',
            'name_ar' => 'حذف منتج مستورد',
            'parent_id' => 11,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.etlobhaShow',
            'action_type' => 'etlobhaShow',
            'name_ar' => 'عرض منتجات اطلبها',
            'parent_id' => 11,
            'type' => 'store']);

        Permission::create(['name' => 'store.products.etlbhasingleproduct',
            'action_type' => 'etlbhasingleproduct',
            'name_ar' => 'عرض تفاصيل منتج مستورد',
            'parent_id' => 11,
            'type' => 'store']);

        Permission::create(['name' => 'store.products.import',
            'action_type' => 'import',
            'name_ar' => 'استيراد منتج',
            'parent_id' => 11,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.updateimport',
            'action_type' => 'updateimport',
            'name_ar' => 'تعديل منتج مستورد',
            'parent_id' => 11,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 15

        Permission::create(['name' => 'store.orders',
            'action_type' => 'orders',
            'name_ar' => 'الطلبات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.orders.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 25,
            'type' => 'store']);

        Permission::create(['name' => 'store.orders.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 25,
            'type' => 'store']);
        Permission::create(['name' => 'store.orders.delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 25,
            'type' => 'store']);
        Permission::create(['name' => 'store.orders.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف',
            'parent_id' => 25,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 19

        Permission::create(['name' => 'store.copons',
            'action_type' => 'copons',
            'name_ar' => 'الكوبونات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.copons.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 27,
            'type' => 'store']);
        Permission::create(['name' => 'store.copons.store',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 27,
            'type' => 'store']);

        Permission::create(['name' => 'store.copons.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 27,
            'type' => 'store']);
        Permission::create(['name' => 'store.copons.delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 27,
            'type' => 'store']);
        Permission::create(['name' => 'store.copons.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 27,
            'type' => 'store']);
        Permission::create(['name' => 'store.copons.changestatusall',
            'action_type' => 'changestatusall',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 27,
            'type' => 'store']);
        Permission::create(['name' => 'store.copons.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف',
            'parent_id' => 27,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 25

        // Permission::create(['name' => 'offers',
        //     'action_type' => 'offers',
        //     'name_ar' => 'العروض الخاصة',
        //     'parent_id' => null,
        //     'type' => 'store']);

        // Permission::create(['name' => 'offers-show',
        //     'action_type' => 'show',
        //     'name_ar' => 'عرض',
        //     'parent_id' => 25,
        //     'type' => 'store']);
        // Permission::create(['name' => 'offers-add',
        //     'action_type' => 'add',
        //     'name_ar' => 'إضافة',
        //     'parent_id' => 25,
        //     'type' => 'store']);

        // Permission::create(['name' => 'offers-activate',
        // 'action_type' => 'activate',
        // 'name_ar' => 'تفعيل / تعطيل',
        // 'parent_id' => 25,
        // 'type' => 'store']);
        //////////////////////////////////////////////////////////////// 29

        Permission::create(['name' => 'abandoned.carts',
            'action_type' => 'abandoned-carts',
            'name_ar' => 'السلات المتروكة',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'abandoned.carts.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 29,
            'type' => 'store']);
        Permission::create(['name' => 'abandoned.carts.delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 29,
            'type' => 'store']);
        Permission::create(['name' => 'abandoned.carts.sendoffer',
            'action_type' => 'delsendofferete',
            'name_ar' => 'ارسال عرض',
            'parent_id' => 29,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 32

        Permission::create(['name' => 'store.keywords',
            'action_type' => 'keywords',
            'name_ar' => 'الكلمات المفتاحية',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.keywords.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 33,
            'type' => 'store']);

        Permission::create(['name' => 'store.keywords.updateseo',
            'action_type' => 'updateseo',
            'name_ar' => 'تعديل الكلمات المفتاحية',
            'parent_id' => 33,
            'type' => 'store']);
        Permission::create(['name' => 'store.keywords.updatelink',
            'action_type' => 'تعديل رابط جوجل انليتكس',
            'name_ar' => 'تعديل',
            'parent_id' => 33,
            'type' => 'store']);

        Permission::create(['name' => 'store.keywords.updaterobots',
            'action_type' => 'updaterobots',
            'name_ar' => 'تعديل ملف الروبوت',
            'parent_id' => 33,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 35
        Permission::create(['name' => 'store.subsicriptions',
            'action_type' => 'subsicriptions',
            'name_ar' => 'الاشتراكات البريدية',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.subsicriptions.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 38,
            'type' => 'store']);

        Permission::create(['name' => 'store.subsicriptions.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف',
            'parent_id' => 38,
            'type' => 'store']);

//////////////////////////////////////////////////////////////// 35

        Permission::create(['name' => 'celebrity-marketings',
            'action_type' => 'celebrity-marketings',
            'name_ar' => 'التسويق عبر المشاهير',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'celebrity-marketings-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 41,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 37

        Permission::create(['name' => 'store.comments',
            'action_type' => 'comments',
            'name_ar' => 'التقييمات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.comments.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 44,
            'type' => 'store']);
        Permission::create(['name' => 'store.comments.store',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 44,
            'type' => 'store']);

        Permission::create(['name' => 'store.comments.delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 44,
            'type' => 'store']);
        Permission::create(['name' => 'store.comments.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 44,
            'type' => 'store']);
        Permission::create(['name' => 'store.comments.activateall',
            'action_type' => 'activateall',
            'name_ar' => 'تفعيل جميع التعليقات',
            'parent_id' => 44,
            'type' => 'store']);
        Permission::create(['name' => 'store.comments.replaycomment',
            'action_type' => 'replaycomment',
            'name_ar' => 'رد على تعليق',
            'parent_id' => 44,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 42

        Permission::create(['name' => 'store.pages',
            'action_type' => 'pages',
            'name_ar' => 'الصفحات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.pages.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 52,
            'type' => 'store']);
        Permission::create(['name' => 'store.pages.store',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 52,
            'type' => 'store']);

        Permission::create(['name' => 'store.pages.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 52,
            'type' => 'store']);
        Permission::create(['name' => 'store.pages.delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 52,
            'type' => 'store']);
        Permission::create(['name' => 'store.pages.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 52,
            'type' => 'store']);
        Permission::create(['name' => 'store.pages.changestatusall',
            'action_type' => 'changestatusall',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 52,
            'type' => 'store']);
        Permission::create(['name' => 'store.pages.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف',
            'parent_id' => 52,
            'type' => 'store']);
        Permission::create(['name' => 'store.pages.publish',
            'action_type' => 'publish',
            'name_ar' => 'نشر صفحة',
            'parent_id' => 52,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 48

        Permission::create(['name' => 'store.academy',
            'action_type' => 'academy',
            'name_ar' => 'الأكاديمية',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.academy.index',
            'action_type' => 'index',
            'name_ar' => ' عرض الكل',
            'parent_id' => 61,
            'type' => 'store']);

        Permission::create(['name' => 'store.academy.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 61,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 50
        Permission::create(['name' => 'store.explainvideos',
            'action_type' => 'explainvideos',
            'name_ar' => 'الشروحات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.explainvideos.index',
            'action_type' => 'index',
            'name_ar' => ' عرض الكل',
            'parent_id' => 64,
            'type' => 'store']);

        Permission::create(['name' => 'store.explainvideos.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 64,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 50

        Permission::create(['name' => 'store.template',
            'action_type' => 'template',
            'name_ar' => 'القالب',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.template.index',
            'action_type' => 'index',
            'name_ar' => 'عرض',
            'parent_id' => 67,
            'type' => 'store']);

        Permission::create(['name' => 'store.template.logoupdate',
            'action_type' => 'logoupdate',
            'name_ar' => 'تعديل الشعار',
            'parent_id' => 67,
            'type' => 'store']);
        Permission::create(['name' => 'store.template.sliderupdate',
            'action_type' => 'sliderupdate',
            'name_ar' => 'تعديل السلايدرات',
            'parent_id' => 67,
            'type' => 'store']);
        Permission::create(['name' => 'store.template.banarupdate',
            'action_type' => 'banarupdate',
            'name_ar' => 'تعديل البنارات',
            'parent_id' => 67,
            'type' => 'store']);

        Permission::create(['name' => 'store.template.commentupdate',
            'action_type' => 'commentupdate',
            'name_ar' => 'تفعيل/تعطيل التعليقات',
            'parent_id' => 67,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 53

        Permission::create(['name' => 'store.verification',
            'action_type' => 'verification',
            'name_ar' => 'توثيق المتجر',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.verification.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 73,
            'type' => 'store']);
        Permission::create(['name' => 'store.verification.add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 73,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 56

        Permission::create(['name' => 'store.socialmedia',
            'action_type' => 'socialmedia',
            'name_ar' => 'صفحات التواصل',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.socialmedia.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 77,
            'type' => 'store']);

        Permission::create(['name' => 'store.socialmedia.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 77,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 59

        // Permission::create(['name' => 'package-upgrade',
        //     'action_type' => 'package-upgrade',
        //     'name_ar' => 'ترقية الباقة',
        //     'parent_id' => null,
        //     'type' => 'store']);

        // Permission::create(['name' => 'package-upgrade-show',
        //     'action_type' => 'show',
        //     'name_ar' => 'عرض',
        //     'parent_id' => 59,
        //     'type' => 'store']);

        // Permission::create(['name' => 'package-upgrade-update',
        //     'action_type' => 'update',
        //     'name_ar' => 'تعديل',
        //     'parent_id' => 59,
        //     'type' => 'store']);
        //////////////////////////////////////////////////////////////// 62

        Permission::create(['name' => 'store.technicalsupport',
            'action_type' => 'technicalsupport',
            'name_ar' => 'الدعم الفني',
            'parent_id' => null,
            'type' => 'store']);
        Permission::create(['name' => 'store.technicalsupport.index',
            'action_type' => 'index',
            'name_ar' => 'عرض الكل',
            'parent_id' => 81,
            'type' => 'store']);

        Permission::create(['name' => 'store.technicalsupport.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 81,
            'type' => 'store']);

        Permission::create(['name' => 'store.technicalsupport.cahngestatus',
            'action_type' => 'cahngestatus',
            'name_ar' => 'تغيير الحالة',
            'parent_id' => 81,
            'type' => 'store']);
        Permission::create(['name' => 'store.technicalsupport.delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 81,
            'type' => 'store']);
        Permission::create(['name' => 'store.technicalsupport.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف الكل',
            'parent_id' => 81,
            'type' => 'store']);
        Permission::create(['name' => 'store.technicalsupport.replay',
            'action_type' => 'replay',
            'name_ar' => 'رد',
            'parent_id' => 81,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 66

        Permission::create(['name' => 'store.shippingcompanies',
            'action_type' => 'shipping-companies',
            'name_ar' => 'شركات الشحن',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.shippingcompanies.index',
            'action_type' => 'index',
            'name_ar' => 'عرض',
            'parent_id' => 88,
            'type' => 'store']);

        Permission::create(['name' => 'store.shippingcompanies.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 88,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 69

        Permission::create(['name' => 'store.paymentsgateways',
            'action_type' => 'paymentsgateways',
            'name_ar' => 'بوابات الدفع',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.paymentsgateways.index',
            'action_type' => 'index',
            'name_ar' => 'عرض',
            'parent_id' => 91,
            'type' => 'store']);

        Permission::create(['name' => 'store.paymentsgateways.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 91,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 73

        Permission::create(['name' => 'store.basicdata',
            'action_type' => 'basicdata',
            'name_ar' => 'البيانات الأساسية',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.basicdata.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 94,
            'type' => 'store']);

        Permission::create(['name' => 'store.basicdata.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 94,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 76

        Permission::create(['name' => 'store.maintenancemode',
            'action_type' => 'maintenancemode',
            'name_ar' => 'وضع الصيانة',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.maintenancemode.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 97,
            'type' => 'store']);

        Permission::create(['name' => 'store.maintenancemode.updatemaintenance',
            'action_type' => 'updatemaintenance',
            'name_ar' => 'تعديل',
            'parent_id' => 97,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 79

        Permission::create(['name' => 'users',
            'action_type' => 'users',
            'name_ar' => 'المستخدمين',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'users-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 79,
            'type' => 'store']);
        Permission::create(['name' => 'users-add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 79,
            'type' => 'store']);

        Permission::create(['name' => 'users-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 79,
            'type' => 'store']);
        Permission::create(['name' => 'users-delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 79,
            'type' => 'store']);
        Permission::create(['name' => 'users-activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 79,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 85

        Permission::create(['name' => 'roles',
            'action_type' => 'roles',
            'name_ar' => 'الأدوار',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'roles-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 85,
            'type' => 'store']);
        Permission::create(['name' => 'roles-add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 85,
            'type' => 'store']);

        Permission::create(['name' => 'roles-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 85,
            'type' => 'store']);
        Permission::create(['name' => 'roles-delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 85,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 90

        Permission::create(['name' => 'notifications',
            'action_type' => 'notifications',
            'name_ar' => 'الاشعارات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'notifications-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 90,
            'type' => 'store']);
        Permission::create(['name' => 'notifications-delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 90,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 93

        Permission::create(['name' => 'customers',
            'action_type' => 'customers',
            'name_ar' => 'العملاء',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'customers-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 93,
            'type' => 'store']);
        Permission::create(['name' => 'customers-add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 93,
            'type' => 'store']);

        Permission::create(['name' => 'customers-delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 93,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 97

        Permission::create(['name' => 'platform-services',
            'action_type' => 'platform-services',
            'name_ar' => 'خدمات المنصة',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'platform-services-add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 97,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 99

        Permission::create(['name' => 'reports',
            'action_type' => 'reports',
            'name_ar' => 'التقارير',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'reports-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 99,
            'type' => 'store']);

    }
}
