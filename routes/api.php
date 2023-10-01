<?php

use App\Http\Controllers\api\storeDashboard\ReportController;
use App\Http\Controllers\api\storeDashboard\SubscriptionEmailController;
use App\Http\Middleware\SetActiveStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

//  test sms
Route::post('/send', 'App\Http\Controllers\api\SmsController@smsSend');
Route::get('sendMessage', 'App\Http\Controllers\api\AuthController@store_verify_message');
//Route::post('sendMessagePost', 'App\Http\Controllers\api\AuthController@sendMessagePost');

Route::get('selector/cities', [App\Http\Controllers\api\SelectorController::class, 'cities']);
Route::get('selector/countries', [App\Http\Controllers\api\SelectorController::class, 'countries']);
Route::get('selector/activities', [App\Http\Controllers\api\SelectorController::class, 'activities']);
Route::get('selector/packages', [App\Http\Controllers\api\SelectorController::class, 'packages']);
Route::get('selector/addToCart', [App\Http\Controllers\api\SelectorController::class, 'addToCart']);

Route::post('/social-mobile', 'App\Http\Controllers\api\AuthController@social_mobile');

Route::post('/loginapi', 'App\Http\Controllers\api\AuthController@login');
Route::post('/loginadminapi', 'App\Http\Controllers\api\AuthController@login_admin');
// Route::post('/logincustomerapi','App\Http\Controllers\api\AuthController@login_customer');
Route::post('/registerapi', 'App\Http\Controllers\api\AuthController@register');
Route::get('/logout', 'App\Http\Controllers\api\AuthController@logout');
// login template
Route::post('/logincustomerphoneapi', 'App\Http\Controllers\api\AuthCustomerController@login_customer');
Route::post('/logincustomeremailapi', 'App\Http\Controllers\api\AuthCustomerController@login_customer_email');
Route::post('/registerUser/{id}', 'App\Http\Controllers\api\AuthCustomerController@registerUser');
Route::post('/verifyUser', 'App\Http\Controllers\api\AuthCustomerController@verifyUser');

//  index Ettlobha page

Route::get('index', [App\Http\Controllers\api\IndexEtlobhaController::class, 'index']);
Route::post('atlobhaContactAdd', [App\Http\Controllers\api\IndexEtlobhaController::class, 'store']);
//  index store page القالب
Route::middleware([SetActiveStore::class])->group(function () {
    Route::get('indexStore/{id}', [App\Http\Controllers\api\IndexStoreController::class, 'index']);
    Route::get('productPage/{domain}/{id}', [App\Http\Controllers\api\IndexStoreController::class, 'productPage']);
    Route::get('storPage/{id}', [App\Http\Controllers\api\IndexStoreController::class, 'storPage']);
    Route::get('category/{id}', [App\Http\Controllers\api\IndexStoreController::class, 'category']);
    Route::get('storeProductCategory', [App\Http\Controllers\api\IndexStoreController::class, 'storeProductCategory']);
    Route::get('productSearch', [App\Http\Controllers\api\IndexStoreController::class, 'productSearch']);
    Route::get('profileCustomer', [App\Http\Controllers\api\ProfileCustomerController::class, 'index']);
    Route::post('profileCustomer', [App\Http\Controllers\api\ProfileCustomerController::class, 'update']);
    Route::post('addSubsicription/{domain}', [App\Http\Controllers\api\IndexStoreController::class, 'addSubsicription']);

});

Route::middleware([SetActiveStore::class])->group(function () {
    Route::get('indexStore', [App\Http\Controllers\api\IndexStoreController2::class, 'index']);
});

Route::get('cartShow/{id}', [App\Http\Controllers\api\CartTemplateController::class, 'show']);
Route::post('addCart/{domain}', [App\Http\Controllers\api\CartTemplateController::class, 'addToCart']);
Route::get('deleteCart/{domain}/{id}', [App\Http\Controllers\api\CartTemplateController::class, 'delete']);

// Route::get('productPage/{slug}',[App\Http\Controllers\api\IndexStoreController::class,'productPage']);
// المدونه
Route::get('postStore/{id}', [App\Http\Controllers\api\PostStoreController::class, 'index']);
Route::get('postByCategory/{id}', [App\Http\Controllers\api\PostStoreController::class, 'show']);
Route::get('postdetail/{id}', [App\Http\Controllers\api\PostStoreController::class, 'show_post']);

Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::post('addComment/{id}', [App\Http\Controllers\api\IndexStoreController::class, 'addComment']);
    Route::post('addContact', [App\Http\Controllers\api\IndexStoreController::class, 'addContact']);
});
// visit count
Route::post('storeClientVisit', [App\Http\Controllers\api\VisitCountController::class, 'storeClientVisit']);
//
Route::get('visit', [App\Http\Controllers\api\VisitCountController::class, 'visit']);

Route::get('posts', [App\Http\Controllers\api\PostController::class, 'index']);
Route::get('show/{id}', [App\Http\Controllers\api\PostController::class, 'show']);
Route::get('show_post/{id}', [App\Http\Controllers\api\PostController::class, 'show_post']);

Route::post('send-verify-message', 'App\Http\Controllers\api\AuthController@store_verify_message');
Route::post('verify-user', 'App\Http\Controllers\api\AuthController@verifyUser');

Route::get('page/{id}', [App\Http\Controllers\api\SubpageController::class, "show"]);
Route::get('packages', [App\Http\Controllers\api\SubpageController::class, "packages"]);
Route::post('showVideoDuration', [App\Http\Controllers\api\VideoController::class, "showVideo"]);

Route::get('profile', [App\Http\Controllers\api\ProfileController::class, 'index']);
Route::post('profile', [App\Http\Controllers\api\ProfileController::class, 'update']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'password',
], function () {
    Route::post('create', 'App\Http\Controllers\api\PasswordResetController@create');
    Route::post('create-by-email', 'App\Http\Controllers\api\PasswordResetController@create_by_email');
    Route::get('find/{token}', 'App\Http\Controllers\api\PasswordResetController@find');
    Route::post('verify', 'App\Http\Controllers\api\PasswordResetController@verifyContact');
    Route::post('reset-password', 'App\Http\Controllers\api\PasswordResetController@reset');
});
// ,AdminCheckPermission::class
// change status routers
Route::middleware([AdminUser::class])->group(function () {
    Route::prefix('/Admin')->group(function () {
        
        // Route::post('payment', [App\Http\Controllers\api\adminDashboard\PaymentController::class, 'payment']);
        // Route::get('callback', [App\Http\Controllers\api\adminDashboard\PaymentController::class, 'callback'])->name('callback');
        // Route::post('updateCharge/{id}', [App\Http\Controllers\api\adminDashboard\PaymentController::class, 'updateCharge']);
        // Route::get('list', [App\Http\Controllers\api\adminDashboard\PaymentController::class, 'list'])->name('list');

        Route::get('selector/etlobahCategory', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'etlobahCategory']);
        Route::get('selector/years', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'years']);
        Route::get('selector/cities', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'cities']);
        Route::get('selector/countries', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'countries']);
        Route::get('selector/activities', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'activities']);
        Route::get('selector/packages', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'packages']);
        Route::get('selector/plans', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'plans']);
        Route::get('selector/templates', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'templates']);
        Route::get('selector/course/units/{id}', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'units']);
        Route::get('selector/page-categories', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'page_categories']);
        Route::get('selector/post-categories', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'post_categories']);
        Route::get('selector/roles', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'roles']);
        Route::get('selector/subcategories', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'subcategories']);

//Route::get('profile',[App\Http\Controllers\api\adminDashboard\ProfileController::class,'index']);
        Route::resource('pagecategory', App\Http\Controllers\api\adminDashboard\PageCategoryController::class);
        Route::get('profile', [App\Http\Controllers\api\adminDashboard\ProfileController::class, 'index']);
        Route::post('profile', [App\Http\Controllers\api\adminDashboard\ProfileController::class, 'update']);
        // Route::middleware([AdminCheckPermission::class])->group(function () {
        // Route::get('changeCountryStatus/{id}', [App\Http\Controllers\api\adminDashboard\CountryController::class, 'changeStatus']);
        // Route::get('changeCityStatus/{id}', [App\Http\Controllers\api\adminDashboard\CityController::class, 'changeStatus']);
        // Route::get('changeMarketerStatus/{id}', [App\Http\Controllers\api\adminDashboard\MarketerController::class, 'changeStatus']);
        // Route::get('changeExplainVideosStatus/{id}', [App\Http\Controllers\api\adminDashboard\ExplainVideosController::class, 'changeStatus']);
//Route::get('changeCourseStatus/{id}', [App\Http\Controllers\api\adminDashboard\CourseController::class,'changeStatus']);
        // Route::get('changeUnitStatus/{id}', [App\Http\Controllers\api\adminDashboard\UnitController::class, 'changeStatus']);
        // Route::get('changeVideoStatus/{id}', [App\Http\Controllers\api\adminDashboard\VideoController::class, 'changeStatus']);
        // Route::get('changeActivityStatus/{id}', [App\Http\Controllers\api\adminDashboard\ActivityController::class, 'changeStatus']);
        Route::get('changePlatformStatus/{id}', [App\Http\Controllers\api\adminDashboard\PlatformController::class, 'changeStatus'])->name('admin.platform.changePlatformStatus');
//Route::get('changeServiceStatus/{id}', [App\Http\Controllers\api\adminDashboard\ServiceController::class,'changeStatus']);
        // Route::get('changeCategoryStatus/{id}', [App\Http\Controllers\api\adminDashboard\CategoryController::class, 'changeStatus']);
        // Route::get('changeStoreCategoryStatus/{id}', [App\Http\Controllers\api\adminDashboard\CategoryController::class, 'changeStatus']);
        Route::get('changeShippingtypeStatus/{id}', [App\Http\Controllers\api\adminDashboard\ShippingtypeController::class, 'changeStatus'])->name('admin.shippingtype.changeStatus');
        Route::get('changePaymenttypeStatus/{id}', [App\Http\Controllers\api\adminDashboard\PaymenttypeController::class, 'changeStatus'])->name('admin.paymenttype.changeStatus');
        // Route::get('changeCommentStatus/{id}', [App\Http\Controllers\api\adminDashboard\CommentController::class, 'changeStatus']);
        // Route::get('changeReplaycommentStatus/{id}', [App\Http\Controllers\api\adminDashboard\ReplaycommentController::class, 'changeStatus']);
        // Route::get('changeMaintenanceStatus/{id}', [App\Http\Controllers\api\adminDashboard\MaintenanceController::class, 'changeStatus']);
        // Route::get('changePageStatus/{id}', [App\Http\Controllers\api\adminDashboard\PageController::class, 'changeStatus']);
        // Route::get('changePageCategoryStatus/{id}', [App\Http\Controllers\api\adminDashboard\PageCategoryController::class, 'changeStatus']);
        // Route::get('changeTechnicalSupportStatus/{id}', [App\Http\Controllers\api\adminDashboard\TechnicalSupportController::class, 'changeStatus']);
        // Route::get('changeCurrencyStatus/{id}', [App\Http\Controllers\api\adminDashboard\CurrencyController::class, 'changeStatus']);
        Route::get('changewebsite_socialmediaStatus/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteSocialmediaController::class, 'changeStatus'])->name('admin.websitesocialmedia.changeStatus');
        // Route::get('changeHomepageStatus/{id}', [App\Http\Controllers\api\adminDashboard\HomepageController::class, 'changeStatus']);
        // Route::get('changePlaneStatus/{id}', [App\Http\Controllers\api\adminDashboard\PlanController::class, 'changeStatus']);
        // Route::get('changePackageStatus/{id}', [App\Http\Controllers\api\adminDashboard\PackageController::class, 'changeStatus']);
        // Route::get('changeTemplateStatus/{id}', [App\Http\Controllers\api\adminDashboard\TemplateController::class, 'changeStatus']);
        // Route::get('changeCouponStatus/{id}', [App\Http\Controllers\api\adminDashboard\CouponController::class, 'changeStatus']);
        // Route::get('changePackagecouponStatus/{id}', [App\Http\Controllers\api\adminDashboard\PackagecouponController::class, 'changeStatus']);
        Route::get('changeSectionStatus/{id}', [App\Http\Controllers\api\adminDashboard\SectionController::class, 'changeStatus'])->name('admin.section.changestatus');
        // Route::get('changeSettingStatus/{id}', [App\Http\Controllers\api\adminDashboard\SettingController::class, 'changeStatus']);
        // Route::get('changeReplaycontactStatus/{id}', [App\Http\Controllers\api\adminDashboard\ReplaycontactController::class, 'changeStatus']);
        // Route::get('changeContactStatus/{id}', [App\Http\Controllers\api\adminDashboard\ContactController::class, 'changeStatus']);
        // Route::get('changeSeoStatus/{id}', [App\Http\Controllers\api\adminDashboard\SeoController::class, 'changeStatus']);
        // Route::get('changeSettingStatus/{id}', [App\Http\Controllers\api\adminDashboard\SettingController::class, 'changeStatus']);
        Route::get('changeStoreStatus', [App\Http\Controllers\api\adminDashboard\StoreController::class, 'changeSatusall'])->name('admin.store.changeSatusall');
        // Route::get('changeOfferStatus/{id}', [App\Http\Controllers\api\adminDashboard\OfferController::class, 'changeStatus']);
        // Route::get('changeProductStatus/{id}', [App\Http\Controllers\api\adminDashboard\ProductController::class, 'changeStatus']);
        // Route::get('changeOptionStatus/{id}', [App\Http\Controllers\api\adminDashboard\OptionController::class, 'changeStatus']);
        // Route::get('changeHomeStatus/{name}/{id}', [App\Http\Controllers\api\adminDashboard\HomepageController::class, 'changeHomeStatus']);
        // Route::get('changeWebsiteorderStatus/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteorderController::class, 'changeStatus']);
        // Route::get('changeclientStatus/{id}', [App\Http\Controllers\api\adminDashboard\ClientController::class, 'changeStatus']);
        // Route::get('changeuserStatus/{id}', [App\Http\Controllers\api\adminDashboard\UserController::class, 'changeStatus']);

// home page
        Route::post('logoUpdate', [App\Http\Controllers\api\adminDashboard\HomepageController::class, 'logoUpdate'])->name('admin.homepage.logoUpdate');
        Route::post('banarUpdate', [App\Http\Controllers\api\adminDashboard\HomepageController::class, 'banarUpdate'])->name('admin.homepage.banarUpdate');
        Route::post('sliderUpdate', [App\Http\Controllers\api\adminDashboard\HomepageController::class, 'sliderUpdate'])->name('admin.homepage.sliderUpdate');
        
        Route::resource('country', App\Http\Controllers\api\adminDashboard\CountryController::class,['names' => 'admin.country']);
        Route::resource('city', App\Http\Controllers\api\adminDashboard\CityController::class,['names' => 'admin.city']);
        Route::resource('marketer', App\Http\Controllers\api\adminDashboard\MarketerController::class,['names' => 'admin.marketer']);
        // Route::resource('client', App\Http\Controllers\api\adminDashboard\ClientController::class);
        Route::resource('explainVideos', App\Http\Controllers\api\adminDashboard\ExplainVideosController::class,['names' => 'admin.explainvideo']);
        Route::resource('course', App\Http\Controllers\api\adminDashboard\CourseController::class,['names' => 'admin.course']);
        // Route::resource('unit', App\Http\Controllers\api\adminDashboard\UnitController::class);
        // Route::resource('video', App\Http\Controllers\api\adminDashboard\VideoController::class);
        Route::resource('activity', App\Http\Controllers\api\adminDashboard\ActivityController::class,['names' => 'admin.activity']);
        Route::resource('platform', App\Http\Controllers\api\adminDashboard\PlatformController::class,['names' => 'admin.platform']);
        Route::resource('service', App\Http\Controllers\api\adminDashboard\ServiceController::class ,['names' => 'admin.service']);
        Route::get('service/showDetail/{id}', [App\Http\Controllers\api\adminDashboard\ServiceController::class, 'showDetail'])->name('admin.service.showdetail');

        Route::get('servicedeleteall', [App\Http\Controllers\api\adminDashboard\ServiceController::class, 'deleteall'])->name('admin.service.deleteall');

        Route::get('NotificationIndex', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'index'])->name('admin.notification.index');
        Route::get('NotificationRead/{id}', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'read'])->name('admin.notification.read');
        // Route::get('NotificationDelete/{id}', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'deleteNotification']);
        Route::get('NotificationDeleteAll', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'deleteNotificationAll'])->name('admin.notification.deleteall');
        Route::get('NotificationShow/{id}', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'show'])->name('admin.notification.show');
        Route::post('addEmail', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'addEmail'])->name('admin.email.store');
        // Route::post('addAlert', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'addAlert']);

        Route::get('EmailIndex', [App\Http\Controllers\api\adminDashboard\EmailController::class, 'index'])->name('admin.email.index');
        // Route::get('EmailDelete/{id}', [App\Http\Controllers\api\adminDashboard\EmailController::class, 'deleteEmail']);
        Route::get('EmailDeleteAll', [App\Http\Controllers\api\adminDashboard\EmailController::class, 'deleteEmailAll'])->name('admin.email.deleteEmailAll');
        Route::get('EmailShow/{id}', [App\Http\Controllers\api\adminDashboard\EmailController::class, 'show'])->name('admin.email.show');
        // Route::post('addEmail', [App\Http\Controllers\api\adminDashboard\EmailController::class, 'addEmail']);

        Route::resource('category', App\Http\Controllers\api\adminDashboard\CategoryController::class,['names' => 'admin.category']);
        Route::resource('storecategory', App\Http\Controllers\api\adminDashboard\StoreCategoryController::class,['names' => 'admin.storecategory']);
        Route::post('addvideo', [App\Http\Controllers\api\adminDashboard\CourseController::class, 'addvideo'])->name('admin.course.addvideo');
        Route::get('deletevideo/{id}', [App\Http\Controllers\api\adminDashboard\CourseController::class, 'deletevideo'])->name('admin.course.deletevideo');
        Route::get('deleteunit/{id}', [App\Http\Controllers\api\adminDashboard\CourseController::class, 'deleteunit'])->name('admin.course.deleteunit');

        Route::resource('course', App\Http\Controllers\api\adminDashboard\CourseController::class,['names' => 'admin.course']);

        // Route::resource('category', App\Http\Controllers\api\adminDashboard\CategoryController::class,['names' => 'admin.category']);
        // Route::resource('storecategory', App\Http\Controllers\api\adminDashboard\StoreCategoryController::class);
        // Route::post('addvideo', [App\Http\Controllers\api\adminDashboard\CourseController::class, 'addvideo']);
        // Route::get('deletevideo/{id}', [App\Http\Controllers\api\adminDashboard\CourseController::class, 'deletevideo']);

        // Route::resource('course', App\Http\Controllers\api\adminDashboard\CourseController::class);

        Route::resource('shippingtype', App\Http\Controllers\api\adminDashboard\ShippingtypeController::class,['names' => 'admin.shippingtype']);
        Route::resource('paymenttype', App\Http\Controllers\api\adminDashboard\PaymenttypeController::class,['names' => 'admin.paymenttype']);
        Route::resource('comment', App\Http\Controllers\api\adminDashboard\CommentController::class,['names' => 'admin.comment']);
        Route::get('commentchangeSatusall', [App\Http\Controllers\api\adminDashboard\CommentController::class, 'changeSatusall'])->name('admin.comment.changeSatusall');
        Route::get('commentdeleteall', [App\Http\Controllers\api\adminDashboard\CommentController::class, 'deleteall'])->name('admin.comment.deleteall');

        Route::resource('page', App\Http\Controllers\api\adminDashboard\PageController::class,['names' => 'admin.page']);
//Route::get('relatedPage/{id}',[App\Http\Controllers\api\adminDashboard\PageController::class,"relatedPage"]);
        Route::post('page-publish', [App\Http\Controllers\api\adminDashboard\PageController::class, 'publish'])->name('admin.page.publish');

        Route::resource('technicalSupport', App\Http\Controllers\api\adminDashboard\TechnicalSupportController::class,['names' => 'admin.technicalsupport']);
        Route::resource('currency', App\Http\Controllers\api\adminDashboard\CurrencyController::class,['names' => 'admin.currency']);
        Route::resource('website_socialmedia', App\Http\Controllers\api\adminDashboard\WebsiteSocialmediaController::class,['names' => 'admin.websitesocialmedia']);
        Route::resource('homepage', App\Http\Controllers\api\adminDashboard\HomepageController::class,['names' => 'admin.homepage']);
        // Route::resource('plan', App\Http\Controllers\api\adminDashboard\PlanController::class);
        // Route::resource('package', App\Http\Controllers\api\adminDashboard\PackageController::class);
        // Route::resource('template', App\Http\Controllers\api\adminDashboard\TemplateController::class);
        Route::resource('coupons', App\Http\Controllers\api\adminDashboard\CouponController::class,['names' => 'admin.coupon']);
        Route::resource('packagecoupon', App\Http\Controllers\api\adminDashboard\PackagecouponController::class);
        Route::resource('notification', App\Http\Controllers\api\adminDashboard\NotificationController::class);
        Route::resource('notification_type', App\Http\Controllers\api\adminDashboard\Notification_typesController::class);
        Route::resource('section', App\Http\Controllers\api\adminDashboard\SectionController::class,['names' => 'admin.section']);
        Route::resource('contact', App\Http\Controllers\api\adminDashboard\ContactController::class);
        Route::resource('replaycontact', App\Http\Controllers\api\adminDashboard\ReplaycontactController::class);
        Route::resource('seo', App\Http\Controllers\api\adminDashboard\SeoController::class);
        Route::resource('setting', App\Http\Controllers\api\adminDashboard\SettingController::class,['names' => 'admin.setting']);
        Route::resource('store', App\Http\Controllers\api\adminDashboard\StoreController::class,['names' => 'admin.store']);
        Route::resource('offer', App\Http\Controllers\api\adminDashboard\OfferController::class);
        Route::resource('product', App\Http\Controllers\api\adminDashboard\ProductController::class,['names' => 'admin.product']);
        Route::resource('option', App\Http\Controllers\api\adminDashboard\OptionController::class);
        Route::resource('user', App\Http\Controllers\api\adminDashboard\UserController::class,['names' => 'admin.user']);
        Route::resource('etlobha', App\Http\Controllers\api\adminDashboard\EtlobhaController::class,['names' => 'admin.etlobha']);

        Route::get('statistics/{id}', [App\Http\Controllers\api\adminDashboard\EtlobhaController::class, 'statistics'])->name('admin.etlobha.statistics');

        Route::post('sectionupdate', [App\Http\Controllers\api\adminDashboard\SectionController::class, 'update'])->name('admin.section.sectionupdate');

        Route::get('storeReport', [App\Http\Controllers\api\adminDashboard\StoreReportController::class, 'index']);
        Route::get('home', [App\Http\Controllers\api\adminDashboard\StoreReportController::class, 'home'])->name('admin.mainpage.index');

        Route::get('registration_status_show', [App\Http\Controllers\api\adminDashboard\SettingController::class, 'registration_status_show'])->name('admin.registration.show');
        Route::post('registration_status_update', [App\Http\Controllers\api\adminDashboard\SettingController::class, 'registration_status_update'])->name('admin.registration.update');

        Route::post('optionsProduct/{id}', [App\Http\Controllers\api\adminDashboard\OptionController::class, 'optionsProduct']);

        Route::resource('websiteorder', App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,['names' => 'admin.websiteorder']);
        Route::get('websiteorderdeleteall', [App\Http\Controllers\api\adminDashboard\WebsiteorderController::class, 'deleteall'])->name('admin.websiteorder.deleteall');

        Route::post('acceptStore/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteorderController::class, 'acceptStore']);
        Route::post('rejectStore/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteorderController::class, 'rejectStore']);
        Route::post('acceptService/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteorderController::class, 'acceptService'])->name('admin.websiteorder.acceptService');
        Route::post('rejectService/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteorderController::class, 'rejectService'])->name('admin.websiteorder.rejectService');

        Route::resource('stock', App\Http\Controllers\api\adminDashboard\StockController::class,['names' => 'admin.stock']);
// import product
        Route::post('importproducts', [App\Http\Controllers\api\adminDashboard\StockController::class, 'importStockProducts'])->name('admin.stock.importStockProducts');;

        Route::get('stockdeleteall', [App\Http\Controllers\api\adminDashboard\StockController::class, 'deleteall'])->name('admin.stock.deleteall');;
        Route::get('storechangeSatusall', [App\Http\Controllers\api\adminDashboard\StoreController::class, 'changeSatusall'])->name('admin.store.changeSatusall');
        Route::get('productchangeSatusall', [App\Http\Controllers\api\adminDashboard\ProductController::class, 'changeSatusall'])->name('admin.product.changeSatusall');
        Route::get('etlobhachangeSatusall', [App\Http\Controllers\api\adminDashboard\EtlobhaController::class, 'changeStatusall'])->name('admin.etlobha.changeStatusall');
        Route::get('etlobhadeleteall', [App\Http\Controllers\api\adminDashboard\EtlobhaController::class, 'deleteall'])->name('admin.etlobha.deleteall');
        Route::get('etlobhachangeSpecial/{id}', [App\Http\Controllers\api\adminDashboard\EtlobhaController::class, 'specialStatus'])->name('admin.etlobha.specialStatus');
        Route::get('productdeleteall', [App\Http\Controllers\api\adminDashboard\ProductController::class, 'deleteall'])->name('admin.product.deleteall');
        Route::post('addToStore/{id}', [App\Http\Controllers\api\adminDashboard\StockController::class, 'addToStore'])->name('admin.stock.addToStore');
        // Route::get('technicalSupportchangeStatusall', [App\Http\Controllers\api\adminDashboard\TechnicalSupportController::class, 'changeStatusall']);

        Route::get('pagechangeSatusall', [App\Http\Controllers\api\adminDashboard\PageController::class, 'changeSatusall'])->name('admin.page.changesatusall');
        Route::get('pagedeleteall', [App\Http\Controllers\api\adminDashboard\PageController::class, 'deleteall'])->name('admin.page.deleteall');
        Route::get('userchangeSatusall', [App\Http\Controllers\api\adminDashboard\UserController::class, 'changeSatusall'])->name('admin.user.changesatusall');
        Route::get('userdeleteall', [App\Http\Controllers\api\adminDashboard\UserController::class, 'deleteall'])->name('admin.user.deleteall');
        Route::get('couponchangeSatusall', [App\Http\Controllers\api\adminDashboard\CouponController::class, 'changeSatusall'])->name('admin.coupon.changesatusall');
        Route::get('coupondeleteall', [App\Http\Controllers\api\adminDashboard\CouponController::class, 'deleteall'])->name('admin.coupon.deleteall');
        Route::get('marketerdeleteall', [App\Http\Controllers\api\adminDashboard\MarketerController::class, 'deleteall'])->name('admin.marketer.deleteall');
        Route::get('categorychangeSatusall', [App\Http\Controllers\api\adminDashboard\CategoryController::class, 'changeSatusall'])->name('admin.category.changesatusall');
        Route::get('categorydeleteall', [App\Http\Controllers\api\adminDashboard\CategoryController::class, 'deleteall'])->name('admin.category.deleteall');
        Route::get('categorystorechangeSatusall', [App\Http\Controllers\api\adminDashboard\StoreCategoryController::class, 'changeSatusall'])->name('admin.storecategory.changesatusall');
        Route::get('categorystoredeleteall', [App\Http\Controllers\api\adminDashboard\StoreCategoryController::class, 'deleteall'])->name('admin.storecategory.deleteall');
        Route::get('cityedeleteall', [App\Http\Controllers\api\adminDashboard\CityController::class, 'deleteall'])->name('admin.city.deleteall');
        Route::get('countryedeleteall', [App\Http\Controllers\api\adminDashboard\CountryController::class, 'deleteall'])->name('admin.country.deleteall');
        Route::get('currencychangeSatusall', [App\Http\Controllers\api\adminDashboard\CurrencyController::class, 'changeSatusall'])->name('admin.currency.changeSatusall');
        Route::get('packagechangeSatusall', [App\Http\Controllers\api\adminDashboard\PackageController::class, 'changeSatusall'])->name('admin.package.changeSatusall');
        // Route::get('packagedeleteall', [App\Http\Controllers\api\adminDashboard\PackageController::class, 'deleteall']);
        Route::get('registration_marketer_show', [App\Http\Controllers\api\adminDashboard\SettingController::class, 'registration_marketer_show'])->name('admin.marketer.registration_marketer_show');

        // Route::resource('note', App\Http\Controllers\api\adminDashboard\NoteController::class);

        Route::resource('roles', App\Http\Controllers\api\adminDashboard\RoleController::class,['names' => 'admin.role']);
        Route::post('addProductNote', [App\Http\Controllers\api\adminDashboard\ProductController::class, 'addNote'])->name('admin.product.addNote');
        Route::get('productchangeSpecial/{id}', [App\Http\Controllers\api\adminDashboard\ProductController::class, 'specialStatus']);
        Route::get('activitydeleteall', [App\Http\Controllers\api\adminDashboard\ActivityController::class, 'deleteall'])->name('admin.activity.deleteall');

// Route::post('statusMarketer/{id}',[App\Http\Controllers\api\adminDashboard\SettingController::class,'statusMarketer']);
        Route::post('registrationMarketer', [App\Http\Controllers\api\adminDashboard\SettingController::class, 'registrationMarketer'])->name('admin.marketer.registrationMarketer');
        Route::get('contactdeleteall', [App\Http\Controllers\api\adminDashboard\ContactController::class, 'deleteall']);
        // Route::post('shippOrder', [App\Http\Controllers\api\adminDashboard\ShippingtypeController::class, 'shippOrder']);
//
        Route::get('verification', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'index'])->name('admin.verification.index');
        Route::get('verificationdeleteall', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'deleteall'])->name('admin.verification.deleteall');
        Route::post('addStoreNote', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'addNote'])->name('admin.verification.addNote');
        Route::get('acceptVerification/{id}', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'acceptVerification'])->name('admin.verification.acceptVerification');
        Route::get('specialStatus/{id}', [App\Http\Controllers\api\adminDashboard\StoreController::class, 'specialStatus'])->name('admin.store.specialStatus');
        Route::get('rejectVerification/{id}', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'rejectVerification'])->name('admin.verification.rejectVerification');
        Route::post('verification_update', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'verification_update'])->name('admin.verification.verification_update');
//Route::delete('verification_delete/{id}',[App\Http\Controllers\api\adminDashboard\VerificationController::class,'destroy']);
//
        Route::get('subscriptions', [App\Http\Controllers\api\adminDashboard\SubscriptionsController::class, 'index'])->name('admin.subscriptions.index');
        Route::post('addAlert', [App\Http\Controllers\api\adminDashboard\SubscriptionsController::class, 'addAlert'])->name('admin.subscriptions.addAlert');
        Route::get('subscriptionsdeleteall', [App\Http\Controllers\api\adminDashboard\SubscriptionsController::class, 'deleteall'])->name('admin.subscriptions.deleteall');
        Route::get('subscriptionschangeSatusall', [App\Http\Controllers\api\adminDashboard\SubscriptionsController::class, 'changeSatusall'])->name('admin.subscriptions.changeSatusall');

        Route::get('permissions', [App\Http\Controllers\api\adminDashboard\PermissionController::class, 'index'])->name('permissions');
        Route::get('atlobhaContact', [App\Http\Controllers\api\adminDashboard\AtlobhaContactController::class, 'index'])->name('admin.atlobhaContact.index');
        Route::get('atlobhaContact/{id}', [App\Http\Controllers\api\adminDashboard\AtlobhaContactController::class, 'show'])->name('admin.atlobhaContact.show');
        Route::get('atlobhaContactdeleteall', [App\Http\Controllers\api\adminDashboard\AtlobhaContactController::class, 'deleteall'])->name('admin.atlobhaContact.deleteall');
        Route::get('atlobhaContactChangeStatus/{id}', [App\Http\Controllers\api\adminDashboard\AtlobhaContactController::class, 'changeStatus'])->name('admin.atlobhaContact.changeStatus');
        // });
    });
});
Auth::routes();

// Route::group(['prefix' => '/Store', 'middleware' => ['storeUsers']], function(){
Route::middleware([StoreUser::class])->group(function () {
    Route::prefix('/Store')->group(function () {
        // import file
        // Route::post('importFile', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'importProducts']);

        // country
        Route::resource('country', App\Http\Controllers\api\storeDashboard\CountryController::class);
        Route::resource('city', App\Http\Controllers\api\storeDashboard\CityController::class);

        //cart
        Route::get('cartShow/{id}', [App\Http\Controllers\api\storeDashboard\CartController::class, 'show'])->name('abandoned.carts.show');
        Route::get('admin', [App\Http\Controllers\api\storeDashboard\CartController::class, 'admin'])->name('abandoned.carts');
        // Route::post('addCart', [App\Http\Controllers\api\storeDashboard\CartController::class, 'addToCart']);
        Route::get('deleteCart', [App\Http\Controllers\api\storeDashboard\CartController::class, 'delete'])->name('abandoned.carts.delete');
        Route::post('sendOfferCart/{id}', [App\Http\Controllers\api\storeDashboard\CartController::class, 'sendOffer'])->name('abandoned.carts.sendoffer');

        // page
        Route::resource('page', App\Http\Controllers\api\storeDashboard\PageController::class, ['names' => 'store.pages']);
        Route::post('page-publish', [App\Http\Controllers\api\storeDashboard\PageController::class, 'publish'])->name('store.pages.publish');
        Route::get('pagechangeSatusall', [App\Http\Controllers\api\storeDashboard\PageController::class, 'changeSatusall'])->name('store.pages.changestatusall');
        Route::get('pagedeleteall', [App\Http\Controllers\api\storeDashboard\PageController::class, 'deleteall'])->name('store.pages.deleteall');
        Route::post('changePageStatus/{id}', [App\Http\Controllers\api\storeDashboard\PageController::class, 'changeStatus'])->name('store.pages.activate');
        // academy
        Route::resource('explainVideos', App\Http\Controllers\api\storeDashboard\ExplainVideosController::class, ['names' => 'store.explainvideos']);
        Route::resource('course', App\Http\Controllers\api\storeDashboard\CourseController::class, ['names' => 'store.academy']);

        // template
        Route::get('theme', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'theme'])->name('store.template.theme');
        Route::post('logoUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'logoUpdate'])->name('store.template.logoupdate');
        Route::post('banarUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'banarUpdate'])->name('store.template.banarupdate');
        Route::post('sliderUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'sliderUpdate'])->name('store.template.sliderupdate');
        Route::post('commentUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'commentUpdate'])->name('store.template.commentupdate');
        Route::post('themeSearchUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeSearchUpdate'])->name('store.template.themeSearchUpdate');
        Route::post('themeCategoriesUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeCategoriesUpdate'])->name('store.template.themeCategoriesUpdate');
        Route::post('themeMenuUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeMenuUpdate'])->name('store.template.themeMenuUpdate');
        Route::post('themeLayoutUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeLayoutUpdate'])->name('store.template.themeLayoutUpdate');
        Route::post('themeIconUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeIconUpdate'])->name('store.template.themeIconUpdate');
        Route::post('themeProductUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeProductUpdate'])->name('store.template.themeProductUpdate');
        Route::post('themeFilterUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeFilterUpdate'])->name('store.template.themeFilterUpdate');
        Route::post('themeMainUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeMainUpdate'])->name('store.template.themeMainUpdate');
        // Route::post('themeSubUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeSubUpdate'])->name('store.template.themeSubUpdate');
        Route::post('themeFooterUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeFooterUpdate'])->name('store.template.themeFooterUpdate');

        // maintenance
        Route::resource('maintenance', App\Http\Controllers\api\storeDashboard\MaintenanceController::class, ['names' => 'store.maintenancemode']);
        Route::post('updateMaintenance', [App\Http\Controllers\api\storeDashboard\MaintenanceController::class, 'updateMaintenance'])->name('store.maintenancemode.updatemaintenance');

        Route::resource('pagecategory', App\Http\Controllers\api\storeDashboard\PageCategoryController::class);
        Route::get('changePageCategoryStatus/{id}', [App\Http\Controllers\api\storeDashboard\PageCategoryController::class, 'changeStatus']);
        // product
        Route::resource('product', App\Http\Controllers\api\storeDashboard\ProductController::class, ['names' => 'store.products']);
        Route::post('import-products', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'importProducts'])->name('store.products.importfile');
        Route::get('productchangeSatusall', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'changeSatusall'])->name('store.products.changestatusall');
        Route::get('productdeleteall', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'deleteall'])->name('store.products.deleteall');
        Route::get('changeProductStatus/{id}', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'changeStatus'])->name('store.products.activate');
        Route::get('deleteImport/{product}', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'deleteImport'])->name('store.products.deleteimport');
        Route::post('duplicateProduct/{product}', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'duplicateProduct'])->name('store.products.duplicateproduct');

        // importProduct
        Route::get('etlobhaShow', [App\Http\Controllers\api\storeDashboard\ImportproductController::class, 'etlobhaShow'])->name('store.products.etlobhaShow');
        Route::get('etlobhaProductShow/{id}', [App\Http\Controllers\api\storeDashboard\ImportproductController::class, 'show'])->name('store.products.etlbhasingleproduct');

        Route::post('importproduct', [App\Http\Controllers\api\storeDashboard\ImportproductController::class, 'store'])->name('store.products.import');
        Route::post('updateimportproduct/{id}', [App\Http\Controllers\api\storeDashboard\ImportproductController::class, 'updateimportproduct'])->name('store.products.updateimport');
        // category
        Route::resource('category', App\Http\Controllers\api\storeDashboard\CategoryController::class, ['names' => 'store.categories']);
        Route::get('categoryStorechangeSatusall', [App\Http\Controllers\api\storeDashboard\CategoryController::class, 'changeSatusall'])->name('store.categories.changestatusall');
        Route::get('categoryStoredeleteall', [App\Http\Controllers\api\storeDashboard\CategoryController::class, 'deleteall'])->name('store.categories.deleteall');
        Route::get('changeCategoryStatus/{id}', [App\Http\Controllers\api\storeDashboard\CategoryController::class, 'changeStatus'])->name('store.categories.activate');
        // coupon
        Route::get('changeCouponStatus/{id}', [App\Http\Controllers\api\storeDashboard\CouponController::class, 'changeStatus'])->name('store.copons.activate');
        Route::resource('coupons', App\Http\Controllers\api\storeDashboard\CouponController::class, ['names' => 'store.copons']);
        Route::get('couponchangeSatusall', [App\Http\Controllers\api\storeDashboard\CouponController::class, 'changeSatusall'])->name('store.copons.changestatusall');
        Route::get('coupondeleteall', [App\Http\Controllers\api\storeDashboard\CouponController::class, 'deleteall'])->name('store.copons.deleteall');
        //  التوثيق
        Route::get('verification_show', [App\Http\Controllers\api\storeDashboard\VerificationController::class, 'verification_show'])->name('store.verification.show');
        Route::post('verification_update', [App\Http\Controllers\api\storeDashboard\VerificationController::class, 'verification_update'])->name('store.verification.add');
        // social media
        Route::get('socialMedia_store_show', [App\Http\Controllers\api\storeDashboard\storeInformationController::class, 'socialMedia_store_show'])->name('store.socialmedia.show');
        Route::post('socialMedia_store_update', [App\Http\Controllers\api\storeDashboard\storeInformationController::class, 'socialMedia_store_update'])->name('store.socialmedia.update');

        Route::resource('video', App\Http\Controllers\api\storeDashboard\VideoController::class);
        Route::get('changeUnitStatus/{id}', [App\Http\Controllers\api\storeDashboard\UnitController::class, 'changeStatus']);
        Route::resource('unit', App\Http\Controllers\api\storeDashboard\UnitController::class);
        // technical Support
        // Route::post('changeTechnicalSupportStatus/{id}', [App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class,'changeStatus']);
        Route::resource('technicalSupport', App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class, ['names' => 'store.technicalsupport']);
        Route::get('technicalSupportStoredeleteall', [App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class, 'deleteall'])->name('store.technicalsupport.deleteall');
        Route::get('changeTechnicalSupportStatus/{id}', [App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class, 'changeStatus'])->name('store.technicalsupport.cahngestatus');
        Route::post('replayTechnicalSupport', [App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class, 'replay'])->name('store.technicalsupport.replay');
        // Shipping company
        Route::get('changeShippingtypeStatus/{id}', [App\Http\Controllers\api\storeDashboard\ShippingtypeController::class, 'changeStatus'])->name('store.shippingcompanies.activate');
        Route::resource('shippingtype', App\Http\Controllers\api\storeDashboard\ShippingtypeController::class, ['names' => 'store.shippingcompanies']);
        // payment type
        Route::get('changePaymenttypeStatus/{id}', [App\Http\Controllers\api\storeDashboard\PaymenttypeController::class, 'changeStatus'])->name('store.paymentsgateways.activate');
        Route::resource('paymenttype', App\Http\Controllers\api\storeDashboard\PaymenttypeController::class, ['names' => 'store.paymentsgateways']);
        //offer
        // Route::get('changeOfferStatus/{id}', [App\Http\Controllers\api\storeDashboard\OfferController::class, 'changeStatus']);
        // Route::resource('offer', App\Http\Controllers\api\storeDashboard\OfferController::class);
        // SEO keywords
        Route::resource('seo', App\Http\Controllers\api\storeDashboard\SeoController::class, ['names' => 'store.keywords']);
        Route::post('updateSeo', [App\Http\Controllers\api\storeDashboard\SeoController::class, 'updateSeo'])->name('store.keywords.updateseo');
        Route::post('updateLink', [App\Http\Controllers\api\storeDashboard\SeoController::class, 'updateLink'])->name('store.keywords.updatelink');
        Route::post('updateRobots', [App\Http\Controllers\api\storeDashboard\SeoController::class, 'updateRobots'])->name('store.keywords.updaterobots');

        //  clients
        // Route::resource('client',App\Http\Controllers\api\storeDashboard\ClientController::class);
        // Route::get('changeClientStatus/{id}', [App\Http\Controllers\api\storeDashboard\ClientController::class, 'changeStatus']);
        // Route::get('clientdeleteall', [App\Http\Controllers\api\storeDashboard\ClientController::class, 'deleteall']);
        // //
        Route::resource('homepage', App\Http\Controllers\api\storeDashboard\HomepageController::class, ['names' => 'store.template']);
        // comments
        Route::resource('comment', App\Http\Controllers\api\storeDashboard\CommentController::class, ['names' => 'store.comments']);
        Route::get('commentchangeSatusall', [App\Http\Controllers\api\storeDashboard\CommentController::class, 'changeSatusall'])->name('store.comments.changestatusall');
        Route::post('replaycomment', [App\Http\Controllers\api\storeDashboard\CommentController::class, 'replayComment'])->name('store.comments.replaycomment');
        Route::get('changeCommentStatus/{id}', [App\Http\Controllers\api\storeDashboard\CommentController::class, 'changeStatus'])->name('store.comments.activate');
        Route::get('commentActivation', [App\Http\Controllers\api\storeDashboard\CommentController::class, 'commentActivation'])->name('store.comments.activateall');
        // Route::post('changeReplaycommentStatus/{id}', [App\Http\Controllers\api\storeDashboard\ReplaycommentController::class,'changeStatus']);

        // Route::resource('replaycomment',App\Http\Controllers\api\storeDashboard\ReplaycommentController::class);
        // etlobha comment
        Route::post('etlobhaComment', [App\Http\Controllers\api\storeDashboard\EtlobhaController::class, 'etlobhaComment']);
        Route::get('existComment', [App\Http\Controllers\api\storeDashboard\EtlobhaController::class, 'existComment']);

        // users
        Route::resource('user', App\Http\Controllers\api\storeDashboard\UserController::class);
        Route::get('changeuserStatus/{id}', [App\Http\Controllers\api\storeDashboard\UserController::class, 'changeStatus']);
        Route::get('userchangeSatusall', [App\Http\Controllers\api\storeDashboard\UserController::class, 'changeSatusall']);
        Route::get('userdeleteall', [App\Http\Controllers\api\storeDashboard\UserController::class, 'deleteall']);
        //
        Route::get('changeReplaycommentStatus/{id}', [App\Http\Controllers\api\storeDashboard\ReplaycommentController::class, 'changeStatus']);

        //  setting
        Route::get('setting_store_show', [App\Http\Controllers\api\storeDashboard\SettingController::class, 'setting_store_show'])->name('store.basicdata.show');
        Route::post('setting_store_update', [App\Http\Controllers\api\storeDashboard\SettingController::class, 'setting_store_update'])->name('store.basicdata.update');
        //profile
        Route::get('profile', [App\Http\Controllers\api\storeDashboard\ProfileController::class, 'index']);
        Route::post('profile', [App\Http\Controllers\api\storeDashboard\ProfileController::class, 'update']);
        // notifications
        Route::get('NotificationIndex', [App\Http\Controllers\api\storeDashboard\NotificationController::class, 'index']);
        Route::get('NotificationRead/{id}', [App\Http\Controllers\api\storeDashboard\NotificationController::class, 'read']);
        Route::get('NotificationDelete/{id}', [App\Http\Controllers\api\storeDashboard\NotificationController::class, 'deleteNotification']);
        Route::get('NotificationDeleteAll', [App\Http\Controllers\api\storeDashboard\NotificationController::class, 'deleteNotificationAll']);
        Route::get('NotificationShow/{id}', [App\Http\Controllers\api\storeDashboard\NotificationController::class, 'show']);
        //  Etlobha services
        Route::get('etlobhaservice/show', [App\Http\Controllers\api\storeDashboard\EtlobhaserviceController::class, 'show']);
        Route::post('etlobhaservice', [App\Http\Controllers\api\storeDashboard\EtlobhaserviceController::class, 'store']);
        Route::get('marketerRequest/{id}', [App\Http\Controllers\api\storeDashboard\EtlobhaserviceController::class, 'marketerRequest']);

        // selector
        Route::get('selector/packages', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'packages']);
        Route::get('selector/products', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'products']);
        Route::get('selector/payment_types', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'payment_types']);
        Route::get('selector/productImportproduct', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'storeImportproduct']);

        Route::get('selector/auth_user', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'auth_user']);
        Route::get('selector/cities', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'cities']);
        Route::get('selector/countries', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'countries']);
        Route::get('selector/activities', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'activities']);
        Route::get('selector/mainCategories', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'mainCategories']);
        Route::get('selector/etlobahCategory', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'etlobahCategory']);
        Route::get('selector/services', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'services']);
        Route::get('selector/children/{id}', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'children']);
        Route::get('selector/roles', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'roles']);

        Route::get('selector/page-categories', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'pagesCategory']);
        Route::get('selector/post-categories', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'post_categories']);

        //  payment
        Route::post('payment', [App\Http\Controllers\api\storeDashboard\PaymentController::class, 'payment']);
        Route::get('callback', [App\Http\Controllers\api\storeDashboard\PaymentController::class, 'callback'])->name('callback');
        Route::post('updateCharge/{id}', [App\Http\Controllers\api\storeDashboard\PaymentController::class, 'updateCharge']);
        Route::get('list', [App\Http\Controllers\api\storeDashboard\PaymentController::class, 'list'])->name('list');
        // order
        Route::resource('orders', App\Http\Controllers\api\storeDashboard\OrderController::class, ['names' => 'store.orders']);
        Route::get('index', [App\Http\Controllers\api\storeDashboard\IndexController::class, 'index'])->name('homepage.show');
        Route::get('ordersdeleteall', [App\Http\Controllers\api\storeDashboard\OrderController::class, 'deleteall'])->name('store.orders.deleteall');
        Route::get('permissions', [App\Http\Controllers\api\storeDashboard\PermissionController::class, 'index'])->name('permissions');
        Route::resource('roles', App\Http\Controllers\api\storeDashboard\RoleController::class);
        // reports
        Route::get('reports', [ReportController::class, 'index']);

        // subsicription

        Route::get('subsicriptions', [SubscriptionEmailController::class, 'index']);
        Route::get('subsicriptionsdeleteall', [SubscriptionEmailController::class, 'deleteall']);

    });
});
