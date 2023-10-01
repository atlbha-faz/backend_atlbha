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

        Permission::create(['name' => 'store.homepage',
            'action_type' => 'homepage',
            'name_ar' => 'الرئيسية',
            'parent_id' => null,
            'type' => 'store',
        ]);

        Permission::create(['name' => 'store.homepage.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 210,
            'type' => 'store',
        ]);

////////////////////////////////////////////////////// 212

        Permission::create(['name' => 'store.categories',
            'action_type' => 'categories',
            'name_ar' => 'التصنيفات',
            'parent_id' => null,
            'type' => 'store',
        ]);

        Permission::create(['name' => 'store.categories.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 212,
            'type' => 'store',
        ]);
        Permission::create(['name' => 'store.categories.store',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 212,
            'type' => 'store',
        ]);

        Permission::create(['name' => 'store.categories.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 212,
            'type' => 'store',
        ]);
        Permission::create(['name' => 'store.categories.destroy',
            'action_type' => 'destroy',
            'name_ar' => 'حذف',
            'parent_id' => 212,
            'type' => 'store',
        ]);
        Permission::create(['name' => 'store.categories.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 212,
            'type' => 'store',
        ]);
        Permission::create(['name' => 'store.categories.changestatusall',
            'action_type' => 'changestatusalls',
            'name_ar' => 'تفعيل الكل / تعطيل الكل',
            'parent_id' => 212,
            'type' => 'store',
        ]);
        Permission::create(['name' => 'store.categories.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف الكل',
            'parent_id' => 212,
            'type' => 'store',
        ]);

        ////////////////////////////////////////////////////////////////220

        Permission::create(['name' => 'store.products',
            'action_type' => 'products',
            'name_ar' => 'المنتجات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.products.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 220,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.store',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 220,
            'type' => 'store']);

        Permission::create(['name' => 'store.products.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 220,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.destroy',
            'action_type' => 'destroy',
            'name_ar' => 'حذف',
            'parent_id' => 220,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 220,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.changestatusall',
            'action_type' => 'changestatusall',
            'name_ar' => 'تفعيل الكل / تعطيل الكل',
            'parent_id' => 220,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف الكل',
            'parent_id' => 220,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.importfile',
            'action_type' => 'importfile',
            'name_ar' => 'رفع ملف',
            'parent_id' => 220,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.duplicateproduct',
            'action_type' => 'duplicateproduct',
            'name_ar' => 'تكرار منتج',
            'parent_id' => 220,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.deleteimport',
            'action_type' => 'deleteimport',
            'name_ar' => 'حذف منتج مستورد',
            'parent_id' => 220,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.etlobhaShow',
            'action_type' => 'etlobhaShow',
            'name_ar' => 'عرض منتجات اطلبها',
            'parent_id' => 220,
            'type' => 'store']);

        Permission::create(['name' => 'store.products.etlbhasingleproduct',
            'action_type' => 'etlbhasingleproduct',
            'name_ar' => 'عرض تفاصيل منتج مستورد',
            'parent_id' => 220,
            'type' => 'store']);

        Permission::create(['name' => 'store.products.import',
            'action_type' => 'import',
            'name_ar' => 'استيراد منتج',
            'parent_id' => 220,
            'type' => 'store']);
        Permission::create(['name' => 'store.products.updateimport',
            'action_type' => 'updateimport',
            'name_ar' => 'تعديل منتج مستورد',
            'parent_id' => 220,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 235

        Permission::create(['name' => 'store.orders',
            'action_type' => 'orders',
            'name_ar' => 'الطلبات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.orders.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 235,
            'type' => 'store']);

        Permission::create(['name' => 'store.orders.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 235,
            'type' => 'store']);
        Permission::create(['name' => 'store.orders.destroy',
            'action_type' => 'destroy',
            'name_ar' => 'حذف',
            'parent_id' => 235,
            'type' => 'store']);
        Permission::create(['name' => 'store.orders.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف',
            'parent_id' => 235,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 240

        Permission::create(['name' => 'store.copons',
            'action_type' => 'copons',
            'name_ar' => 'الكوبونات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.copons.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 240,
            'type' => 'store']);
        Permission::create(['name' => 'store.copons.store',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 240,
            'type' => 'store']);

        Permission::create(['name' => 'store.copons.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 240,
            'type' => 'store']);
        Permission::create(['name' => 'store.copons.destroy',
            'action_type' => 'destroy',
            'name_ar' => 'حذف',
            'parent_id' => 240,
            'type' => 'store']);
        Permission::create(['name' => 'store.copons.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 240,
            'type' => 'store']);
        Permission::create(['name' => 'store.copons.changestatusall',
            'action_type' => 'changestatusall',
            'name_ar' => 'تفعيل الكل / تعطيل الكل',
            'parent_id' => 240,
            'type' => 'store']);
        Permission::create(['name' => 'store.copons.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف الكل',
            'parent_id' => 240,
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
        //////////////////////////////////////////////////////////////// 248

        Permission::create(['name' => 'abandoned.carts',
            'action_type' => 'abandoned-carts',
            'name_ar' => 'السلات المتروكة',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'abandoned.carts.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 248,
            'type' => 'store']);
        Permission::create(['name' => 'abandoned.carts.destroy',
            'action_type' => 'destroy',
            'name_ar' => 'حذف',
            'parent_id' => 248,
            'type' => 'store']);
        Permission::create(['name' => 'abandoned.carts.sendoffer',
            'action_type' => 'sendoffer',
            'name_ar' => 'ارسال عرض',
            'parent_id' => 248,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 252

        Permission::create(['name' => 'store.seo',
            'action_type' => 'seo',
            'name_ar' => 'تحسينات السيو',
            'parent_id' => null,
            'type' => 'store']);
        Permission::create(['name' => 'store.seo.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 252,
            'type' => 'store']);
        Permission::create(['name' => 'store.seo.updateGoogleAnalytics',
            'action_type' => 'updateGoogleAnalytics',
            'name_ar' => 'تعديل ربط جوجل انليتكس',
            'parent_id' => 252,
            'type' => 'store']);
        Permission::create(['name' => 'store.seo.updateSnapPixel',
            'action_type' => 'updateSnapPixel',
            'name_ar' => 'تعديل سناب بكسل',
            'parent_id' => 252,
            'type' => 'store']);
        Permission::create(['name' => 'store.seo.updateTiktokPixel',
            'action_type' => 'updateTiktokPixel',
            'name_ar' => 'تعديل تيك توك بكسل',
            'parent_id' => 252,
            'type' => 'store']);
        Permission::create(['name' => 'store.seo.updateTwitterpixel',
            'action_type' => 'updateTwitterpixel',
            'name_ar' => 'تعديل تويتر بكسل',
            'parent_id' => 252,
            'type' => 'store']);
        Permission::create(['name' => 'store.seo.updateInstapixel',
            'action_type' => 'updateInstapixel',
            'name_ar' => 'تعديل انستجرام بكسل',
            'parent_id' => 252,
            'type' => 'store']);

        Permission::create(['name' => 'store.seo.updateMetaTags',
            'action_type' => 'updateMetaTags',
            'name_ar' => 'تعديل ملف الميتا تاج',
            'parent_id' => 252,
            'type' => 'store']);
        Permission::create(['name' => 'store.seo.updateKeyWords',
            'action_type' => 'updateKeyWords',
            'name_ar' => 'تعديل الكلمات المفتاحية',
            'parent_id' => 252,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 262
        Permission::create(['name' => 'store.subsicriptions',
            'action_type' => 'subsicriptions',
            'name_ar' => 'الاشتراكات البريدية',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.subsicriptions.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 262,
            'type' => 'store']);

        Permission::create(['name' => 'store.subsicriptions.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف',
            'parent_id' => 262,
            'type' => 'store']);

//////////////////////////////////////////////////////////////// 265

        Permission::create(['name' => 'celebrity-marketings',
            'action_type' => 'celebrity-marketings',
            'name_ar' => 'التسويق عبر المشاهير',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'celebrity-marketings-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 265,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 267

        Permission::create(['name' => 'store.comments',
            'action_type' => 'comments',
            'name_ar' => 'التقييمات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.comments.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 267,
            'type' => 'store']);
        Permission::create(['name' => 'store.comments.store',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 267,
            'type' => 'store']);

        Permission::create(['name' => 'store.comments.destroy',
            'action_type' => 'destroy',
            'name_ar' => 'حذف',
            'parent_id' => 267,
            'type' => 'store']);
        Permission::create(['name' => 'store.comments.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 267,
            'type' => 'store']);
        Permission::create(['name' => 'store.comments.activateall',
            'action_type' => 'activateall',
            'name_ar' => 'تفعيل جميع التعليقات',
            'parent_id' => 267,
            'type' => 'store']);
        Permission::create(['name' => 'store.comments.replaycomment',
            'action_type' => 'replaycomment',
            'name_ar' => 'رد على تعليق',
            'parent_id' => 267,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 274

        Permission::create(['name' => 'store.pages',
            'action_type' => 'pages',
            'name_ar' => 'الصفحات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.pages.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 274,
            'type' => 'store']);
        Permission::create(['name' => 'store.pages.store',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 274,
            'type' => 'store']);

        Permission::create(['name' => 'store.pages.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 274,
            'type' => 'store']);
        Permission::create(['name' => 'store.pages.destroy',
            'action_type' => 'destroy',
            'name_ar' => 'حذف',
            'parent_id' => 274,
            'type' => 'store']);
        Permission::create(['name' => 'store.pages.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 274,
            'type' => 'store']);
        Permission::create(['name' => 'store.pages.changestatusall',
            'action_type' => 'changestatusall',
            'name_ar' => 'تفعيل الكل / تعطيل الكل',
            'parent_id' => 274,
            'type' => 'store']);
        Permission::create(['name' => 'store.pages.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف الكل',
            'parent_id' => 274,
            'type' => 'store']);
        Permission::create(['name' => 'store.pages.publish',
            'action_type' => 'publish',
            'name_ar' => 'نشر صفحة',
            'parent_id' => 274,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 283

        Permission::create(['name' => 'store.academy',
            'action_type' => 'academy',
            'name_ar' => 'الأكاديمية',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.academy.index',
            'action_type' => 'index',
            'name_ar' => ' عرض الكل',
            'parent_id' => 283,
            'type' => 'store']);

        Permission::create(['name' => 'store.academy.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 283,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 286
        Permission::create(['name' => 'store.explainvideos',
            'action_type' => 'explainvideos',
            'name_ar' => 'الشروحات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.explainvideos.index',
            'action_type' => 'index',
            'name_ar' => ' عرض الكل',
            'parent_id' => 286,
            'type' => 'store']);

        Permission::create(['name' => 'store.explainvideos.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 286,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 289

        Permission::create(['name' => 'store.template',
            'action_type' => 'template',
            'name_ar' => 'القالب',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.template.index',
            'action_type' => 'index',
            'name_ar' => 'عرض',
            'parent_id' => 289,
            'type' => 'store']);

        Permission::create(['name' => 'store.template.logoupdate',
            'action_type' => 'logoupdate',
            'name_ar' => 'تعديل الشعار',
            'parent_id' => 289,
            'type' => 'store']);
        Permission::create(['name' => 'store.template.sliderupdate',
            'action_type' => 'sliderupdate',
            'name_ar' => 'تعديل السلايدرات',
            'parent_id' => 289,
            'type' => 'store']);
        Permission::create(['name' => 'store.template.banarupdate',
            'action_type' => 'banarupdate',
            'name_ar' => 'تعديل البنارات',
            'parent_id' => 289,
            'type' => 'store']);

        Permission::create(['name' => 'store.template.commentupdate',
            'action_type' => 'commentupdate',
            'name_ar' => 'تفعيل/تعطيل التعليقات',
            'parent_id' => 289,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 295

        Permission::create(['name' => 'store.verification',
            'action_type' => 'verification',
            'name_ar' => 'توثيق المتجر',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.verification.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 295,
            'type' => 'store']);
        Permission::create(['name' => 'store.verification.add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 295,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 298

        Permission::create(['name' => 'store.socialmedia',
            'action_type' => 'socialmedia',
            'name_ar' => 'صفحات التواصل',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.socialmedia.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 298,
            'type' => 'store']);

        Permission::create(['name' => 'store.socialmedia.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 298,
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
        //////////////////////////////////////////////////////////////// 301

        Permission::create(['name' => 'store.technicalsupport',
            'action_type' => 'technicalsupport',
            'name_ar' => 'الدعم الفني',
            'parent_id' => null,
            'type' => 'store']);
        Permission::create(['name' => 'store.technicalsupport.index',
            'action_type' => 'index',
            'name_ar' => 'عرض الكل',
            'parent_id' => 301,
            'type' => 'store']);

        Permission::create(['name' => 'store.technicalsupport.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 301,
            'type' => 'store']);

        Permission::create(['name' => 'store.technicalsupport.cahngestatus',
            'action_type' => 'cahngestatus',
            'name_ar' => 'تغيير الحالة',
            'parent_id' => 301,
            'type' => 'store']);
        Permission::create(['name' => 'store.technicalsupport.destroy',
            'action_type' => 'destroy',
            'name_ar' => 'حذف',
            'parent_id' => 301,
            'type' => 'store']);
        Permission::create(['name' => 'store.technicalsupport.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف الكل',
            'parent_id' => 301,
            'type' => 'store']);
        Permission::create(['name' => 'store.technicalsupport.replay',
            'action_type' => 'replay',
            'name_ar' => 'رد',
            'parent_id' => 301,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 308

        Permission::create(['name' => 'store.shippingcompanies',
            'action_type' => 'shipping-companies',
            'name_ar' => 'شركات الشحن',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.shippingcompanies.index',
            'action_type' => 'index',
            'name_ar' => 'عرض',
            'parent_id' => 308,
            'type' => 'store']);

        Permission::create(['name' => 'store.shippingcompanies.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 308,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 311

        Permission::create(['name' => 'store.paymentsgateways',
            'action_type' => 'paymentsgateways',
            'name_ar' => 'بوابات الدفع',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.paymentsgateways.index',
            'action_type' => 'index',
            'name_ar' => 'عرض',
            'parent_id' => 311,
            'type' => 'store']);

        Permission::create(['name' => 'store.paymentsgateways.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 311,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 314

        Permission::create(['name' => 'store.basicdata',
            'action_type' => 'basicdata',
            'name_ar' => 'البيانات الأساسية',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.basicdata.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 314,
            'type' => 'store']);

        Permission::create(['name' => 'store.basicdata.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 314,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 317

        Permission::create(['name' => 'store.maintenancemode',
            'action_type' => 'maintenancemode',
            'name_ar' => 'وضع الصيانة',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.maintenancemode.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 317,
            'type' => 'store']);

        Permission::create(['name' => 'store.maintenancemode.updatemaintenance',
            'action_type' => 'updatemaintenance',
            'name_ar' => 'تعديل',
            'parent_id' => 317,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 320

        Permission::create(['name' => 'store.users',
            'action_type' => 'users',
            'name_ar' => 'المستخدمين',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.users.index',
            'action_type' => 'index',
            'name_ar' => 'عرض الكل',
            'parent_id' => 320,
            'type' => 'store']);
        Permission::create(['name' => 'store.users.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 320,
            'type' => 'store']);

        Permission::create(['name' => 'store.users.store',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 320,
            'type' => 'store']);

        Permission::create(['name' => 'store.users.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 320,
            'type' => 'store']);
        Permission::create(['name' => 'store.users.destroy',
            'action_type' => 'destroy',
            'name_ar' => 'حذف',
            'parent_id' => 320,
            'type' => 'store']);
        Permission::create(['name' => 'store.users.changestatusall',
            'action_type' => 'changestatusall',
            'name_ar' => 'تفعيل الكل/ تعطيل الكل',
            'parent_id' => 320,
            'type' => 'store']);
        Permission::create(['name' => 'store.users.deleteall',
            'action_type' => 'deleteall',
            'name_ar' => 'حذف الكل',
            'parent_id' => 320,
            'type' => 'store']);
        Permission::create(['name' => 'store.users.activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 320,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 329

        Permission::create(['name' => 'store.roles',
            'action_type' => 'roles',
            'name_ar' => 'الأدوار',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.roles.index',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 329,
            'type' => 'store']);
        Permission::create(['name' => 'store.roles.store',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 329,
            'type' => 'store']);

        Permission::create(['name' => 'store.roles.update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 329,
            'type' => 'store']);
        Permission::create(['name' => 'store.roles.destroy',
            'action_type' => 'destroy',
            'name_ar' => 'حذف',
            'parent_id' => 329,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 334

        Permission::create(['name' => 'store.notifications',
            'action_type' => 'notifications',
            'name_ar' => 'الاشعارات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.notifications.NotificationIndex',
            'action_type' => 'NotificationIndex',
            'name_ar' => 'عرض الكل',
            'parent_id' => 334,
            'type' => 'store']);
        Permission::create(['name' => 'store.notifications.NotificationRead',
            'action_type' => 'NotificationRead',
            'name_ar' => 'قراءة',
            'parent_id' => 334,
            'type' => 'store']);
        Permission::create(['name' => 'store.notifications.NotificationDelete',
            'action_type' => 'NotificationDelete',
            'name_ar' => 'حذف',
            'parent_id' => 334,
            'type' => 'store']);
        Permission::create(['name' => 'store.notifications.NotificationDeleteAll',
            'action_type' => 'NotificationDeleteAll',
            'name_ar' => 'حذف الكل',
            'parent_id' => 334,
            'type' => 'store']);
        Permission::create(['name' => 'store.notifications.NotificationShow',
            'action_type' => 'NotificationShow',
            'name_ar' => ' عرض',
            'parent_id' => 334,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 93

        // Permission::create(['name' => 'customers',
        //     'action_type' => 'customers',
        //     'name_ar' => 'العملاء',
        //     'parent_id' => null,
        //     'type' => 'store']);

        // Permission::create(['name' => 'customers-show',
        //     'action_type' => 'show',
        //     'name_ar' => 'عرض',
        //     'parent_id' => 93,
        //     'type' => 'store']);
        // Permission::create(['name' => 'customers-add',
        //     'action_type' => 'add',
        //     'name_ar' => 'إضافة',
        //     'parent_id' => 93,
        //     'type' => 'store']);

        // Permission::create(['name' => 'customers-destroy',
        // 'action_type' => 'destroy',
        // 'name_ar' => 'حذف',
        // 'parent_id' => 93,
        // 'type' => 'store']);
        //////////////////////////////////////////////////////////////// 340

        Permission::create(['name' => 'store.platformservices',
            'action_type' => 'platformservices',
            'name_ar' => 'خدمات المنصة',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.platformservices.show',
            'action_type' => 'show',
            'name_ar' => 'عرض متاجر',
            'parent_id' => 340,
            'type' => 'store']);
        Permission::create(['name' => 'store.platformservices.add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 340,
            'type' => 'store']);
        Permission::create(['name' => 'store.platformservices.marketerRequest',
            'action_type' => 'marketerRequest',
            'name_ar' => 'طلب مندوب',
            'parent_id' => 340,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 344

        Permission::create(['name' => 'store.reports',
            'action_type' => 'reports',
            'name_ar' => 'التقارير',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.reports.show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 344,
            'type' => 'store']);
//////////////////////////////////////////////////////////////////// 346

        Permission::create(['name' => 'store.etlbhacomment',
            'action_type' => 'etlbhacomment',
            'name_ar' => 'تقييم المنصة',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'store.etlbhacomment.add',
            'action_type' => 'add',
            'name_ar' => ' اضافة تقييم للمنصة',
            'parent_id' => 346,
            'type' => 'store']);

    }
}
