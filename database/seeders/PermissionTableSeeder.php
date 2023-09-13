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

        Permission::create(['name' => 'store.store.categories',
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

        Permission::create(['name' => 'products',
            'action_type' => 'products',
            'name_ar' => 'المنتجات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'products-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 11,
            'type' => 'store']);
        Permission::create(['name' => 'products-add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 9,
            'type' => 'store']);

        Permission::create(['name' => 'products-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 9,
            'type' => 'store']);
        Permission::create(['name' => 'products-delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 9,
            'type' => 'store']);
        Permission::create(['name' => 'products-activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 9,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 15

        Permission::create(['name' => 'orders',
            'action_type' => 'orders',
            'name_ar' => 'الطلبات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'orders-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 15,
            'type' => 'store']);

        Permission::create(['name' => 'orders-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 15,
            'type' => 'store']);
        Permission::create(['name' => 'orders-delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 15,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 19

        Permission::create(['name' => 'copons',
            'action_type' => 'copons',
            'name_ar' => 'الكوبونات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'copons-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 19,
            'type' => 'store']);
        Permission::create(['name' => 'copons-add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 19,
            'type' => 'store']);

        Permission::create(['name' => 'copons-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 19,
            'type' => 'store']);
        Permission::create(['name' => 'copons-delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 19,
            'type' => 'store']);
        Permission::create(['name' => 'copons-activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 19,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 25

        Permission::create(['name' => 'offers',
            'action_type' => 'offers',
            'name_ar' => 'العروض الخاصة',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'offers-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 25,
            'type' => 'store']);
        Permission::create(['name' => 'offers-add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 25,
            'type' => 'store']);

        Permission::create(['name' => 'offers-activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 25,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 29

        Permission::create(['name' => 'abandoned-carts',
            'action_type' => 'abandoned-carts',
            'name_ar' => 'السلات المتروكة',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'abandoned-carts-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 29,
            'type' => 'store']);
        Permission::create(['name' => 'abandoned-carts-delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 29,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 32

        Permission::create(['name' => 'keywords',
            'action_type' => 'keywords',
            'name_ar' => 'الكلمات المفتاحية',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'keywords-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 32,
            'type' => 'store']);

        Permission::create(['name' => 'keywords-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 32,
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
            'parent_id' => 35,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 37

        Permission::create(['name' => 'ratings',
            'action_type' => 'ratings',
            'name_ar' => 'التقييمات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'ratings-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 37,
            'type' => 'store']);
        Permission::create(['name' => 'ratings-add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 37,
            'type' => 'store']);

        Permission::create(['name' => 'ratings-delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 37,
            'type' => 'store']);
        Permission::create(['name' => 'ratings-activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 37,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 42

        Permission::create(['name' => 'pages',
            'action_type' => 'pages',
            'name_ar' => 'الصفحات',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'pages-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 42,
            'type' => 'store']);
        Permission::create(['name' => 'pages-add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 42,
            'type' => 'store']);

        Permission::create(['name' => 'pages-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 42,
            'type' => 'store']);
        Permission::create(['name' => 'pages-delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 42,
            'type' => 'store']);
        Permission::create(['name' => 'pages-activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 42,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 48

        Permission::create(['name' => 'academy',
            'action_type' => 'academy',
            'name_ar' => 'الأكاديمية',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'academy-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 48,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 50

        Permission::create(['name' => 'template',
            'action_type' => 'template',
            'name_ar' => 'القالب',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'template-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 50,
            'type' => 'store']);

        Permission::create(['name' => 'template-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 50,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 53

        Permission::create(['name' => 'documentation',
            'action_type' => 'documentation',
            'name_ar' => 'توثيق المتجر',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'documentation-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 53,
            'type' => 'store']);
        Permission::create(['name' => 'documentation-add',
            'action_type' => 'add',
            'name_ar' => 'إضافة',
            'parent_id' => 53,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 56

        Permission::create(['name' => 'socialmedia',
            'action_type' => 'socialmedia',
            'name_ar' => 'صفحات التواصل',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'socialmedia-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 56,
            'type' => 'store']);

        Permission::create(['name' => 'socialmedia-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 56,
            'type' => 'store']);

        //////////////////////////////////////////////////////////////// 59

        Permission::create(['name' => 'package-upgrade',
            'action_type' => 'package-upgrade',
            'name_ar' => 'ترقية الباقة',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'package-upgrade-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 59,
            'type' => 'store']);

        Permission::create(['name' => 'package-upgrade-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 59,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 62

        Permission::create(['name' => 'technical-support',
            'action_type' => 'technical-support',
            'name_ar' => 'الدعم الفني',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'technical-support-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 62,
            'type' => 'store']);

        Permission::create(['name' => 'technical-support-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 62,
            'type' => 'store']);
        Permission::create(['name' => 'technical-support-delete',
            'action_type' => 'delete',
            'name_ar' => 'حذف',
            'parent_id' => 62,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 66

        Permission::create(['name' => 'shipping-companies',
            'action_type' => 'shipping-companies',
            'name_ar' => 'شركات الشحن',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'shipping-companies-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 66,
            'type' => 'store']);

        Permission::create(['name' => 'shipping-companies-activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 66,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 69

        Permission::create(['name' => 'payments-gateways',
            'action_type' => 'payments-gateways',
            'name_ar' => 'بوابات الدفع',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'payments-gateways-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 69,
            'type' => 'store']);

        Permission::create(['name' => 'payments-gateways-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 69,
            'type' => 'store']);

        Permission::create(['name' => 'payments-gateways-activate',
            'action_type' => 'activate',
            'name_ar' => 'تفعيل / تعطيل',
            'parent_id' => 69,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 73

        Permission::create(['name' => 'basic-data',
            'action_type' => 'basic-data',
            'name_ar' => 'البيانات الأساسية',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'basic-data-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 73,
            'type' => 'store']);

        Permission::create(['name' => 'basic-data-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 73,
            'type' => 'store']);
        //////////////////////////////////////////////////////////////// 76

        Permission::create(['name' => 'maintenance-mode',
            'action_type' => 'maintenance-mode',
            'name_ar' => 'وضع الصيانة',
            'parent_id' => null,
            'type' => 'store']);

        Permission::create(['name' => 'maintenance-mode-show',
            'action_type' => 'show',
            'name_ar' => 'عرض',
            'parent_id' => 76,
            'type' => 'store']);

        Permission::create(['name' => 'maintenance-mode-update',
            'action_type' => 'update',
            'name_ar' => 'تعديل',
            'parent_id' => 76,
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
