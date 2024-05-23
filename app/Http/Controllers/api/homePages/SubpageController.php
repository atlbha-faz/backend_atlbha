<?php

namespace App\Http\Controllers\api\homePages;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\PackageResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\website_socialmediaResource;
use App\Models\Homepage;
use App\Models\Package;
use App\Models\Page;
use App\Models\Page_page_category;
use App\Models\website_socialmedia;

class SubpageController extends BaseController
{

    public function show($page_id)
    {
        $page = Page::with(['user' => function ($query) {
            $query->select('id', 'name');
        }])->where('id', $page_id)->select('id', 'title', 'page_content', 'altImage', 'default_page', 'page_desc', 'seo_title', 'seo_link', 'seo_desc', 'tags', 'status', 'image')->first();
        if (is_null($page) || $page->is_deleted != 0) {
            return $this->sendError("الصفحة غير موجودة", "Page is't exists");
        }
        $success['pages'] = new PageResource($page);
        
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الصفحة بنجاح', 'Page showed successfully');
    }

    public function packages()
    {
        $success['packages'] = PackageResource::collection(Package::where('is_deleted', 0)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الباقات بنجاح', 'packages return successfully');
    }
    public function homeService()
    {
        $data = [
            [
                'title' => 'الاستشارات',
                'description' => 'تقدم منصة اطلبها خدمة الاستشارات في التعاملات الالكترونية لإدارة المشاريع والمتاجر الالكترونية وهيكلة الوظائف والتنظيم الإداري لها كما تقدم النصائح الهامة في التسويق وطرق البيع وتطوير المهارات في شراء المنتجات وبيعها. لطلب الاستشارة',
                'is_have_link' => false,
                'link' => '',
            ],
            [
                'title' => 'صناعة البراند وتصميم الهوية البصرية',
                'description' => 'نساعدك في اختيار الاسم وتصميم الهوية البصرية للبراند الخاص فيك وفق اشتراطات الهيئة السعودية للملكية الفكرية ، ونستورد منتجاتك من مصادر جملة الجملة في الصين تحمل علامة براندك الخاص. للمزيد ',
                'is_have_link' => true,
                'link' => 'https://atlbha.sa/detail/353',
            ],
            [
                'title' => 'الاستيراد من الصين',
                'description' => 'تسعى منصة اطلبها من خلال هذه الخدمة التسهيل على عملائها استيراد المنتجات من مصادر جملة الجملة وإنهاء الإجراءات بعد التأكد من تطابق المنتجات مع نشاط السجل التجاري مطابقة لمعايير ( سابر ) مع توصيلها الى مستودع العميل داخل السعودية. للمزيد ',
                'is_have_link' => true,
                'link' => 'https://atlbha.sa/detail/354',
            ], [
                'title' => 'سوق اطلبها',
                'description' => 'يوفر سوق اطلبها عدة اصناف وانواع مختلفة من المنتجات عالية الجودة وبإسعار منافسة جدا لجميع التجار والمتسوقين ويستطيع أي شخص طلب كميات كبيرة او صغيرة حسب احتياجه وقدرته المالية',
                'is_have_link' => false,
                'link' => '',
            ], [
                'title' => 'الرحلات التجارية',
                'description' => 'البرنامج الذي يصنع علامتك التجارية وانطلاقة مشروعك التجاري جولات حقيقية ومباشرة من داخل الصين لتبدأ رحلتك في التجارة حيث نصطحبك برفقة خبراء في مجال الأعمال لتصل إلى أقل الأسعار من محلات ومصانع الجملة',
                'is_have_link' => false,
                'link' => '',
            ], [
                'title' => 'الاكاديمية',
                'description' => 'يأتي إنشاء الاكاديمية لتلبية الاحتياجات التدريبية الخاصة بسوق العمل الحر والتجارة الالكترونية بتقديم دورات لتطوير مهارات العميل في التسويق والتصميم وادارة المتاجر ورفع جودتها وتحسين مبيعاتها',
                'is_have_link' => false,
                'link' => '',
            ], [
                'title' => 'بوابات الدفع',
                'description' => 'التجارة الالكترونية تعتمد بشكل كبير على الدفع الإلكتروني لذلك منصة اطلبها قامت بعقد اتفاقيات مع شركات الدفع الالكتروني لاتمام وتسهيل عمليات الدفع بشكل سريع وآمن.',
                'is_have_link' => false,
                'link' => '',
            ], [
                'title' => 'شركات الشحن',
                'description' => 'التجارة الالكترونية تعتمد بشكل كبير على خدمة الشحن لذلك منصة اطلبها قامت بعقد اتفاقيات مع الشركات لتوصيل الطلبات بشكل سريع ',
                'is_have_link' => false,
                'link' => '',
            ], [
                'title' => 'قناة اطلبها',
                'description' => 'تعمل منصة اطلبها على نشر قصص نجاح عملائها من رواد أعمال بدوء تجارتهم معانا وحققو مبيعات عالية.',
                'is_have_link' => false,
                'link' => '',
            ], [
                'title' => 'نماذج عمل',
                'description' => 'تتيح منصة اطلبها لعملائها نماذج جاهزة للتعاقد مع خبراء ومتخصصين في مجالات متعددة لانجاز المهام والاعمال والتصاميم.',
                'is_have_link' => false,
                'link' => '',
            ], [
                'title' => 'مدير متجر خاص',
                'description' => 'تتيح منصة اطلبها لعملائها التعاقد مع متخصصين وخبراء في إدارة المتاجر لتطويرها.',
                'is_have_link' => false,
                'link' => '',
            ], [
                'title' => 'التسويق عبر المشاهير',
                'description' => 'عمدت منصة اطلبها عقد شراكة مع منصة المشاهير لتمكين عملائها من سهولة التواصل المباشر مع مجموعة كبيرة من المشاهير بعقود موثقة لطلب خدمة الإعلان لمنتجاتهم وخدماتهم والتسويق لمتاجرهم.',
                'is_have_link' => false,
                'link' => '',
            ], [
                'title' => 'التصميم',
                'description' => 'نوفر مصممين محترفين لعمل تصاميم (شعارات-بنرات-بوستات اعلانية) لمتاجر عملائنا في منصة اطلبها لزيادة جذب المتسوقين ومنافسة المتاجر الأخرى.',
                'is_have_link' => false,
                'link' => '',
            ], [
                'title' => 'خدمة العملاء',
                'description' => 'تقدم منصة اطلبها فريق عمل متميز في خدمة العملاء على مدار ٢٤ ساعة لمساعدة عملائها والرد على الاستفسارات وتحويلها الى المختصين.',
                'is_have_link' => false,
                'link' => '',
            ],
            [
                'title' => 'الدعم الفني',
                'description' => 'لمساعدة عملائنا خصصنا فريق مختص في الدعم الفني في حل المشاكل التقنية التي قد تواجههم في اسرع وقت ممكن على مدار 24/7',
                'is_have_link' => false,
                'link' => '',
            ],
        ];
        $success['homeServices'] = $data;
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم ارجاع الخدمات بنجاح', 'home Service return successfully');

    }

}
