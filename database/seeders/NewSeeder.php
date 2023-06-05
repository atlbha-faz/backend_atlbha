<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Order;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
/*      Page::create([
            'title' => 'التسويق الرقمي',
            'page_content' => '<h4 style="text-align: right; font-family: Tajawal, sans-serif; font-weight: var(--font-weight-05); line-height: 1.2; margin: 0px; font-size: var(--font-size-28); width: 935.987px; background-color: rgba(2, 70, 106, 0.04); scroll-behavior: smooth !important;">هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة</h4><h4 style="text-align: right; font-family: Tajawal, sans-serif; font-weight: var(--font-weight-05); line-height: 1.2; margin: 0px; font-size: var(--font-size-28); width: 935.987px; background-color: rgba(2, 70, 106, 0.04); scroll-behavior: smooth !important;"><p style="text-align: right; margin-bottom: 0px; font-size: var(--font-size-20); width: 935.987px; color: var(--font-color-05); letter-spacing: 0.4px; scroll-behavior: smooth !important;">لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص</p></h4><h4 style="text-align: right; font-weight: var(--font-weight-05); line-height: 1.2; margin: 0px; font-size: var(--font-size-28); width: 453px; font-family: Tajawal, sans-serif !important; scroll-behavior: smooth !important;">هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة</h4><h4 style="text-align: right; font-family: Tajawal, sans-serif; font-weight: var(--font-weight-05); line-height: 1.2; margin: 0px; font-size: var(--font-size-28); width: 935.987px; background-color: rgba(2, 70, 106, 0.04); scroll-behavior: smooth !important;"><div class="box-1 box" style="align-items: center; display: flex; gap: 30px; justify-content: start; color: rgb(33, 37, 41); font-size: 16px; text-align: start; scroll-behavior: smooth !important;"><div class="info" style="align-items: flex-start; display: flex; flex: 1 1 0%; flex-direction: column; gap: 40px; justify-content: start; scroll-behavior: smooth !important;"><p style="text-align: right; margin-bottom: 0px; font-size: var(--font-size-20); font-weight: var(--font-weight-02); width: 453px; color: var(--font-color-05); letter-spacing: 0.4px; scroll-behavior: smooth !important;">هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك</p></div><div class="box-img" style="text-align: right; flex: 1 1 0%; scroll-behavior: smooth !important;"><img src="http://home.atlbha.com/static/media/image%20blog2.1fe8e7452db60d6aa538.png" alt="" style="max-width: 100%; scroll-behavior: smooth !important;"></div></div></h4><h6 style="text-align: right; font-family: Tajawal, sans-serif; font-weight: var(--font-weight-02); line-height: 1.2; margin: 0px; font-size: var(--font-size-20); letter-spacing: 0.2px; width: 935.987px; background-color: rgba(2, 70, 106, 0.04); scroll-behavior: smooth !important;">المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص</h6><h4 style="text-align: right; font-weight: var(--font-weight-05); line-height: 1.2; margin: 0px; font-size: var(--font-size-28); width: 453px; font-family: Tajawal, sans-serif !important; scroll-behavior: smooth !important;">هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة</h4><h4 style="text-align: right; font-family: Tajawal, sans-serif; font-weight: var(--font-weight-05); line-height: 1.2; margin: 0px; font-size: var(--font-size-28); width: 935.987px; background-color: rgba(2, 70, 106, 0.04); scroll-behavior: smooth !important;"><div class="box-2 box" style="align-items: center; display: flex; gap: 30px; justify-content: start; flex-direction: row-reverse; color: rgb(33, 37, 41); font-size: 16px; text-align: start; scroll-behavior: smooth !important;"><div class="info" style="align-items: flex-start; display: flex; flex: 1 1 0%; flex-direction: column; gap: 40px; justify-content: start; scroll-behavior: smooth !important;"><p style="text-align: right; margin-bottom: 0px; font-size: var(--font-size-20); font-weight: var(--font-weight-02); width: 453px; color: var(--font-color-05); letter-spacing: 0.4px; scroll-behavior: smooth !important;">هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك</p></div><div class="box-img" style="text-align: right; flex: 1 1 0%; scroll-behavior: smooth !important;"><img src="http://home.atlbha.com/static/media/image%20blog13.66ac4eb621ada1d784d0.png" alt="" style="max-width: 100%; scroll-behavior: smooth !important;"></div></div></h4><h6 style="text-align: right; font-family: Tajawal, sans-serif; font-weight: var(--font-weight-02); line-height: 1.2; margin: 0px; font-size: var(--font-size-20); letter-spacing: 0.2px; width: 935.987px; background-color: rgba(2, 70, 106, 0.04); scroll-behavior: smooth !important;">المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص</h6>',
            'seo_title' => 'عنوان',
            'seo_link'=>'http',
            'seo_desc'=>"this is description",
             'page_desc'=>'منصة اطلبها تضمن تقديم حلول ذكية وبسيطة وشاملة لإنشاء متجر الكتروني للعملاء بشكل سلس و بعيد عن التعقيد بحيث تكون الأمور مفهومة لكل الأشخاص من خلال توفير ادوات و وسائل عمل و لوحة تحكم احترافية و بسيطة في العمل لتمكّن التاجر من ادارة اعماله بشكل بسيط و احترافي',
            'tags'=>'about us',
            'user_id'=>1,
            'image'=>"image.png",
            'postcategory_id'=>2,
            'store_id'=>1,
        ]);
          Page::create([
            'title' => 'التجارةالالكترونية',
            'page_content' => '<h4 style="text-align: right; font-family: Tajawal, sans-serif; font-weight: var(--font-weight-05); line-height: 1.2; margin: 0px; font-size: var(--font-size-28); width: 935.987px; background-color: rgba(2, 70, 106, 0.04); scroll-behavior: smooth !important;">هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة</h4><h4 style="text-align: right; font-family: Tajawal, sans-serif; font-weight: var(--font-weight-05); line-height: 1.2; margin: 0px; font-size: var(--font-size-28); width: 935.987px; background-color: rgba(2, 70, 106, 0.04); scroll-behavior: smooth !important;"><p style="text-align: right; margin-bottom: 0px; font-size: var(--font-size-20); width: 935.987px; color: var(--font-color-05); letter-spacing: 0.4px; scroll-behavior: smooth !important;">لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص</p></h4><h4 style="text-align: right; font-weight: var(--font-weight-05); line-height: 1.2; margin: 0px; font-size: var(--font-size-28); width: 453px; font-family: Tajawal, sans-serif !important; scroll-behavior: smooth !important;">هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة</h4><h4 style="text-align: right; font-family: Tajawal, sans-serif; font-weight: var(--font-weight-05); line-height: 1.2; margin: 0px; font-size: var(--font-size-28); width: 935.987px; background-color: rgba(2, 70, 106, 0.04); scroll-behavior: smooth !important;"><div class="box-1 box" style="align-items: center; display: flex; gap: 30px; justify-content: start; color: rgb(33, 37, 41); font-size: 16px; text-align: start; scroll-behavior: smooth !important;"><div class="info" style="align-items: flex-start; display: flex; flex: 1 1 0%; flex-direction: column; gap: 40px; justify-content: start; scroll-behavior: smooth !important;"><p style="text-align: right; margin-bottom: 0px; font-size: var(--font-size-20); font-weight: var(--font-weight-02); width: 453px; color: var(--font-color-05); letter-spacing: 0.4px; scroll-behavior: smooth !important;">هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك</p></div><div class="box-img" style="text-align: right; flex: 1 1 0%; scroll-behavior: smooth !important;"><img src="http://home.atlbha.com/static/media/image%20blog2.1fe8e7452db60d6aa538.png" alt="" style="max-width: 100%; scroll-behavior: smooth !important;"></div></div></h4><h6 style="text-align: right; font-family: Tajawal, sans-serif; font-weight: var(--font-weight-02); line-height: 1.2; margin: 0px; font-size: var(--font-size-20); letter-spacing: 0.2px; width: 935.987px; background-color: rgba(2, 70, 106, 0.04); scroll-behavior: smooth !important;">المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص</h6><h4 style="text-align: right; font-weight: var(--font-weight-05); line-height: 1.2; margin: 0px; font-size: var(--font-size-28); width: 453px; font-family: Tajawal, sans-serif !important; scroll-behavior: smooth !important;">هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة</h4><h4 style="text-align: right; font-family: Tajawal, sans-serif; font-weight: var(--font-weight-05); line-height: 1.2; margin: 0px; font-size: var(--font-size-28); width: 935.987px; background-color: rgba(2, 70, 106, 0.04); scroll-behavior: smooth !important;"><div class="box-2 box" style="align-items: center; display: flex; gap: 30px; justify-content: start; flex-direction: row-reverse; color: rgb(33, 37, 41); font-size: 16px; text-align: start; scroll-behavior: smooth !important;"><div class="info" style="align-items: flex-start; display: flex; flex: 1 1 0%; flex-direction: column; gap: 40px; justify-content: start; scroll-behavior: smooth !important;"><p style="text-align: right; margin-bottom: 0px; font-size: var(--font-size-20); font-weight: var(--font-weight-02); width: 453px; color: var(--font-color-05); letter-spacing: 0.4px; scroll-behavior: smooth !important;">هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك</p></div><div class="box-img" style="text-align: right; flex: 1 1 0%; scroll-behavior: smooth !important;"><img src="http://home.atlbha.com/static/media/image%20blog13.66ac4eb621ada1d784d0.png" alt="" style="max-width: 100%; scroll-behavior: smooth !important;"></div></div></h4><h6 style="text-align: right; font-family: Tajawal, sans-serif; font-weight: var(--font-weight-02); line-height: 1.2; margin: 0px; font-size: var(--font-size-20); letter-spacing: 0.2px; width: 935.987px; background-color: rgba(2, 70, 106, 0.04); scroll-behavior: smooth !important;">المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص</h6>',
            'seo_title' => 'عنوان',
            'seo_link'=>'http',
            'seo_desc'=>"this is description",
             'page_desc'=>'منصة اطلبها تضمن تقديم حلول ذكية وبسيطة وشاملة لإنشاء متجر الكتروني للعملاء بشكل سلس و بعيد عن التعقيد بحيث تكون الأمور مفهومة لكل الأشخاص من خلال توفير ادوات و وسائل عمل و لوحة تحكم احترافية و بسيطة في العمل لتمكّن التاجر من ادارة اعماله بشكل بسيط و احترافي',
            'tags'=>'about us',
            'user_id'=>1,
            'image'=>"image.png",
            'postcategory_id'=>1,
            'store_id'=>1
        ]);
          Category::create([
                'number' =>0003,
                 'name' => 'أجهزة كمبيوتر',
                 'icon'=>'cat.png',
                 'parent_id'=>null,
                'for'=>'store',
                'store_id'=>1


                ]);
                Category::create([
                    'number' =>0004,
                     'name' => 'جوالات',
                     'parent_id'=>3,
                    'for'=>'store',
                    'store_id'=>1
                    ]);
  Category::create([
                    'number' =>0004,
                     'name' => '2جوالات',
                     'parent_id'=>3,
                    'for'=>'store',
                       'store_id'=>1
                       ]);
*/

          Order::create([
            'order_number' => '4455',
            'user_id' => 2,
            'quantity' => 5,
            'total_price' => 1100,
            'tax' => 2.3,
            'shipping_price' => 10,
            'discount' => 400,
            'order_status' => 'completed',
            'payment_status' => 'pending',
            'store_id' => 1,

        ]);
          Order::create([
            'order_number' => '5657',
            'user_id' => 1,
            'quantity' => 4,
            'total_price' => 1000,
            'tax' => 1.2,
            'shipping_price' => 10,
            'discount' => 0,
            'order_status' => 'completed',
            'payment_status' => "pending",
            'store_id' => 1,

        ]);

          OrderItem::create([
             'product_id'=>70,
            'order_id'=>2,
            'user_id' => 6,
            'price' => 500,
            'discount' => 400,
            'quantity' => 3,
            'total_price' => 600,
            'order_status' => 'completed',
            'payment_status' => 1,

        ]);
           OrderItem::create([
             'product_id'=>102,
            'order_id'=>1,
            'user_id' => 6,
            'price' => 500,
            'discount' => 400,
            'quantity' => 3,
            'total_price' => 600,
            'order_status' => 'completed',
            'payment_status' => 1,

        ]);
    }
}
