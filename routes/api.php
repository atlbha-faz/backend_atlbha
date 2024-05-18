<?php

use App\Http\Controllers\api\adminDashboard\ServiceController;
use App\Http\Controllers\api\MadfuController;
use App\Http\Controllers\api\storeDashboard\ReportController;
use App\Http\Controllers\api\storeDashboard\SubscriptionEmailController;
use App\Http\Middleware\SetActiveStore;
use App\Http\Controllers\api\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AramexController;

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

Route::prefix('madfu')->group(function () {

    Route::post('login', [MadfuController::class, 'login']);
    Route::post('create-order', [MadfuController::class, 'createOrder']);
    Route::post('webhook', [MadfuController::class, 'webhook'])->name('madfu-webhook');
});
Route::prefix('aramex')->group(function () {
    Route::post('webhook', [AramexController::class, 'webhook'])->name('aramex-webhook');
});
Route::group(['prefix' => 'home'], function () {
    Route::get('products', [HomeController::class, 'products']);
});
//  test sms
Route::post('/send', 'App\Http\Controllers\api\SmsController@smsSend');
Route::get('sendMessage', 'App\Http\Controllers\api\AuthController@storeVerifyMessage');
Route::post('webhook', [App\Http\Controllers\api\WebhookController::class, 'handleWebhook']);

Route::get('unifonicTest', 'App\Http\Controllers\api\AuthController@unifonicTest');
Route::get('selector/cities', [App\Http\Controllers\api\SelectorController::class, 'cities']);
Route::get('selector/countries', [App\Http\Controllers\api\SelectorController::class, 'countries']);
Route::get('selector/activities', [App\Http\Controllers\api\SelectorController::class, 'activities']);
Route::get('selector/packages', [App\Http\Controllers\api\SelectorController::class, 'packages']);
Route::get('selector/addToCart', [App\Http\Controllers\api\SelectorController::class, 'addToCart']);
Route::get('selector/shippingcities/{id}', [App\Http\Controllers\api\SelectorController::class, 'shippingCities']);
Route::get('selector/activateAccount/{id}', [App\Http\Controllers\api\SelectorController::class, 'activateAccount']);
Route::get('selector/registrationMarketer', [App\Http\Controllers\api\SelectorController::class, 'registrationMarketer']);
Route::get('selector/banks', [App\Http\Controllers\api\SelectorController::class, 'getBank']);
Route::get('selector/returnReasons', [App\Http\Controllers\api\SelectorController::class, 'returnReason']);
Route::post('/social-mobile', 'App\Http\Controllers\api\AuthController@social_mobile');

Route::post('/loginapi', 'App\Http\Controllers\api\AuthController@login');
Route::post('/loginadminapi', 'App\Http\Controllers\api\AuthController@loginAdmin');
// Route::post('/logincustomerapi','App\Http\Controllers\api\AuthController@login_customer');
Route::post('/registerapi', 'App\Http\Controllers\api\AuthController@register');
Route::get('/logout', 'App\Http\Controllers\api\AuthController@logout');
// login template
Route::post('/logincustomerphoneapi', 'App\Http\Controllers\api\storeTemplate\AuthCustomerController@loginCustomer');
Route::post('/logincustomeremailapi', 'App\Http\Controllers\api\storeTemplate\AuthCustomerController@loginCustomerEmail');
Route::post('/registerUser/{id}', 'App\Http\Controllers\api\storeTemplate\AuthCustomerController@registerUser');
Route::post('/verifyUser', 'App\Http\Controllers\api\storeTemplate\AuthCustomerController@verifyUser');
Route::get('/logoutcustomer', 'App\Http\Controllers\api\storeTemplate\AuthCustomerController@logout');
//  index Ettlobha page

Route::get('index', [App\Http\Controllers\api\homePages\IndexEtlobhaController::class, 'index']);
Route::get('storesFilter', [App\Http\Controllers\api\homePages\IndexEtlobhaController::class, 'storesFilter']);
Route::get('commonquestion', [App\Http\Controllers\api\homePages\IndexEtlobhaController::class, 'commonQuestion']);
Route::get('addstoremonth', [App\Http\Controllers\api\homePages\IndexEtlobhaController::class, 'addstoremonth']);
Route::get('searchIndex', [App\Http\Controllers\api\homePages\IndexEtlobhaController::class, 'searchIndex']);

Route::post('atlobhaContactAdd', [App\Http\Controllers\api\homePages\IndexEtlobhaController::class, 'store']);
//  index store page القالب
Route::middleware([SetActiveStore::class])->group(function () {
    Route::get('indexStore/{id}', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'index']);
    Route::get('productPage/{domain}/{id}', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'productPage']);
    Route::get('storPage/{id}', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'storPage']);
    Route::get('category/{id}', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'category']);
    Route::get('storeProductCategory', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'storeProductCategory']);
    Route::get('productSearch', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'productSearch']);
    Route::get('profileCustomer', [App\Http\Controllers\api\storeTemplate\ProfileCustomerController::class, 'index']);
    Route::post('profileCustomer', [App\Http\Controllers\api\storeTemplate\ProfileCustomerController::class, 'update']);
    Route::get('deactivateAccount', [App\Http\Controllers\api\storeTemplate\ProfileCustomerController::class, 'deactivateAccount']);
    Route::get('specialProducts/{id}', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'specialProducts']);
    Route::get('recentProducts/{id}', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'recentProducts']);
    Route::get('moreSalesProducts/{id}', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'moreSalesProducts']);
    Route::get('productsRatings/{id}', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'productsRatings']);

    Route::post('addSubsicription/{domain}', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'addSubsicription']);

});
Route::get('cartShow/{id}', [App\Http\Controllers\api\storeTemplate\CartTemplateController::class, 'show']);
Route::post('addCart/{domain}', [App\Http\Controllers\api\storeTemplate\CartTemplateController::class, 'addToCart']);
Route::get('deleteCart/{domain}/{id}', [App\Http\Controllers\api\storeTemplate\CartTemplateController::class, 'delete']);
Route::post('cheackout/{domain}', [App\Http\Controllers\api\storeTemplate\CheckoutController::class, 'cheackout']);
Route::get('paymentmethods/{domain}', [App\Http\Controllers\api\storeTemplate\CheckoutController::class, 'paymentMethods']);
Route::get('shippingcompany/{domain}', [App\Http\Controllers\api\storeTemplate\CheckoutController::class, 'shippingCompany']);
Route::post('applyCoupon/{domain}/{cart_id}', [App\Http\Controllers\api\storeTemplate\CheckoutController::class, 'applyCoupon']);
Route::get('ordersUser/{domain}', [App\Http\Controllers\api\storeTemplate\CheckoutController::class, 'ordersUser']);
Route::get('orderUser/{domain}/{order_id}', [App\Http\Controllers\api\storeTemplate\CheckoutController::class, 'orderUser']);
Route::resource('OrderAddress', App\Http\Controllers\api\storeTemplate\OrderAddressController::class);
Route::get('show_default_address', [App\Http\Controllers\api\storeTemplate\OrderAddressController::class, 'showDefaultAddress']);
Route::get('setDefaultAddress/{id}', [App\Http\Controllers\api\storeTemplate\OrderAddressController::class, 'setDefaultAddress']);
Route::get('cancelOrder/{id}', [App\Http\Controllers\api\storeTemplate\CheckoutController::class, 'cancelOrder']);
Route::get('returnOrderIndex/{id}', [App\Http\Controllers\api\storeTemplate\ReturnOrderController::class, 'index']);
Route::post('returnOrder', [App\Http\Controllers\api\storeTemplate\ReturnOrderController::class, 'store']);
Route::get('returnOrder/{id}', [App\Http\Controllers\api\storeTemplate\ReturnOrderController::class, 'show']);

Route::get('postStore/{id}', [App\Http\Controllers\api\storeTemplate\PostStoreController::class, 'index']);
Route::get('postByCategory/{id}', [App\Http\Controllers\api\storeTemplate\PostStoreController::class, 'show']);
Route::get('postdetail/{id}', [App\Http\Controllers\api\storeTemplate\PostStoreController::class, 'showPost']);

Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::post('addComment/{id}', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'addComment']);
    Route::post('addContact', [App\Http\Controllers\api\storeTemplate\IndexStoreController::class, 'addContact']);
    Route::prefix('madfu')->group(function () {

        Route::post('login', [MadfuController::class, 'login']);
    });
});
// visit count
Route::post('storeClientVisit', [App\Http\Controllers\api\VisitCountController::class, 'storeClientVisit']);
//
Route::get('visit', [App\Http\Controllers\api\VisitCountController::class, 'visit']);

Route::get('posts', [App\Http\Controllers\api\homePages\PostController::class, 'index']);
Route::get('searchPost', [App\Http\Controllers\api\homePages\PostController::class, 'searchPost']);
Route::get('start', [App\Http\Controllers\api\homePages\PostController::class, 'start']);
Route::get('show/{id}', [App\Http\Controllers\api\homePages\PostController::class, 'show']);
Route::get('show_post/{id}', [App\Http\Controllers\api\homePages\PostController::class, 'showPost']);

Route::post('send-verify-message', 'App\Http\Controllers\api\AuthController@storeVerifyMessage');
Route::post('verify-user', 'App\Http\Controllers\api\AuthController@verifyUser');

Route::get('page/{id}', [App\Http\Controllers\api\homePages\SubpageController::class, "show"]);
Route::get('packages', [App\Http\Controllers\api\homePages\SubpageController::class, "packages"]);
Route::get('homeService', [App\Http\Controllers\api\homePages\SubpageController::class, "homeService"]);

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
Route::get('store_token/{id}',[\App\Http\Controllers\api\adminDashboard\StoreController::class,'storeToken']);
Route::middleware([AdminUser::class])->group(function () {
    Route::prefix('/Admin')->group(function () {

        Route::resource('packagecoupon', App\Http\Controllers\api\adminDashboard\PackagecouponController::class);
        Route::resource('notification', App\Http\Controllers\api\adminDashboard\NotificationController::class);
        Route::resource('notification_type', App\Http\Controllers\api\adminDashboard\Notification_typesController::class);

        Route::get('selector/etlobahCategory', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'etlobahCategory']);
        Route::get('selector/years', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'years']);
        Route::get('selector/cities', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'cities']);
        Route::get('selector/countries', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'countries']);
        Route::get('selector/activities', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'activities']);
        Route::get('selector/packages', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'packages']);
        Route::get('selector/plans', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'plans']);
        Route::get('selector/templates', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'templates']);
        Route::get('selector/course/units/{id}', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'units']);
        Route::get('selector/page-categories', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'pageCategories']);
        Route::get('selector/post-categories', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'postCategories']);
        Route::get('selector/roles', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'roles']);
        Route::get('selector/subcategories', [App\Http\Controllers\api\adminDashboard\SelectorController::class, 'subCategories']);

        Route::resource('pagecategory', App\Http\Controllers\api\adminDashboard\PageCategoryController::class);
        Route::get('profile', [App\Http\Controllers\api\adminDashboard\ProfileController::class, 'index']);
        Route::post('profile', [App\Http\Controllers\api\adminDashboard\ProfileController::class, 'update']);
        Route::resource('storecategory', App\Http\Controllers\api\adminDashboard\StoreCategoryController::class);

        Route::middleware([AdminCheckPermission::class])->group(function () {
            Route::get('loginid/{id}', [App\Http\Controllers\api\adminDashboard\StoreController::class, 'loginId'])->name('admin.store.loginStore');

            Route::get('changePlatformStatus/{id}', [App\Http\Controllers\api\adminDashboard\PlatformController::class, 'changeStatus'])->name('admin.platform.changePlatformStatus');

            Route::get('changeShippingtypeStatus/{id}', [App\Http\Controllers\api\adminDashboard\ShippingtypeController::class, 'changeStatus'])->name('admin.shippingtype.changeStatus');
            Route::get('changePaymenttypeStatus/{id}', [App\Http\Controllers\api\adminDashboard\PaymenttypeController::class, 'changeStatus'])->name('admin.paymenttype.changeStatus');
            Route::get('changewebsite_socialmediaStatus/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteSocialmediaController::class, 'changeStatus'])->name('admin.websitesocialmedia.changeStatus');

            Route::get('changeSectionStatus/{id}', [App\Http\Controllers\api\adminDashboard\SectionController::class, 'changeStatus'])->name('admin.section.changestatus');

            Route::get('changeStoreStatus', [App\Http\Controllers\api\adminDashboard\StoreController::class, 'changeSatusAll'])->name('admin.store.changeSatusall');
            Route::get('unVerificationStore', [App\Http\Controllers\api\adminDashboard\StoreController::class, 'unVerificationStore'])->name('admin.store.unVerificationStore');

            Route::post('logoUpdate', [App\Http\Controllers\api\adminDashboard\HomepageController::class, 'logoUpdate'])->name('admin.homepage.logoUpdate');
            Route::post('banarUpdate', [App\Http\Controllers\api\adminDashboard\HomepageController::class, 'banarUpdate'])->name('admin.homepage.banarUpdate');
            Route::post('sliderUpdate', [App\Http\Controllers\api\adminDashboard\HomepageController::class, 'sliderUpdate'])->name('admin.homepage.sliderUpdate');

            Route::resource('country', App\Http\Controllers\api\adminDashboard\CountryController::class, ['names' => 'admin.country']);
            Route::resource('city', App\Http\Controllers\api\adminDashboard\CityController::class, ['names' => 'admin.city']);
            Route::resource('marketer', App\Http\Controllers\api\adminDashboard\MarketerController::class, ['names' => 'admin.marketer']);
            Route::resource('explainVideos', App\Http\Controllers\api\adminDashboard\ExplainVideosController::class, ['names' => 'admin.explainvideo']);
            Route::resource('course', App\Http\Controllers\api\adminDashboard\CourseController::class, ['names' => 'admin.course']);
            Route::resource('activity', App\Http\Controllers\api\adminDashboard\ActivityController::class, ['names' => 'admin.activity']);

            Route::resource('commonquestion', App\Http\Controllers\api\adminDashboard\CommonQuestionController::class, ['names' => 'admin.commonquestion']);
            Route::get('commonDeleteall', [App\Http\Controllers\api\adminDashboard\CommonQuestionController::class, 'deleteAll'])->name('admin.commonquestion.deleteall');
            Route::get('importOrders', [App\Http\Controllers\api\adminDashboard\AdminOrderController::class, 'index'])->name('admin.adminOrder.index');
            Route::get('showImportOrder/{id}', [App\Http\Controllers\api\adminDashboard\AdminOrderController::class, 'show'])->name('admin.adminOrder.show');
            Route::PUT('updateImportOrder/{id}', [App\Http\Controllers\api\adminDashboard\AdminOrderController::class, 'update'])->name('admin.adminOrder.update');
            Route::get('importordersdeleteall', [App\Http\Controllers\api\adminDashboard\AdminOrderController::class, 'deleteAll'])->name('admin.adminOrder.deleteall');

            Route::resource('platform', App\Http\Controllers\api\adminDashboard\PlatformController::class, ['names' => 'admin.platform']);
            Route::resource('service', App\Http\Controllers\api\adminDashboard\ServiceController::class, ['names' => 'admin.service']);
            Route::get('service/showDetail/{id}', [App\Http\Controllers\api\adminDashboard\ServiceController::class, 'showDetail'])->name('admin.service.showdetail');
            Route::get('service/changeStatus/{id}', [ServiceController::class, 'changeStatus'])->name('admin.service.changeStatus');

            Route::get('servicedeleteall', [App\Http\Controllers\api\adminDashboard\ServiceController::class, 'deleteAll'])->name('admin.service.deleteall');

            Route::get('NotificationIndex', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'index'])->name('admin.notification.index');
            Route::get('NotificationRead', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'read'])->name('admin.notification.read');
            Route::get('NotificationDelete/{id}', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'deleteNotification']);
            Route::get('NotificationDeleteAll', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'deleteNotificationAll'])->name('admin.notification.deleteall');
            Route::get('NotificationShow/{id}', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'show'])->name('admin.notification.show');
            Route::post('addEmail', [App\Http\Controllers\api\adminDashboard\NotificationController::class, 'addEmail'])->name('admin.email.store');

            Route::get('EmailIndex', [App\Http\Controllers\api\adminDashboard\EmailController::class, 'index'])->name('admin.email.index');
            Route::get('EmailDeleteAll', [App\Http\Controllers\api\adminDashboard\EmailController::class, 'deleteEmailAll'])->name('admin.email.deleteEmailAll');
            Route::get('EmailShow/{id}', [App\Http\Controllers\api\adminDashboard\EmailController::class, 'show'])->name('admin.email.show');

            Route::resource('category', App\Http\Controllers\api\adminDashboard\CategoryController::class, ['names' => 'admin.category']);

            Route::get('deleteunit/{id}', [App\Http\Controllers\api\adminDashboard\CourseController::class, 'deleteUnit'])->name('admin.course.deleteunit');

            Route::resource('course', App\Http\Controllers\api\adminDashboard\CourseController::class, ['names' => 'admin.course']);

            Route::resource('shippingtype', App\Http\Controllers\api\adminDashboard\ShippingtypeController::class, ['names' => 'admin.shippingtype']);

            Route::resource('paymenttype', App\Http\Controllers\api\adminDashboard\PaymenttypeController::class, ['names' => 'admin.paymenttype']);
            Route::resource('comment', App\Http\Controllers\api\adminDashboard\CommentController::class, ['names' => 'admin.comment']);
            Route::get('commentchangeSatusall', [App\Http\Controllers\api\adminDashboard\CommentController::class, 'changeSatusAll'])->name('admin.comment.changeSatusall');
            Route::get('commentdeleteall', [App\Http\Controllers\api\adminDashboard\CommentController::class, 'deleteAll'])->name('admin.comment.deleteall');

            Route::resource('page', App\Http\Controllers\api\adminDashboard\PageController::class, ['names' => 'admin.page']);
            Route::post('page-publish', [App\Http\Controllers\api\adminDashboard\PageController::class, 'publish'])->name('admin.page.publish');

            Route::resource('technicalSupport', App\Http\Controllers\api\adminDashboard\TechnicalSupportController::class, ['names' => 'admin.technicalsupport']);
            Route::resource('currency', App\Http\Controllers\api\adminDashboard\CurrencyController::class, ['names' => 'admin.currency']);
            Route::resource('website_socialmedia', App\Http\Controllers\api\adminDashboard\WebsiteSocialmediaController::class, ['names' => 'admin.websitesocialmedia']);
            Route::resource('homepage', App\Http\Controllers\api\adminDashboard\HomepageController::class, ['names' => 'admin.homepage']);
            Route::resource('coupons', App\Http\Controllers\api\adminDashboard\CouponController::class, ['names' => 'admin.coupon']);

            Route::resource('section', App\Http\Controllers\api\adminDashboard\SectionController::class, ['names' => 'admin.section']);
            Route::resource('contact', App\Http\Controllers\api\adminDashboard\ContactController::class);
            Route::resource('replaycontact', App\Http\Controllers\api\adminDashboard\ReplaycontactController::class);

            Route::get('seo', [App\Http\Controllers\api\adminDashboard\SeoController::class, 'index'])->name('admin.seo.index');

            Route::resource('setting', App\Http\Controllers\api\adminDashboard\SettingController::class, ['names' => 'admin.setting']);
            Route::resource('store', App\Http\Controllers\api\adminDashboard\StoreController::class, ['names' => 'admin.store']);
            Route::resource('offer', App\Http\Controllers\api\adminDashboard\OfferController::class);
            Route::resource('product', App\Http\Controllers\api\adminDashboard\ProductController::class, ['names' => 'admin.product']);
            Route::resource('option', App\Http\Controllers\api\adminDashboard\OptionController::class);
            Route::resource('user', App\Http\Controllers\api\adminDashboard\UserController::class, ['names' => 'admin.user']);
            Route::resource('etlobha', App\Http\Controllers\api\adminDashboard\EtlobhaController::class, ['names' => 'admin.etlobha']);

            Route::get('statistics/{id}', [App\Http\Controllers\api\adminDashboard\EtlobhaController::class, 'statistics'])->name('admin.etlobha.statistics');

            Route::post('sectionupdate', [App\Http\Controllers\api\adminDashboard\SectionController::class, 'update'])->name('admin.section.sectionupdate');

            Route::get('storeReport', [App\Http\Controllers\api\adminDashboard\StoreReportController::class, 'index']);
            Route::get('home', [App\Http\Controllers\api\adminDashboard\StoreReportController::class, 'home'])->name('admin.mainpage.index');

            Route::get('registration_status_show', [App\Http\Controllers\api\adminDashboard\SettingController::class, 'registrationStatusShow'])->name('admin.registration.show');
            Route::post('registration_status_update', [App\Http\Controllers\api\adminDashboard\SettingController::class, 'registrationStatusUpdate'])->name('admin.registration.update');

            Route::post('optionsProduct/{id}', [App\Http\Controllers\api\adminDashboard\OptionController::class, 'optionsProduct']);

            Route::resource('websiteorder', App\Http\Controllers\api\adminDashboard\WebsiteorderController::class, ['names' => 'admin.websiteorder']);
            Route::get('websiteorderdeleteall', [App\Http\Controllers\api\adminDashboard\WebsiteorderController::class, 'deleteAll'])->name('admin.websiteorder.deleteall');

            Route::post('acceptService/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteorderController::class, 'acceptService'])->name('admin.websiteorder.acceptService');
            Route::post('rejectService/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteorderController::class, 'rejectService'])->name('admin.websiteorder.rejectService');

            Route::resource('stock', App\Http\Controllers\api\adminDashboard\StockController::class, ['names' => 'admin.stock']);
            // import product
            Route::post('importproducts', [App\Http\Controllers\api\adminDashboard\StockController::class, 'importStockProducts'])->name('admin.stock.importStockProducts');;

            Route::get('stockdeleteall', [App\Http\Controllers\api\adminDashboard\StockController::class, 'deleteAll'])->name('admin.stock.deleteall');;
            Route::get('storechangeSatusall', [App\Http\Controllers\api\adminDashboard\StoreController::class, 'changeSatusAll'])->name('admin.store.changeSatusall');
            Route::get('productchangeSatusall', [App\Http\Controllers\api\adminDashboard\ProductController::class, 'changeSatusAll'])->name('admin.product.changeSatusall');
            Route::get('etlobhachangeSatusall', [App\Http\Controllers\api\adminDashboard\EtlobhaController::class, 'changeStatusAll'])->name('admin.etlobha.changeStatusall');
            Route::get('etlobhadeleteall', [App\Http\Controllers\api\adminDashboard\EtlobhaController::class, 'deleteAll'])->name('admin.etlobha.deleteall');
            Route::get('etlobhachangeSpecial/{id}', [App\Http\Controllers\api\adminDashboard\EtlobhaController::class, 'specialStatus'])->name('admin.etlobha.specialStatus');
            Route::get('productdeleteall', [App\Http\Controllers\api\adminDashboard\ProductController::class, 'deleteAll'])->name('admin.product.deleteall');
            Route::post('addToStore/{id}', [App\Http\Controllers\api\adminDashboard\StockController::class, 'addToStore'])->name('admin.stock.addToStore');

            Route::get('pagechangeSatusall', [App\Http\Controllers\api\adminDashboard\PageController::class, 'changeSatusAll'])->name('admin.page.changesatusall');
            Route::get('pagedeleteall', [App\Http\Controllers\api\adminDashboard\PageController::class, 'deleteAll'])->name('admin.page.deleteall');
            Route::get('userchangeSatusall', [App\Http\Controllers\api\adminDashboard\UserController::class, 'changeSatusAll'])->name('admin.user.changesatusall');
            Route::get('userdeleteall', [App\Http\Controllers\api\adminDashboard\UserController::class, 'deleteAll'])->name('admin.user.deleteall');
            Route::get('couponchangeSatusall', [App\Http\Controllers\api\adminDashboard\CouponController::class, 'changeSatusAll'])->name('admin.coupon.changesatusall');
            Route::get('coupondeleteall', [App\Http\Controllers\api\adminDashboard\CouponController::class, 'deleteAll'])->name('admin.coupon.deleteall');
            Route::get('marketerdeleteall', [App\Http\Controllers\api\adminDashboard\MarketerController::class, 'deleteAll'])->name('admin.marketer.deleteall');
            Route::get('categorychangeSatusall', [App\Http\Controllers\api\adminDashboard\CategoryController::class, 'changeSatusAll'])->name('admin.category.changesatusall');
            Route::get('categorydeleteall', [App\Http\Controllers\api\adminDashboard\CategoryController::class, 'deleteAll'])->name('admin.category.deleteall');
            Route::get('categorystorechangeSatusall', [App\Http\Controllers\api\adminDashboard\StoreCategoryController::class, 'changeSatusAll'])->name('admin.storecategory.changesatusall');
            Route::get('categorystoredeleteall', [App\Http\Controllers\api\adminDashboard\StoreCategoryController::class, 'deleteAll'])->name('admin.storecategory.deleteall');
            Route::get('cityedeleteall', [App\Http\Controllers\api\adminDashboard\CityController::class, 'deleteAll'])->name('admin.city.deleteall');
            Route::get('countryedeleteall', [App\Http\Controllers\api\adminDashboard\CountryController::class, 'deleteAll'])->name('admin.country.deleteall');
            Route::get('currencychangeSatusall', [App\Http\Controllers\api\adminDashboard\CurrencyController::class, 'changeSatusall'])->name('admin.currency.changeSatusall');
            Route::get('packagechangeSatusall', [App\Http\Controllers\api\adminDashboard\PackageController::class, 'changeSatusAll'])->name('admin.package.changeSatusall');
            Route::get('registration_marketer_show', [App\Http\Controllers\api\adminDashboard\SettingController::class, 'registrationMarketerShow'])->name('admin.marketer.registration_marketer_show');
            Route::resource('roles', App\Http\Controllers\api\adminDashboard\RoleController::class, ['names' => 'admin.role']);
            Route::post('addProductNote', [App\Http\Controllers\api\adminDashboard\ProductController::class, 'addNote'])->name('admin.product.addNote');
            Route::get('productchangeSpecial/{id}', [App\Http\Controllers\api\adminDashboard\ProductController::class, 'specialStatus']);
            Route::get('productchangeSpecial', [App\Http\Controllers\api\adminDashboard\ProductController::class, 'specialStatusAll']);
            Route::get('activitydeleteall', [App\Http\Controllers\api\adminDashboard\ActivityController::class, 'deleteAll'])->name('admin.activity.deleteall');

            Route::post('registrationMarketer', [App\Http\Controllers\api\adminDashboard\SettingController::class, 'registrationMarketer'])->name('admin.marketer.registrationMarketer');
            Route::get('contactdeleteall', [App\Http\Controllers\api\adminDashboard\ContactController::class, 'deleteAll']);

            Route::get('verification', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'index'])->name('admin.verification.index');
            Route::get('verificationdeleteall', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'deleteAll'])->name('admin.verification.deleteall');
            Route::post('addStoreNote', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'addNote'])->name('admin.verification.addNote');
            Route::post('addToStoreNote', [App\Http\Controllers\api\adminDashboard\StoreController::class, 'addNote'])->name('admin.store.addNote');
            Route::get('acceptVerification/{id}', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'acceptVerification'])->name('admin.verification.acceptVerification');
            Route::get('specialStatus/{id}', [App\Http\Controllers\api\adminDashboard\StoreController::class, 'specialStatus'])->name('admin.store.specialStatus');
            Route::get('rejectVerification/{id}', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'rejectVerification'])->name('admin.verification.rejectVerification');
            Route::post('verification_update', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'verificationUpdate'])->name('admin.verification.verification_update');
            Route::get('verification/{id}', [App\Http\Controllers\api\adminDashboard\VerificationController::class, 'verificationShow'])->name('admin.verification.verification_show');
            Route::get('subscriptions', [App\Http\Controllers\api\adminDashboard\SubscriptionsController::class, 'index'])->name('admin.subscriptions.index');
            Route::post('addAlert', [App\Http\Controllers\api\adminDashboard\SubscriptionsController::class, 'addAlert'])->name('admin.subscriptions.addAlert');
            Route::get('subscriptionsdeleteall', [App\Http\Controllers\api\adminDashboard\SubscriptionsController::class, 'deleteAll'])->name('admin.subscriptions.deleteall');
            Route::get('subscriptionschangeSatusall', [App\Http\Controllers\api\adminDashboard\SubscriptionsController::class, 'changeSatusAll'])->name('admin.subscriptions.changeSatusall');

            Route::get('permissions', [App\Http\Controllers\api\adminDashboard\PermissionController::class, 'index'])->name('permissions');
            Route::get('atlobhaContact', [App\Http\Controllers\api\adminDashboard\AtlobhaContactController::class, 'index'])->name('admin.atlobhaContact.index');
            Route::get('atlobhaContact/{id}', [App\Http\Controllers\api\adminDashboard\AtlobhaContactController::class, 'show'])->name('admin.atlobhaContact.show');
            Route::get('atlobhaContactdeleteall', [App\Http\Controllers\api\adminDashboard\AtlobhaContactController::class, 'deleteAll'])->name('admin.atlobhaContact.deleteall');
            Route::get('atlobhaContactChangeStatus/{id}', [App\Http\Controllers\api\adminDashboard\AtlobhaContactController::class, 'changeStatus'])->name('admin.atlobhaContact.changeStatus');

            Route::post('updateSeo', [App\Http\Controllers\api\adminDashboard\SeoController::class, 'updateSeo'])->name('admin.seo.update');
            Route::post('updateProfile/{id}', [App\Http\Controllers\api\adminDashboard\StoreController::class, 'updateProfile'])->name('admin.store.updateProfile');

            Route::post('createSupplier', [App\Http\Controllers\api\adminDashboard\SupplierController::class, 'store'])->name('admin.supplier.create');
            Route::get('showSupplier', [App\Http\Controllers\api\adminDashboard\SupplierController::class, 'show'])->name('admin.supplier.show');
            Route::post('updateSupplier', [App\Http\Controllers\api\adminDashboard\SupplierController::class, 'update'])->name('admin.supplier.update');
            Route::get('showSupplierDashboard', [App\Http\Controllers\api\adminDashboard\SupplierController::class, 'showSupplierDashboard'])->name('admin.supplier.showSupplierDashboard');
            Route::post('uploadSupplierDocument', [App\Http\Controllers\api\adminDashboard\SupplierController::class, 'uploadSupplierDocument'])->name('admin.supplier.uploadSupplierDocument');
            Route::get('indexSupplier', [App\Http\Controllers\api\adminDashboard\SupplierController::class, 'index'])->name('admin.supplier.indexSupplier');
            Route::get('billing', [App\Http\Controllers\api\adminDashboard\SupplierController::class, 'billing'])->name('admin.supplier.billing');
        });
    });
});
Auth::routes();

Route::middleware([StoreUser::class])->group(function () {
    Route::prefix('/Store')->group(function () {

        Route::resource('country', App\Http\Controllers\api\storeDashboard\CountryController::class);
        Route::resource('city', App\Http\Controllers\api\storeDashboard\CityController::class);

        Route::resource('pagecategory', App\Http\Controllers\api\storeDashboard\PageCategoryController::class);
        Route::get('changePageCategoryStatus/{id}', [App\Http\Controllers\api\storeDashboard\PageCategoryController::class, 'changeStatus']);

        Route::resource('video', App\Http\Controllers\api\storeDashboard\VideoController::class);
        Route::get('changeUnitStatus/{id}', [App\Http\Controllers\api\storeDashboard\UnitController::class, 'changeStatus']);
        Route::resource('unit', App\Http\Controllers\api\storeDashboard\UnitController::class);

        Route::get('existComment', [App\Http\Controllers\api\storeDashboard\EtlobhaController::class, 'existComment']);

        Route::get('changeReplaycommentStatus/{id}', [App\Http\Controllers\api\storeDashboard\ReplaycommentController::class, 'changeStatus']);
        //profile
        Route::get('profile', [App\Http\Controllers\api\storeDashboard\ProfileController::class, 'index']);
        Route::post('profile', [App\Http\Controllers\api\storeDashboard\ProfileController::class, 'update']);
        // selector
        Route::get('selector/packages', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'packages']);
        Route::get('selector/products', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'products']);
        Route::get('selector/payment_types', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'paymentTypes']);
        Route::get('selector/productImportproduct', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'storeImportProduct']);

        Route::get('selector/auth_user', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'authUser']);
        Route::get('selector/cities', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'cities']);
        Route::get('selector/countries', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'countries']);
        Route::get('selector/activities', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'activities']);
        Route::get('selector/mainCategories', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'mainCategories']);
        Route::get('selector/productCategories', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'productCategories']);

        Route::get('selector/etlobahCategory', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'etlobahCategory']);
        Route::get('selector/storeCategory', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'storeCategory']);
        Route::get('selector/services', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'services']);
        Route::get('selector/children/{id}', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'children']);
        Route::get('selector/roles', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'roles']);
        Route::get('selector/subcategories', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'subCategories']);

        Route::get('selector/page-categories', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'pagesCategory']);
        Route::get('selector/post-categories', [App\Http\Controllers\api\storeDashboard\SelectorController::class, 'postCategories']);
        //search
        Route::get('searchCategory', [App\Http\Controllers\api\storeDashboard\CategoryController::class, 'searchCategoryName']);
        Route::get('searchCategoryEtlobha', [App\Http\Controllers\api\storeDashboard\CategoryController::class, 'searchCategoryEtlobha']);
        Route::get('searchProduct', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'searchProductName']);
        Route::get('searchImportProduct', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'searchImportProductName']);
        Route::get('searchOrder', [App\Http\Controllers\api\storeDashboard\OrderController::class, 'searchOrder']);
        Route::get('searchCouponName', [App\Http\Controllers\api\storeDashboard\CouponController::class, 'searchCouponName']);
        Route::get('searchCartName', [App\Http\Controllers\api\storeDashboard\CartController::class, 'searchCartName']);
        Route::get('searchPageName', [App\Http\Controllers\api\storeDashboard\PageController::class, 'searchPageName']);
        Route::get('searchCourseName', [App\Http\Controllers\api\storeDashboard\CourseController::class, 'searchCourseName']);
        Route::get('searchTechnicalSupport', [App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class, 'searchTechnicalSupport']);
        Route::get('explainVideoName', [App\Http\Controllers\api\storeDashboard\ExplainVideosController::class, 'explainVideoName']);

        //couponall
        Route::get('couponchangeSatusItems', [App\Http\Controllers\api\storeDashboard\CouponController::class, 'changeSatusItems']);
        Route::get('coupondeleteItems', [App\Http\Controllers\api\storeDashboard\CouponController::class, 'deleteItems']);
        // Route::middleware([CheckStorePermission::class])->group(function () {
        //cart
        Route::get('cartShow/{id}', [App\Http\Controllers\api\storeDashboard\CartController::class, 'show'])->name('abandoned.carts.show');
        Route::get('admin', [App\Http\Controllers\api\storeDashboard\CartController::class, 'admin'])->name('abandoned.carts');
        // Route::post('addCart', [App\Http\Controllers\api\storeDashboard\CartController::class, 'addToCart']);
        Route::get('deleteCart', [App\Http\Controllers\api\storeDashboard\CartController::class, 'delete'])->name('abandoned.carts.delete');
        Route::post('sendOfferCart/{id}', [App\Http\Controllers\api\storeDashboard\CartController::class, 'sendOffer'])->name('abandoned.carts.sendoffer');
        //  import cart
        Route::get('showImportCart', [App\Http\Controllers\api\storeDashboard\ImportCartController::class, 'index']);
        Route::post('addImportCart', [App\Http\Controllers\api\storeDashboard\ImportCartController::class, 'addToCart']);
        Route::get('deleteImportCart/{id}', [App\Http\Controllers\api\storeDashboard\ImportCartController::class, 'delete']);
        // cheackout import
        Route::post('checkoutImport', [App\Http\Controllers\api\storeDashboard\CheckoutController::class, 'checkOut']);
        Route::post('applyCoupon/{cart_id}', [App\Http\Controllers\api\storeDashboard\CheckoutController::class, 'applyCoupon']);

        //  paymenttype import
        Route::get('paymentmethodsImport', [App\Http\Controllers\api\storeDashboard\CheckoutController::class, 'paymentMethods']);
        Route::get('shippingMethodsImport', [App\Http\Controllers\api\storeDashboard\CheckoutController::class, 'shippingMethods']);

        // page
        Route::resource('page', App\Http\Controllers\api\storeDashboard\PageController::class, ['names' => 'store.pages']);
        Route::post('page-publish', [App\Http\Controllers\api\storeDashboard\PageController::class, 'publish'])->name('store.pages.publish');
        Route::get('pagechangeSatusall', [App\Http\Controllers\api\storeDashboard\PageController::class, 'changeSatusAll'])->name('store.pages.changestatusall');
        Route::get('pagedeleteall', [App\Http\Controllers\api\storeDashboard\PageController::class, 'deleteAll'])->name('store.pages.deleteall');
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
        Route::post('themePrimaryUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themePrimaryUpdate'])->name('store.template.themeSearchUpdate');
        Route::post('themeSecondaryUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeSecondaryUpdate'])->name('store.template.themeCategoriesUpdate');
        Route::post('themeHeaderUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeHeaderUpdate'])->name('store.template.themeMenuUpdate');
        Route::post('themeLayoutUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeLayoutUpdate'])->name('store.template.themeLayoutUpdate');
        Route::post('themeIconUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeIconUpdate'])->name('store.template.themeIconUpdate');
        Route::post('themeProductUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeProductUpdate'])->name('store.template.themeProductUpdate');
        Route::post('themeFilterUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeFilterUpdate'])->name('store.template.themeFilterUpdate');
        Route::post('themeMainUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeMainUpdate'])->name('store.template.themeMainUpdate');
        Route::post('themeSubUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeSubUpdate'])->name('store.template.themeSubUpdate');
        Route::post('themeFooterUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeFooterUpdate'])->name('store.template.themeFooterUpdate');
        Route::post('themeFontColorUpdate', [App\Http\Controllers\api\storeDashboard\HomepageController::class, 'themeFontColorUpdate']);

        // maintenance
        Route::resource('maintenance', App\Http\Controllers\api\storeDashboard\MaintenanceController::class, ['names' => 'store.maintenancemode']);
        Route::post('updateMaintenance', [App\Http\Controllers\api\storeDashboard\MaintenanceController::class, 'updateMaintenance'])->name('store.maintenancemode.updatemaintenance');

        // product
        Route::resource('product', App\Http\Controllers\api\storeDashboard\ProductController::class, ['names' => 'store.products']);
        Route::post('import-products', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'importProducts'])->name('store.products.importfile');
        Route::get('productchangeSatusall', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'changeSatusAll'])->name('store.products.changestatusall');
        Route::get('productdeleteall', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'deleteAll'])->name('store.products.deleteall');
        Route::get('deleteItems', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'deleteItems'])->name('store.products.deleteItems');
        Route::post('updateCategory', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'updateCategory'])->name('store.products.updateCategory');

        Route::get('products', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'products'])->name('store.products.products');
        Route::get('importedProducts', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'importedProducts']);
        Route::get('changeProductStatus/{id}', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'changeStatus'])->name('store.products.activate');
        Route::get('deleteImport/{product}', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'deleteImport'])->name('store.products.deleteimport');
        Route::post('duplicateProduct/{product}', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'duplicateProduct'])->name('store.products.duplicateproduct');
        Route::get('specialStatus/{id}', [App\Http\Controllers\api\storeDashboard\ProductController::class, 'specialStatus'])->name('store.products.specialStatus');
        Route::post('importcities', [App\Http\Controllers\api\storeDashboard\CityController::class, 'importcities']);

        // importProduct
        Route::get('etlobhaShow', [App\Http\Controllers\api\storeDashboard\ImportproductController::class, 'etlobhaShow'])->name('store.products.etlobhaShow');
        Route::get('etlobhaProductShow/{id}', [App\Http\Controllers\api\storeDashboard\ImportproductController::class, 'show'])->name('store.products.etlbhasingleproduct');

        Route::post('importproduct', [App\Http\Controllers\api\storeDashboard\ImportproductController::class, 'store'])->name('store.products.import');
        Route::post('updateimportproduct/{id}', [App\Http\Controllers\api\storeDashboard\ImportproductController::class, 'updateImportProduct'])->name('store.products.updateimport');
        // category
        Route::resource('category', App\Http\Controllers\api\storeDashboard\CategoryController::class, ['names' => 'store.categories']);
        Route::get('categoryStorechangeSatusall', [App\Http\Controllers\api\storeDashboard\CategoryController::class, 'changeSatusAll'])->name('store.categories.changestatusall');
        Route::get('categoryStoredeleteall', [App\Http\Controllers\api\storeDashboard\CategoryController::class, 'deleteAll'])->name('store.categories.deleteall');
        Route::get('categoryStoredeleteItems', [App\Http\Controllers\api\storeDashboard\CategoryController::class, 'deleteItems'])->name('store.categories.deleteItems');

        Route::get('changeCategoryStatus/{id}', [App\Http\Controllers\api\storeDashboard\CategoryController::class, 'changeStatus'])->name('store.categories.activate');
        // coupon
        Route::get('changeCouponStatus/{id}', [App\Http\Controllers\api\storeDashboard\CouponController::class, 'changeStatus'])->name('store.copons.activate');
        Route::resource('coupons', App\Http\Controllers\api\storeDashboard\CouponController::class, ['names' => 'store.copons']);
        Route::get('couponchangeSatusall', [App\Http\Controllers\api\storeDashboard\CouponController::class, 'changeSatusAll'])->name('store.copons.changestatusall');
        Route::get('coupondeleteall', [App\Http\Controllers\api\storeDashboard\CouponController::class, 'deleteAll'])->name('store.copons.deleteall');
        //  التوثيق
        Route::get('verification_show', [App\Http\Controllers\api\storeDashboard\VerificationController::class, 'verificationShow'])->name('store.verification.show');
        Route::post('verification_update', [App\Http\Controllers\api\storeDashboard\VerificationController::class, 'verificationUpdate'])->name('store.verification.add');
        // social media
        Route::get('socialMedia_store_show', [App\Http\Controllers\api\storeDashboard\storeInformationController::class, 'socialMediaStoreShow'])->name('store.socialmedia.show');
        Route::post('socialMedia_store_update', [App\Http\Controllers\api\storeDashboard\storeInformationController::class, 'socialMediaStoreUpdate'])->name('store.socialmedia.update');

        // technical Support
        Route::resource('technicalSupport', App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class, ['names' => 'store.technicalsupport']);
        Route::get('technicalSupportStoredeleteall', [App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class, 'deleteAll'])->name('store.technicalsupport.deleteall');
        Route::get('changeTechnicalSupportStatus/{id}', [App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class, 'changeStatus'])->name('store.technicalsupport.cahngestatus');
        Route::post('replayTechnicalSupport', [App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class, 'replay'])->name('store.technicalsupport.replay');
        // Shipping company
        Route::get('changeShippingtypeStatus/{id}', [App\Http\Controllers\api\storeDashboard\ShippingtypeController::class, 'changeStatus'])->name('store.shippingcompanies.activate');
        Route::resource('shippingtype', App\Http\Controllers\api\storeDashboard\ShippingtypeController::class, ['names' => 'store.shippingcompanies']);
        Route::post('updatePrice/{id}', [App\Http\Controllers\api\storeDashboard\ShippingtypeController::class, 'updatePrice']);

        // payment type
        Route::get('changePaymenttypeStatus/{id}', [App\Http\Controllers\api\storeDashboard\PaymenttypeController::class, 'changeStatus'])->name('store.paymentsgateways.activate');
        Route::resource('paymenttype', App\Http\Controllers\api\storeDashboard\PaymenttypeController::class, ['names' => 'store.paymentsgateways']);
        //offer
        // Route::get('changeOfferStatus/{id}', [App\Http\Controllers\api\storeDashboard\OfferController::class, 'changeStatus']);
        // Route::resource('offer', App\Http\Controllers\api\storeDashboard\OfferController::class);
        // SEO keywords
        // Route::resource('seo', App\Http\Controllers\api\storeDashboard\SeoController::class, ['names' => 'store.keywords']);
        // Route::post('updateSeo', [App\Http\Controllers\api\storeDashboard\SeoController::class, 'updateSeo'])->name('store.keywords.updateseo');
        // Route::post('updateLink', [App\Http\Controllers\api\storeDashboard\SeoController::class, 'updateLink'])->name('store.keywords.updatelink');
        // Route::post('updateRobots', [App\Http\Controllers\api\storeDashboard\SeoController::class, 'updateRobots'])->name('store.keywords.updaterobots');

        //  clients
        // Route::resource('client',App\Http\Controllers\api\storeDashboard\ClientController::class);
        // Route::get('changeClientStatus/{id}', [App\Http\Controllers\api\storeDashboard\ClientController::class, 'changeStatus']);
        // Route::get('clientdeleteall', [App\Http\Controllers\api\storeDashboard\ClientController::class, 'deleteAll']);
        //
        Route::resource('homepage', App\Http\Controllers\api\storeDashboard\HomepageController::class, ['names' => 'store.template']);
        // comments
        Route::resource('comment', App\Http\Controllers\api\storeDashboard\CommentController::class, ['names' => 'store.comments']);
        Route::get('commentchangeSatusall', [App\Http\Controllers\api\storeDashboard\CommentController::class, 'changeSatusAll'])->name('store.comments.changestatusall');
        Route::post('replaycomment', [App\Http\Controllers\api\storeDashboard\CommentController::class, 'replayComment'])->name('store.comments.replaycomment');
        Route::get('changeCommentStatus/{id}', [App\Http\Controllers\api\storeDashboard\CommentController::class, 'changeStatus'])->name('store.comments.activate');
        Route::get('commentActivation', [App\Http\Controllers\api\storeDashboard\CommentController::class, 'commentActivation'])->name('store.comments.activateall');

        Route::post('etlobhaComment', [App\Http\Controllers\api\storeDashboard\EtlobhaController::class, 'etlobhaComment'])->name('store.etlbhacomment.add');

        // users
        Route::resource('user', App\Http\Controllers\api\storeDashboard\UserController::class, ['names' => 'store.users']);
        Route::get('changeuserStatus/{id}', [App\Http\Controllers\api\storeDashboard\UserController::class, 'changeStatus'])->name('store.users.activate');
        Route::get('userchangeSatusall', [App\Http\Controllers\api\storeDashboard\UserController::class, 'changeSatusAll'])->name('store.users.changestatusall');
        Route::get('userdeleteall', [App\Http\Controllers\api\storeDashboard\UserController::class, 'deleteAll'])->name('store.users.deleteall');
        Route::get('userdeleteItems', [App\Http\Controllers\api\storeDashboard\UserController::class, 'deleteItems'])->name('store.users.deleteItems');

        //  setting
        Route::get('setting_store_show', [App\Http\Controllers\api\storeDashboard\SettingController::class, 'settingStoreShow'])->name('store.basicdata.show');
        Route::post('setting_store_update', [App\Http\Controllers\api\storeDashboard\SettingController::class, 'settingStoreUpdate'])->name('store.basicdata.update');
        Route::get('checkDomain', [App\Http\Controllers\api\storeDashboard\SettingController::class, 'checkDomain']);
        // notifications
        Route::get('NotificationIndex', [App\Http\Controllers\api\storeDashboard\NotificationController::class, 'index'])->name('store.notifications.NotificationIndex');
        Route::get('NotificationRead', [App\Http\Controllers\api\storeDashboard\NotificationController::class, 'read'])->name('store.notifications.NotificationRead');
        Route::get('NotificationDelete/{id}', [App\Http\Controllers\api\storeDashboard\NotificationController::class, 'deleteNotification'])->name('store.notifications.NotificationDelete');
        Route::get('NotificationDeleteAll', [App\Http\Controllers\api\storeDashboard\NotificationController::class, 'deleteNotificationAll'])->name('store.notifications.NotificationDeleteAll');
        Route::get('NotificationShow/{id}', [App\Http\Controllers\api\storeDashboard\NotificationController::class, 'show'])->name('store.notifications.NotificationShow');
        Route::get('countUnRead', [App\Http\Controllers\api\storeDashboard\NotificationController::class, 'countUnRead'])->name('store.notifications.countUnRead');
        //  Etlobha services
        Route::get('etlobhaservice/show', [App\Http\Controllers\api\storeDashboard\EtlobhaserviceController::class, 'show'])->name('store.platformservices.show');
        Route::post('etlobhaservice', [App\Http\Controllers\api\storeDashboard\EtlobhaserviceController::class, 'store'])->name('store.platformservices.add');
        Route::get('marketerRequest', [App\Http\Controllers\api\storeDashboard\EtlobhaserviceController::class, 'marketerRequest'])->name('store.platformservices.marketerRequest');

        //  payment
        Route::post('payment', [App\Http\Controllers\api\storeDashboard\PaymentController::class, 'payment']);
        // order
        Route::resource('orders', App\Http\Controllers\api\storeDashboard\OrderController::class, ['names' => 'store.orders']);
        Route::get('index', [App\Http\Controllers\api\storeDashboard\IndexController::class, 'index'])->name('store.homepage.show');
        Route::get('ordersdeleteall', [App\Http\Controllers\api\storeDashboard\OrderController::class, 'deleteAll'])->name('store.orders.deleteall');
        Route::get('permissions', [App\Http\Controllers\api\storeDashboard\PermissionController::class, 'index'])->name('permissions');
        Route::resource('roles', App\Http\Controllers\api\storeDashboard\RoleController::class, ['names' => 'store.roles']);
        Route::get('tracking/{id}', [App\Http\Controllers\api\storeDashboard\OrderController::class, 'tracking']);

        // reports
        Route::get('reports', [ReportController::class, 'index'])->name('store.reports.show');

        // subsicription

        Route::get('subsicriptions', [SubscriptionEmailController::class, 'index'])->name('store.subsicriptions.show');
        Route::get('subsicriptionsdeleteall', [SubscriptionEmailController::class, 'deleteAll'])->name('store.subsicriptions.deleteall');
        Route::get('searchSubscriptionEmail', [SubscriptionEmailController::class, 'searchSubscriptionEmail'])->name('store.subsicriptions.searchSubscriptionEmail');

        // website seo
        Route::resource('seo', App\Http\Controllers\api\storeDashboard\SeoController::class, ['names' => 'store.seo']);
        Route::post('updateSeo', [App\Http\Controllers\api\storeDashboard\SeoController::class, 'updateSeo'])->name('store.seo.updateGoogleAnalytics');
        //Supplier
        Route::post('createSupplier', [App\Http\Controllers\api\storeDashboard\SupplierController::class, 'store'])->name('store.supplier.create');
        Route::get('showSupplier', [App\Http\Controllers\api\storeDashboard\SupplierController::class, 'show'])->name('store.supplier.show');
        Route::post('updateSupplier', [App\Http\Controllers\api\storeDashboard\SupplierController::class, 'update'])->name('store.supplier.update');
        Route::get('showSupplierDashboard', [App\Http\Controllers\api\storeDashboard\SupplierController::class, 'showSupplierDashboard'])->name('store.supplier.showSupplierDashboard');
        Route::post('uploadSupplierDocument', [App\Http\Controllers\api\storeDashboard\SupplierController::class, 'uploadSupplierDocument'])->name('store.supplier.uploadSupplierDocument');
        Route::get('indexSupplier', [App\Http\Controllers\api\storeDashboard\SupplierController::class, 'index'])->name('store.supplier.indexSupplier');
        Route::get('billing', [App\Http\Controllers\api\storeDashboard\SupplierController::class, 'billing'])->name('store.supplier.billing');
        Route::get('showBilling/{id}', [App\Http\Controllers\api\storeDashboard\SupplierController::class, 'showBilling'])->name('admin.supplier.showBilling');

        //
        Route::get('returnOrderIndex', [App\Http\Controllers\api\storeDashboard\ReturnOrderController::class, 'index']);
        Route::post('returnOrder/{id}', [App\Http\Controllers\api\storeDashboard\ReturnOrderController::class, 'update']);
        Route::get('returnOrder/{id}', [App\Http\Controllers\api\storeDashboard\ReturnOrderController::class, 'show']);
        Route::get('searchReturnOrder', [App\Http\Controllers\api\storeDashboard\ReturnOrderController::class, 'searchReturnOrder']);
        Route::get('refundReturnOrder/{id}', [App\Http\Controllers\api\storeDashboard\ReturnOrderController::class, 'refundReturnOrder']);

        // });
    });
});
