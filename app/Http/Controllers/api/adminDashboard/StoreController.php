<?php

namespace App\Http\Controllers\api\adminDashboard;

use Exception;
use Carbon\Carbon;
use App\Models\Note;
use App\Models\Page;
use App\Models\User;
use App\Models\Store;
use App\Models\Theme;
use App\Mail\SendMail;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Homepage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Mocdels\paymenttype_store;
use App\Mocdels\shippingtype_store;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\NoteResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\StoreResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\VerificationResource;
use App\Http\Controllers\api\BaseController as BaseController;

class StoreController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function loginId($id)
    {

        $user = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', $id)->first();
        if (isset($user->id) && $user->id != 0) {

            $success['user'] = new UserResource($user);
            $success['token'] = $user->createToken('authToken')->accessToken;
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح', 'Login Successfully');
        } else {
            return $this->sendError("المتجر غير موجودة", "store is't exists");
        }

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $success['stores'] =
        StoreResource::collection(Store::with(['categories' => function ($query) {
            $query->select('name', 'icon');
        }, 'city' => function ($query) {
            $query->select('id','name');
        }, 'country' => function ($query) {
            $query->select('id');
        }, 'user' => function ($query) {
            $query->select('id');
        }])->where('is_deleted', 0)->where('verification_status', '!=', 'pending')->orderByDesc('created_at')->select('id', 'store_name', 'domain','phonenumber', 'status', 'periodtype', 'logo', 'icon', 'special','store_email','verification_status', 'city_id','verification_date', 'created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المتاجر بنجاح', 'Stores return successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'user_name' => ['required', 'string', 'max:255', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0);
            })],

            'store_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0);
            })],
            'store_email' => ['required', 'email', Rule::unique('stores')->where(function ($query) {
                return $query->where('is_deleted', 0);
            })],
            'password' => 'required|min:8|string',
            'domain' => ['required', 'string', Rule::unique('stores')->where(function ($query) {
                return $query->where('is_deleted', 0);
            })],
            'userphonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users', 'phonenumber')->where(function ($query) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0);
            })],

            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('stores')->where(function ($query) {
                return $query->where('is_deleted', 0);
            })],
            'activity_id' => 'required|array',
            'subcategory_id' => ['nullable', 'array'],
            //'package_id' =>'required',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'user_country_id' => 'required|exists:countries,id',
            'user_city_id' => 'required|exists:cities,id',
            //'periodtype' => 'nullable|required_unless:package_id,1|in:6months,year',
            'periodtype' => 'required|in:6months,year',
            'status' => 'required|in:active,inactive',
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],

        ]);

        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_name' => $request->user_name,
            'user_type' => "store",
            'password' => $request->password,
            'phonenumber' => $request->userphonenumber,
            'image' => $request->image,
            'country_id' => $request->user_country_id,
            'city_id' => $request->user_city_id,
            'status' => $request->status,
        ]);

        $userid = $user->id;

        $request->package_id = 2;
        $request->periodtype = "6months";

        $store = Store::create([
            'store_name' => $request->store_name,
            'store_email' => $request->store_email,
            'domain' => $request->domain,
            'icon' => $request->icon,
            'phonenumber' => $request->phonenumber,
            'description' => $request->description,
            'business_license' => $request->business_license,
            'ID_file' => $request->ID_file,
            'snapchat' => $request->snapchat,
            'facebook' => $request->facebook,
            'snapchat' => $request->snapchat,
            'twiter' => $request->twiter,
            'youtube' => $request->youtube,
            'instegram' => $request->instegram,
            'logo' => $request->logo,
            'entity_type' => $request->entity_type,
            'package_id' => $request->package_id,
            'user_id' => $userid,
            'periodtype' => $request->periodtype,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
        ]);
        $user->update([
            'store_id' => $store->id,
        ]);
        $user->assignRole("المالك");
        $Homepage = Homepage::create([
            'banar1' => 'banar.png',
            'banarstatus1' => 'active',
            'banar2' => 'banar.png',
            'banarstatus2' => 'active',
            'banar3' => 'banar.png',
            'banarstatus3' => 'active',
            'slider1' => 'slider.png',
            'sliderstatus1' => 'active',
            'slider2' => 'slider.png',
            'sliderstatus2' => 'active',
            'slider3' => 'slider.png',
            'sliderstatus3' => 'active',
            'store_id' => $store->id,
        ]);
        $theme = Theme::create([
            'store_id' => $store->id,
        ]);
        $page1 = Page::create([
            'title' => 'سياسة الخصوصية',
            'page_content' => '<p class="ql-align-justify ql-direction-rtl"><span style="color: black;">متجر _____________ يؤمن بأنّ حماية بياناتك الشخصية هو أمر مهم ويريد أن يحيطك علمًا كمستخدم للموقع ("</span><strong style="color: black;">الـمتجر"</strong><span style="color: black;">) بكيفية جمع واستخدام ومعالجة وكشف معلوماتك الشخصية وبآلية السرية والخصوصية المعمول بها في المتجر؛ لذلك قام المتجر بإنشاء سياسة الخصوصية هذه لإظهار مدى التزامه بحماية خصوصية بيانات المستخدمين على منصته ولتوضيح ما هي البيانات التي نقوم بجمعها أثناء استخدامك لخدماتنا أو شراء منتجاتنا.</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">يرجى قراءة سياسة الخصوصية هذه، حيث إنّ بولوجكم إلى المتجر واستخدامكم له فإنّ جميع معلوماتكم تخضع لهذه السياسة.</strong></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">&nbsp;</strong></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">ماذا تغطي سياسة الخصوصية؟</strong></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">تغطي سياسة الخصوصية جميع البيانات التي تحصل عليها المتجر وتحتفظ بها في أنظمتها الإلكترونية، ومنها:</span></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">·&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;المعلومات والبيانات الشخصية التي تقدمها لنا.</span></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">·&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;معلومات يتم جمعها تلقائيًا عند استخدامك لخدمات المتجر.</span></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">&nbsp;</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">البيانات الشخصية التي تقدمها لنا</strong></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">البيانات الشخصية هي معلومات ذات طابع شخصي يزودها المستخدم لنا عند استخدامه لخدمات المتجر والتي تمكّنا من التعرّف على المستخدمين ومنها – على سبيل المثال لا الحصر – البيانات التالية:</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">بيانات الاتصال الأساسية:</strong><span style="color: black;"> عندما تقوم بالتسجيل في المتجر للاستفادة من خدماتنا فإننا نقوم بجمع بيانات الاتصال الأساسية الخاصة بك والتي تشمل اسمك، وعنوانك، ورقم هاتفك، والبريد الإلكتروني.</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">بيانات الطلب:</strong><span style="color: black;"> عندما تقوم بشراء منتج أو خدمة من المتجر فإننا نقوم بجمع معلومات عن طلبك بما في ذلك اسمك، وعنوان إرسال الفواتير، وعنوان الشحن، بالإضافة إلى البريد الإلكتروني، ورقم الهاتف.</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">المراسلات:</strong><span style="color: black;"> عندما تقوم بالتواصل أو بالمراسلات مع خدمة العملاء، عبر الهاتف، أو البريد الإلكتروني، أو وسائل التواصل المعلنة في المتجر فإننا نجمع البيانات المتعلقة بالاتصال وأي معلومات أخرى تقوم بتقديمها لنا.</span></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">&nbsp;</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">معلومات يتم جمعها تلقائيًا عند استخدامك لخدمات المتجر</strong></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">هي معلومات غير شخصية يقوم متصفحك بإرسالها إلى خوادمنا تلقائيًا وهي على سبيل المثال لا الحصر البيانات التالية:</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">بيانات الاستخدام:</strong><span style="color: black;"> نقوم بجمع معلومات حول تفاعلك مع الخدمات المقدّمة، مثل الصفحات أو المحتويات التي تستعرضها على المتجر، وعمليات البحث التي تجريها، ومعلومات حول نشاطك والمدة التي تقضيها على الصفحة أو الشاشة، ومسارات التنقل بين الصفحات بما في ذلك المعلومات المتعلقة بأنواع ومواصفات المنتجات والخدمات المشتراة ومعلومات التسعير والتسليم.</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">بيانات الموقع:</strong><span style="color: black;"> قد نجمع معلومات حول موقعك على سبيل المثال: المدينة/الدولة المرتبطة بعنوان بروتوكول الانترنت "IP" الخاص بك. كما أنّه عند استخدام خاصية مشاركة الموقع، قد نقوم بجمع معلومات الموقع لجهازك المحمول. ضع في اعتبارك أنّ معظم الأجهزة المحمولة تسمح لك بالتحكم في تفعيل أو تعطيل خدمات الموقع لأي تطبيق على جهازك المحمول في قائمة إعدادات الجهاز.</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">بيانات الجهاز:</strong><span style="color: black;"> نقوم بجمع معلومات حول جهاز الحاسوب أو الجهاز المحمول الخاص بك، مثل نوع نظام التشغيل ورقم الإصدار، الشركة المصنعة، نوع المتصفح المستخدم، دقة الشاشة، معرفات الجهاز، عنوان IP، أو معلومات الموقع العامة مثل المدينة أو الدولة أو المنطقة الجغرافية.</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">ملفات تعريف الارتباط والتقنيات المشابهة:</strong><span style="color: black;"> كما هو شائع في العديد من المواقع الإلكترونية الأخرى، يتم إرسال ملفات تعريف الارتباط إلى الجهاز الخاص بك. الغرض منها هو فهم آلية تفاعلك واستخدامك للخدمات المقدّمة على المتجر وذلك لقياس أداء المستخدمين وتحسين الخدمات المقدّمة على المتجر.</span></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">&nbsp;</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">ما الغرض من جمع واستخدام بياناتك الشخصية؟</strong></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">نقوم بشكل عام بجمع ومعالجة بياناتك الشخصية لتحقيق أغراض مختلفة لتمكينك من الاستفادة من خدماتنا وخدمات الأطراف الثالثة وذلك – على سبيل المثال لا الحصر – من أجل:</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">توفير المنتجات والخدمات:</strong><span style="color: black;"> نحن نستخدم معلوماتك الشخصية لتسهيل عملية شرائك للمنتجات والخدمات الخاصة بنا، بما في ذلك معالجة مدفوعاتك، وتلبية طلباتك، وإرسال إشعارات إليك تتعلق بحسابك، أو عمليات الشراء، أو المرتجعات، أو التبادلات أو المعاملات الأخرى، وترتيب الشحن وتسهيل أي عمليات ارجاع واستبدال، وتمكينك من نشر التقييمات.</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">التسويق والإعلان:</strong><span style="color: black;"> نحن نستخدم معلوماتك الشخصية لأغراض تسويقية وترويجية، مثل إرسال اتصالات تسويقية أو إعلانية، وترويجية عبر البريد الإلكتروني أو الرسائل النصية. قد يشمل ذلك استخدام معلوماتك الشخصية لتخصيص الخدمات والإعلانات بشكل أفضل على موقعنا والمواقع الأخرى.</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">خدمة العملاء:</strong><span style="color: black;"> يحتاج فريق خدمة العملاء إلى الاطلاع على بياناتك الشخصية والتفاصيل الخاصة بك من أجل الرد على استفساراتك وتقديم الخدمات وتقديم المساعدة التي تحتاجها.</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">الأغراض القانونية:</strong><span style="color: black;"> قد نحتاج إلى استخدام معلوماتك الشخصية للتعامل مع الدعاوى والنزاعات القانونية وحلها، أو لتطبيق الشروط والأحكام الخاصة بنا أو الامتثال للطلبات القانونية من الجهات والسلطات المختصة.</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">&nbsp;</strong></p><p class="ql-direction-rtl"><strong>من هم الأطراف الثالثة التي نشارك معها بياناتك الشخصية؟</strong></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">مزودي الخدمات:</strong><span style="color: black;"> قد نتعامل مع مزودي خدمات لدعمنا في بيع منتجاتنا وخدماتنا على المتجر وهم – على سبيل المثال لا الحصر – مقدمي الخدمات التالية:</span></p><ul><li class="ql-align-justify ql-direction-rtl">الجهات المقدمة لخدمات مركز الاتصال، خدمات العملاء، إدارة علاقات العملاء.</li><li class="ql-align-justify ql-direction-rtl">بوابات الدفع.</li><li class="ql-align-justify ql-direction-rtl">مقدمي الخدمات اللوجستية وخدمات الشحن.</li></ul><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">&nbsp;</strong></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">دون ذلك، ما لم نحصل على موافقتك، لن نقوم بمشاركة معلوماتك الشخصية لأي أطراف ثالثة بأي شكل من الأشكال.</strong></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">&nbsp;</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">الاحتفاظ ببياناتك الشخصية وحذفها</strong></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">نحن نحتفظ بمعلوماتك الشخصية طالما أن لدينا حاجة تجارية مشروعة ومستمرة للقيام بذلك، على سبيل المثال لتقديم الخدمات أو المنتجات لك، أو كما هو مطلوب أو مسموح به بموجب القوانين المعمول بها.</span></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">عندما لا تكون لدينا حاجة تجارية مشروعة مستمرة لمعالجة معلوماتك الشخصية، فسنقوم إما بحذفها أو إخفاء هويتها، أو إذا لم يكن ذلك ممكنًا (على سبيل المثال، بسبب تخزين معلوماتك الشخصية في أرشيفات النسخ الاحتياطي)، فسنقوم بتخزين معلوماتك الشخصية وعزلها عن أي معالجة إضافية حتى يصبح الحذف ممكنًا.</span></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">&nbsp;</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">أمن المعلومات</strong></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">نبذل قصارى جهدنا في اتخاذ كافة التدابير الوقائية وتطبيق كافة الإجراءات الفنية والمادية والتنظيمية من أجل حماية بياناتك من أي وصول غير مصرح له أثناء نقل أو تخزين أي من بياناتك الشخصية. ومع ذلك، لا يمكن أن يكون هناك طريقة آمنة بنسبة 100% في نقل البيانات أو تخزينها أو حمايتها لذلك لا يمكننا أن نضمن أمان شبكة الإنترنت لما قد يطرأ على أنظمة الحماية من اختراقات.</span></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">&nbsp;</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">تعديل وتحديث بياناتك الشخصية</strong></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">يمكنك الاطلاع على بياناتك ومراجعة وتصحيح وتحديث وتحرير البيانات التي قدمتها إلينا مسبقًا في أي وقت وذلك عن طريق تسجيل الدخول إلى حسابك ومراجعة إعدادات حسابك وملفك الشخصي وتحديثه. كما يمكنك أيضًا الوصول إلى معلوماتك أو طلب تصحيحها أو طلب تقييد معالجتها عن طريق الاتصال بنا، وفي هذه الحالة قد نحتاج إلى التحقق من هويتك قبل تنفيذ طلبك. بالإضافة إلى أنه يحق لك إلغاء الاشتراك في الرسائل التسويقية عن طريق النقر على رابط (إلغاء الاشتراك) الموجود في رسائل البريد الإلكتروني.</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">&nbsp;</strong></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">تغييرات على سياسة الخصوصية</strong></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">يحتفظ المتجر بحقه في تغيير سياسة الخصوصية هذه من وقت لآخر، وسوف نقوم بنشر سياسة الخصوصية الجديدة والتي تشير إلى تاريخ آخر تعديل أو تحديث؛ لذلك يُرجى مراجعة سياسة الخصوصية هذه بشكل دوري لمعرفة أي تغييرات تطرأ عليها.</span></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">&nbsp;</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">اللغة</strong></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">إذا كان هناك أي تعارض بين النسخة العربية والنسخة الإنجليزية من سياسة الخصوصية فإنه يتم اعتماد اللغة العربية في تفسيرها.</span></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">&nbsp;</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">الموافقة</strong></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">بقراءتك لسياسة الخصوصية هذه فإنك توافق على جمع واستخدام وكشف البيانات الشخصية الخاصة بك وفقًا لما تمّ توضيحه في هذه السياسة، وفي حال عدم موافقتك على ذلك فيرجى عدم تزويدنا بأي من بياناتك الشخصية.</span></p><p class="ql-align-justify ql-direction-rtl"><span style="color: black;">&nbsp;</span></p><p class="ql-align-justify ql-direction-rtl"><strong style="color: black;">تاريخ النشر:&nbsp;</strong></p><p><br></p>',
            'page_desc' => 'سياسة الخصوصية الخاصة بالتاجر',
            'default_page' => 1,
            'user_id' => $user->id,
            'store_id' => $store->id,
        ]);
        $page1->page_categories()->attach(3);
        $page2 = Page::create([
            'title' => 'سياسة الاستبدال والاسترجاع',
            'page_content' => '<p class="ql-align-justify ql-direction-rtl">1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;يمكنك إرجاع أو استبدال المنتج الذي قمت بشرائه من متجرنا خلال <span style="background-color: yellow;">------------</span> يوم من عميلة الشراء.</p><p class="ql-align-justify ql-direction-rtl">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;يجب التأكد من أن يكون المنتج في حالة قابلة لإعادة البيع وفي عبوته الأصلية وأن يتم استرجاعه مع فاتورة الشراء، ويحق للمتجر معاينة المنتج قبل استبداله أو إعادة مبلغه للتأكد من صلاحيته وفي حالة قبوله يتم الاستبدال أو إعادة المبلغ المسترجع.</p><p class="ql-align-justify ql-direction-rtl">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;بعض المنتجات قد لا تكون خاضعة لسياسة الاستبدال والاسترجاع بحسب ما نصت عليه الأنظمة ذات العلاقة والتي تؤثر على صحة وسلامة الإنسان، ومنها على سبيل المثال لا الحصر: الأطعمة المكشوفة، مستحضرات التجميل، المجوهرات، الملابس الداخلية، ملابس السباحة، والأثاث حيث أن هذه المنتجات غير قابلة للإرجاع أو الاستبدال.</p><p class="ql-align-justify ql-direction-rtl">4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;لا يحق للعميل استبدال أو استرجاع قطع التفصيل أو المنتجات التي تكون بحسب الطلب، واستثناء من ذلك في حال كان بها عيب مصنعي على أن يتم إخطار المتجر بذلك خلال ٢٤ من استلام الطلب.</p><p class="ql-align-justify ql-direction-rtl">5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;في حال استلم العميل منتج تالف، فيحق له استبداله بنفس المنتج أو تعويضه قيمة المنتج على أن يتم اخطار المتجر بذلك خلال مدة لا تزيد عن ٢٤ ساعة من استلام الطلب.</p><p class="ql-align-justify ql-direction-rtl">6.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;تتم إعادة المبالغ المسترجعة بعد وصول الشحنة إلى أحد مستودعاتنا حسب طريقة الدفع المختارة من قبل العميل في المتجر الإلكتروني وذلك في فترة قد تتجاوز ٢٠ يوم حسب سياسة بنك العميل أو وسيط الدفع الإلكتروني المستخدم.</p><p><br></p>',
            'page_desc' => 'سياسة الاستبدال والاسترجاع',
            'default_page' => 1,
            'user_id' => $user->id,
            'store_id' => $store->id,
        ]);
        $page2->page_categories()->attach(3);
        $page3 = Page::create([
            'title' => 'اتفاقية الاستخدام',
            'page_content' => '<p class="ql-direction-rtl ql-align-justify"><span style="color: black;">نرحب بك في متجرنا الإلكتروني (</span><strong style="color: black;">"المتجر"</strong><span style="color: black;">) ونود أن نبلغك بأن الشروط والأحكام الواردة في اتفاقية الاستخدام هذه تنظم استخدامك (</span><strong style="color: black;">"أنت"</strong><span style="color: black;">/</span><strong style="color: black;">"المستهلك"</strong><span style="color: black;">) للمتجر وتنص على كافة الآثار القانونية التي تترتب على استخدامك لخدمات المتجر، حيث إنّ أي استخدام لخدمات المتجر يُعد موافقة وقبول لشروط وأحكام هذه الاتفاقية؛ لذلك تعتبر هذه الاتفاقية سارية المفعول ونافذة بمجرد قيامك بالتسجيل أو استخدام خدمات المتجر، قد نقوم بتغيير شروط وأحكام هذه الاتفاقية من وقت لآخر لذلك ننصحك بمراجعة الاتفاقية بشكل دوري لمعرفة التغيرات التي تطرأ عليها.</span></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">استخدامك لخدمات المتجر أو شراءك لأي من منتجاتنا مشروط بقبولك لشروط وأحكام اتفاقية الاستخدام هذه، فإذا كنت لا توافق على أي جزء من الاتفاقية يجب عليك التوقف عن استخدام المتجر. حيث إنّ استمرارك لاستخدام المتجر هو إقرار منك على أنّك قد قرأت وفهمت أحكام وشروط هذه الاتفاقية وقبلتها، وفي حال كان هناك أي جزء غير مفهوم من هذه الاتفاقية، أو أي خدمة مقدّمة على المتجر يرجى التواصل معنا.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">تتم استضافة المتجر على منصة أطلبها وذلك لا يعني أن منصة أطلبها مسؤولة عن المنتجات أو الخدمات التي نقوم بتقديمها حيث إن منصة أطلبها مجرد منصة وساطة تربط بين المتجر والمستهلك والتي بدورها تتيح لنا تقديم خدماتنا ومنتجاتنا إليك.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong>المادة الأولى: شروط المتجر</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;باستخدامك لخدمات المتجر فأنت تقر بأنك مكتسب للأهلية الشرعية والنظامية الكاملة وأن عمرك لا يقل عن 18 عامًا.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;لا يجوز لك استخدام منتجاتنا أو خدماتنا لأي أغراض غير قانونية أو غير مشروعة أو غير مصرح بها بأي شكل من الأشكال وتتحمل وحدك مسؤولية ذلك.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;يجب عليك تسجيل الحساب في المتجر برقم جوال وبريد إلكتروني تابعين لك حتى تتمكن من استلام أي إشعارات مرسلة لك من المتجر، حيث إن المتجر سيقوم باعتماد البريد الإلكتروني ورقم الهاتف الذي قمت بتسجيله لدينا كوسيلة تواصل رسمية ولن يتم التجاوب مع أي مراسلات إلا في حال وردت من البريد الإلكتروني أو رقم الهاتف المسجل على المتجر؛ لذلك يجب عليك التأكد من صحة بياناتك الموجودة على المتجر أو التي قمت بإدخالها عند إتمام عملية الشراء.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;يجب عليك المحافظة على سرية معلومات الحساب بما في ذلك كلمة المرور وألا تقوم بمشاركتها مع أطراف ثالثة.</strong></p><p class="ql-direction-rtl ql-align-justify"><br></p><p class="ql-direction-rtl ql-align-justify"><strong>المادة الثانية: دقة المعلومات المقدمة</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">تقر بأن جميع البيانات والمعلومات المقدمة لنا صحيحة وواضحة وقانونية، وتلتزم بتحديث هذه البيانات في حال تغييرها حيث إن المتجر غير مسؤول في حال كانت المعلومات خاطئة أو غير دقيقة أو غير حقيقية.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong>المادة الثالثة: المنتجات أو الخدمات</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;نحتفظ بحقنا في تعديل المنتجات أو الخدمات، أو إيقافها مؤقتًا أو بشكل دائم وفي أي وقت.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;إن الأسعار المعروضة على المتجر قابلة للتغيير دون الحاجة إلى إشعارك بذلك، ولن نكون مسؤولين تجاهك أو تجاه أي طرف ثالث عن أي تعديل أو تغيير في أسعار المنتجات أو الخدمات أو تعليق أو إيقاف المنتجات أو الخدمات.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong><span style="color: black;">نبذل قصارى جهدنا في توضيح صور وتفاصيل المنتجات، ولكن لا نضمن أي من المنتجات أو الخدمات التي تشتريها من متجرنا من أنها سوف تلبي توقعاتك.</span></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;نحتفظ بحقنا في رفض أي طلب شراء، ويجوز لنا وفقًا لتقديرنا الخاص تحديد أو إلغاء الكميات المشتراة لكل شخص أو أسرة أو طلب. قد تشمل هذه القيود الطلبات المقدمة من قبل أو تحت نفس حساب المستهلك، ونفس بطاقة الائتمان، و/أو الطلبات التي تستخدم نفس عنوان إرسال الفواتير و/أو الشحن.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;تخضع المنتجات و/أو الخدمات المقدمة على المتجر لسياسة الاسترجاع والاستبدال الخاصة بنا </strong><span style="background-color: yellow;">____________________ (يتم وضع رابط سياسة الاسترجاع والاستبدال).</span></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong>المادة الرابعة: التقييمات والتعليقات</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;إذا قمت بمشاركة رأيك على المنتجات أو الخدمات المقدمة على المتجر، سواء بطلب منا أو بدون طلب، فإنك توافق على أن نقوم – في أي وقت وبدون أي قيود – بنسخ، ونشر، وترجمة، واستخدام أي تعليقات ترسلها إلينا بأي وسيلة كانت سواء عبر المتجر أو عبر وسائل التواصل الاجتماعي أو وسائل التواصل التابعة لنا، وتعلم أننا غير ملزمين بالحفاظ على سرية أي تعليق، أو بالرد على أي تعليق، كما يجوز لنا إزالة أي تعليق نرى أنه غير قانوني أو مسيء أو غير مرغوب فيه بأي شكل من الأشكال.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;أنت توافق على أن تعليقاتك لن تنتهك أي حق لأي طرف ثالث، بما في ذلك حقوق الطبع والنشر، أو العلامة التجارية، أو الخصوصية، أو الشخصية، أو غيرها من الحقوق الشخصية أو حقوق الملكية. أنت توافق أيضًا على أن تعليقاتك لن تحتوي على مواد تشهيرية، أو غير قانونية، أو مسيئة، أو فاحشة، أو تحتوي على أي فيروسات كمبيوتر، أو برامج ضارة أخرى يمكن أن تؤثر بأي شكل من الأشكال على تشغيل الخدمة أو أي موقع ويب ذي صلة.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;لا يجوز لك استخدام عنوان بريد إلكتروني مزيف، أو التظاهر بأنك شخص آخر، أو تضليلنا أو تضليل الأطراف الثالثة فيما يتعلق بأصل أي تعليقات، وأنت وحدك المسؤول عن أي تعليقات تقوم بها وعن دقتها، ولا نتحمل أي مسؤولية عن أي تعليقات تنشرها أنت أو أي طرف ثالث.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong>&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong>المادة الخامسة: بياناتك الشخصية</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">جميع بياناتك الشخصية المقدمة عبر المتجر تخضع لسياسة الخصوصية الخاصة بنا، والتي يمكن الاطلاع عليها هنا: </strong><span style="background-color: yellow;">____________________ (يتم وضع رابط سياسة الخصوصية).</span></p><p class="ql-direction-rtl ql-align-justify"><br></p><p class="ql-direction-rtl ql-align-justify"><strong>المادة السادسة: الأخطاء وعدم دقة المعلومات</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">في بعض الأحيان قد تكون هناك معلومات تتعلق بأوصاف المنتج، أو الأسعار، أو العروض الترويجية، أو رسوم التوصيل، أو غيرها من المعلومات الموجودة على المتجر والتي قد تتضمن أخطاء مطبعية، أو تكون خاطئة، أو غير دقيقة. لذلك نحتفظ بحقنا في تصحيح الخطأ، وللمستهلك الخيار بين الاستمرار في الطلب بعد التصحيح أو إلغاء الطلب.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong>المادة السابعة: الاستخدامات المحظورة</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">بالإضافة إلى المحظورات الأخرى المنصوص عليها في اتفاقية الاستخدام، يُحظر عليك استخدام المتجر أو محتواه: ١) لأي غرض غير قانوني؛ ٢) حث الآخرين على المشاركة في أي أعمال غير قانونية؛ ٣) انتهاك أي لوائح أو قواعد أو قوانين أو أنظمة محلية أو دولية؛ ٣) انتهاك حقوق الملكية الفكرية الخاصة بنا أو حقوق الملكية الفكرية الخاصة بالغير؛ ٤) المضايقة أو الإساءة أو الإهانة أو الأذى أو التشهير أو التخويف أو التمييز؛ ٥) تقديم معلومات كاذبة أو مضللة؛ ٦) تحميل أو نقل الفيروسات أو أي نوع آخر من التعليمات البرمجية الضارة التي يتم استخدامها بأي طريقة من شأنها أن تؤثر على وظيفة أو تشغيل الخدمة أو أي موقع ويب ذي صلة أو مواقع ويب أخرى؛ ٧) جمع أو تتبع المعلومات الشخصية للآخرين.</strong></p><p class="ql-direction-rtl ql-align-justify"><br></p><p class="ql-direction-rtl ql-align-justify"><strong>المادة الثامنة: مسؤولية المتجر</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">المتجر لا يتحمل أي مطالبات تنشأ عن أخطاء أو إهمال ناتج عن المستهلك سواء كان ذلك بشكل مباشر أو غير مباشر أو عن طريق أي طرف ثالث كشركات الشحن وشركات تقديم الخدمات اللوجستية.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong>المادة التاسعة: الملكية الفكرية</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;يجب على المستهلك احترام حقوق الملكية الفكرية الخاصة بالمتجر وهي على سبيل المثال لا الحصر: المتجر، والكلمات، والشعارات، والصور، والتصاميم، والفيديوهات، والأصوات، والأيقونات المعروضة على المتجر.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;المتجر وكل حق يتبعه هي حقوق محمية بموجب أنظمة الملكية الفكرية والعلامات التجارية، وتُعدّ ملكية خاصة بالمتجر ولتابعيها وللجهات المرخص لها ولا يحق بأي حال من الأحوال التعدي عليها أو استخدامها دون تفويض من المتجر.</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong>المادة العاشرة: تلقي الشكاوى</strong></p><p class="ql-direction-rtl ql-align-justify"><span style="color: black;">في حال واجهتك مشكلة أو ترغب في تقديم شكوى بإمكانك التواصل معنا عن طريق وسائل التواصل المتاحة عبر المتجر.</span></p><p class="ql-direction-rtl ql-align-justify"><strong>&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong>&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong>المادة الحادية عشر: القانون واجب التطبيق</strong></p><p class="ql-direction-rtl ql-align-justify"><span style="color: black;">تخضع هذه الاتفاقية إلى جميع الأنظمة واللوائح ذات العلاقة والمعمول بها في المملكة العربية السعودية.</span></p><p class="ql-direction-rtl ql-align-justify">&nbsp;</p><p class="ql-direction-rtl ql-align-justify"><strong>المادة الثانية عشر: أحكام عامة:</strong></p><p class="ql-direction-rtl">1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;في حال إلغاء أي بند من بنود اتفاقية الاستخدام هذه، فإنّ هذا لا يلغي صلاحية باقي البنود الواردة في اتفاقية الاستخدام وتظل سارية حتى إشعار آخر.</p><p class="ql-direction-rtl">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;نحتفظ بحقنا في تحديث أو تغيير أو استبدال أي جزء من اتفاقية الاستخدام هذه من وقت لآخر عن طريق نشر التحديثات و/أو التغييرات على متجرنا، ويقع على عاتقك مسؤولية مراجعة هذه الصفحة بشكل دوري لمعرفة أي تغيير يطرأ عليها.</p><p class="ql-direction-rtl">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;إذا تمت ترجمة اتفاقية الاستخدام هذه لأي لغة أخرى، فإنّ اللغة العربية هي اللغة المعمول بها في كافة التعاملات.</p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">&nbsp;</strong></p><p class="ql-direction-rtl ql-align-justify"><strong style="color: black;">تاريخ النشر:&nbsp;</strong></p><p class="ql-align-justify">&nbsp;</p><p><br></p>',
            'page_desc' => 'اتفاقية الاستخدام الخاصة بالتاجر',
            'default_page' => 1,
            'user_id' => $user->id,
            'store_id' => $store->id,
        ]);
        $page3->page_categories()->attach(3);
        // add shipping type as defulte
        $shipping_type = shippingtype_store::create([
            'store_id' => $store->id,
            'shippingtype_id' => 5,
            'price' => 20,
            'time' => 2,
        ]);
        $payment_type = paymenttype_store::create([
            'store_id' => $store->id,
            'paymentype_id' => 4,

        ]);

        if ($request->periodtype == "6months") {
            $end_at = date('Y-m-d', strtotime("+ 6 months", strtotime($store->created_at)));
            $store->update([
                'start_at' => $store->created_at,
                'end_at' => $end_at]);

        } elseif ($request->periodtype == "year") {
            $end_at = date('Y-m-d', strtotime("+ 1 years", strtotime($store->created_at)));
            $store->update([
                'start_at' => $store->created_at,
                'end_at' => $end_at]);
        } else {
            $end_at = date('Y-m-d', strtotime("+ 3 months", strtotime($store->created_at)));
            $store->update([
                'start_at' => $store->created_at,
                'end_at' => $end_at]);

        }
        // if($request->package_id ==1){
        //   $end_at = date('Y-m-d', strtotime("+ 2 weeks", strtotime($store->created_at)));
        //   $store->update([
        //       'start_at' => $store->created_at,
        //       'end_at' => $end_at]);

        //  }
        // $store->activities()->attach($request->activity_id);
        if ($request->subcategory_id != null) {
            $subcategory = implode(',', $request->subcategory_id);
        } else {
            $subcategory = null;
        }

        $store->categories()->attach($request->activity_id, ['subcategory_id' => $subcategory]);
        $store->packages()->attach($request->package_id, ['start_at' => $store->created_at, 'end_at' => $end_at, 'periodtype' => $request->periodtype, 'packagecoupon_id' => $request->packagecoupon]);

        $success['stores'] = new StoreResource($store);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة متجر بنجاح', ' store Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show($store)
    {
        $store = Store::query()->find($store);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجودة", "store is't exists");
        }

        $success['stores'] = new StoreResource($store);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض المتجر  بنجاح', 'store showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $store)
    {

        $store = Store::query()->find($store);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجود", "store is't exists");
        }
        $user = $store->user;
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'user_name' => ['required', 'string', Rule::unique('users')->where(function ($query) use ($store) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0)
                    ->where('id', '!=', $store->user->id);
            })],
            'store_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) use ($store) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0)
                    ->where('id', '!=', $store->user->id);
            })],
            'store_email' => 'required|email|unique:stores,store_email,' . $store->id,
            'password' => 'required|min:8|string',
            'domain' => ['required', 'string', Rule::unique('stores')->where(function ($query) use ($store) {
                return $query->where('is_deleted', 0)->where('id', '!=', $store->id);
            })],
            'userphonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users', 'phonenumber')->where(function ($query) use ($store) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0)
                    ->where('id', '!=', $store->user->id);
            })],
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', 'unique:stores,phonenumber,' . $store->id],
            // 'package_id' =>'required',
            'activity_id' => 'required|array',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'user_country_id' => 'required|exists:countries,id',
            'user_city_id' => 'required|exists:cities,id',
            'periodtype' => 'required|in:6months,year',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $request->package_id = 1;
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'user_name' => $request->input('user_name'),
            'password' => $request->input('password'),
            'phonenumber' => $request->input('phonenumber'),
            'image' => $request->input('image'),
            'country_id' => $request->input('user_country_id'),
            'city_id' => $request->input('user_city_id'),
        ]);

        $store->update([
            'store_name' => $request->input('store_name'),
            'store_email' => $request->input('store_email'),
            'domain' => $request->input('domain'),
            'icon' => $request->input('icon'),
            'description' => $request->input('description'),
            'business_license' => $request->input('business_license'),
            'ID_file' => $request->input('ID_file'),
            'snapchat' => $request->input('snapchat'),
            'facebook' => $request->input('facebook'),
            'snapchat' => $request->input('snapchat'),
            'twiter' => $request->input('twiter'),
            'youtube' => $request->input('youtube'),
            'instegram' => $request->input('instegram'),
            'logo' => $request->input('logo'),
            'entity_type' => $request->input('entity_type'),
            'package_id' => $request->input('package_id'),
            'country_id' => $request->input('country_id'),
            'city_id' => $request->input('city_id'),
            'periodtype' => $request->input('periodtype'),
        ]);
        // $store->activities()->sync($request->activity_id);
        $store->categories()->sync($request->activity_id);

        if ($request->periodtype == "6months") {
            $end_at = date('Y-m-d', strtotime("+ 6 months", strtotime($store->created_at)));

            $store->update([
                'start_at' => $store->created_at,
                'end_at' => $end_at]);

        } else {
            $end_at = date('Y-m-d', strtotime("+1 years", strtotime($store->created_at)));
            $store->update([
                'start_at' => $store->created_at,
                'end_at' => $end_at]);
        }
        $store->packages()->sync($request->package_id, ['start_at' => $store->created_at, 'end_at' => $end_at, 'periodtype' => $request->periodtype, 'packagecoupon_id' => $request->packagecoupon]);

        $success['stores'] = new StoreResource($store);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'store updated successfully');
    }

    public function updateProfile(Request $request, $store)
    {

        $store = Store::query()->find($store);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجود", "store is't exists");
        }
        $storeAdmain = User::where('user_type', 'store')->where('store_id', $store->id)->first();
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_name' => ['required', 'string', Rule::unique('users')->where(function ($query) use ($storeAdmain) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0)
                    ->where('id', '!=', $storeAdmain->id);
            })],
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) use ($storeAdmain) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted', 0)
                    ->where('id', '!=', $storeAdmain->id);
            })],
            'password' => 'required|min:8|string',

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $storeAdmain->update([
            'email' => $request->input('email'),
            'user_name' => $request->input('user_name'),
            'password' => $request->input('password'),
        ]);

        $success['user'] = new UserResource($storeAdmain);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'user updated successfully');
    }

    public function changeStatus(Request $request)
    {

        $stores = Store::whereIn('id', $request->id)->get();
        foreach ($stores as $store) {
            if (is_null($store) || $store->is_deleted != 0) {
                return $this->sendError("المتجر غير موجود", " Store is't exists");
            }

            if ($store->status === 'active') {
                $store->update(['status' => 'not_active']);
            } else {
                $store->update(['status' => 'active']);
            }

        }

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة المتجر بنجاح', 'store updated successfully');

    }

    public function specialStatus($id)
    {
        $store = Store::query()->find($id);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجود", "store is't exists");
        }

        if ($store->special === 'not_special') {
            $store->update(['special' => 'special']);
        } else {
            $store->update(['special' => 'not_special']);
        }
        $success['store'] = new StoreResource($store);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة المتجر بنجاح', 'store updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */

    public function changeSatusall(Request $request)
    {

        $stores = Store::whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($stores) > 0) {
            foreach ($stores as $store) {
                if ($store->status === 'active') {
                    $store->update(['status' => 'not_active']);
                    $users = User::where('store_id', $store->id)->get();
                    if ($users !== null) {
                        foreach ($users as $user) {
                            $user->update(['status' => 'not_active']);
                        }
                    }
                } else {
                    $store->update(['status' => 'active']);
                    $users = User::where('store_id', $store->id)->get();
                    if ($users !== null) {
                        foreach ($users as $user) {
                            $user->update(['status' => 'active']);
                            $data1 = [
                                'subject' => "قبول التسجيل",
                                'message' => "تم قبول التسجيل",
                                'store_id' => $store->id,
                            ];
                            //              try {
                            //  Mail::to($user->email)->send(new SendMail($data1));
                            //  } catch (Exception $e) {
                            //   return $this->sendError('صيغة البريد الالكتروني غير صحيحة', 'The email format is incorrect.');
                            //           }
                        }
                    }
                }
                $success['stores'] = new StoreResource($store);

            }
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم تعديل الحالة بنجاح', 'store updated successfully');

        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
    }

    public function destroy($store)
    {
        $store = Store::query()->find($store);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجود", "store is't exists");
        }
        $store->update(['is_deleted' => $store->id]);
        $users = User::where('store_id', $store->id)->get();
        foreach ($users as $user) {
            $user->update(['is_deleted' => $user->id]);
            $comment = Comment::where('comment_for', 'store')->where('user_id', $user->id)->where('is_deleted', 0)->first();
            if ($comment != null) {
                $comment->update(['is_deleted' => $comment->id]);
            }
        }
        $products = Product::where('store_id', $store->id)->get();
        foreach ($products as $product) {
            $product->update(['is_deleted' => $product->id]);
        }

        $success['store'] = new StoreResource($store);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف المتجر بنجاح', 'store deleted successfully');
    }



  
    public function deleteall(Request $request)
    {

        $stores = Store::whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($stores) > 0) {
            foreach ($stores as $store) {

                $store->update(['is_deleted' => $store->id]);
                $users = User::where('store_id', $store->id)->get();
                foreach ($users as $user) {
                    $user->update(['is_deleted' => $user->id]);
                }
            }
            $success['stores'] = StoreResource::collection($stores);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف المتجر بنجاح', 'store deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }

    }
//
    public function unVerificationStore()
    {
        $stores = Store::with(['categories' => function ($query) {
            $query->select('name', 'icon');
        }, 'city' => function ($query) {
            $query->select('id');
        }, 'country' => function ($query) {
            $query->select('id');

        }])->where('is_deleted', 0)->where('verification_status', 'pending')->orderByDesc('updated_at')->get();
        $success['stores'] = VerificationResource::collection($stores);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المتاجر بنجاح', 'Stores return successfully');
    }

    public function addNote(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'subject' => 'required|string|max:255',
            'details' => 'required|string',
            'store_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $note = Note::create([
            'subject' => $request->subject,
            'details' => $request->details,
            'store_id' => $request->store_id,
            'product_id' => null,
        ]);
        $store = Store::query()->find($request->store_id);
        $data = [
            'message' => $request->details,
            'store_id' => $store->id,
            'user_id' => $store->user_id,
            'type' => $request->subject,
            'object_id' => null,
        ];
        $user = User::where('user_type', 'store')->where('store_id', $store->id)->first();
  
        try {
            Mail::to($user->email)->send(new SendMail($data));

        } catch (\Exception $e) {
       
            $errorMessage = 'Failed to send email. Please try again later.';
            Log::error('Email delivery failure: ' . $e->getMessage());
        }
        $success['notes'] = new NoteResource($note);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة ملاحظة بنجاح', 'note Added successfully');
    }

}
