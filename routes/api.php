<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;

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




Route::post('/social-mobile', 'App\Http\Controllers\api\AuthController@social_mobile');


Route::post('/loginapi','App\Http\Controllers\api\AuthController@login');
Route::post('/registerapi','App\Http\Controllers\api\AuthController@register');
Route::get('/logout','App\Http\Controllers\api\AuthController@logout');
//  index Ettlobha page
Route::get('index',[App\Http\Controllers\api\IndexEtlobhaController::class,'index']);



Route::post('send-verify-message','App\Http\Controllers\api\AuthController@store_verify_message');
Route::post('verify-user','App\Http\Controllers\api\AuthController@verifyUser');



Route::group([
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'App\Http\Controllers\api\PasswordResetController@create');
    Route::get('find/{token}', 'App\Http\Controllers\api\PasswordResetController@find');
    Route::post('verify', 'App\Http\Controllers\api\PasswordResetController@verifyContact');
    Route::post('reset', 'App\Http\Controllers\api\PasswordResetController@reset');
});

// change status routers
Route::middleware([AdminUser::class])->group(function(){
    Route::prefix('/Admin')->group(function ()  {

Route::get('profile',[App\Http\Controllers\api\adminDashboard\ProfileController::class,'index']);
Route::post('profile',[App\Http\Controllers\api\adminDashboard\ProfileController::class,'update']);

Route::post('changeCountryStatus/{id}',[App\Http\Controllers\api\adminDashboard\CountryController::class,'changeStatus']);
Route::post('changeCityStatus/{id}',[App\Http\Controllers\api\adminDashboard\CityController::class,'changeStatus']);
Route::post('changeMarketerStatus/{id}', [App\Http\Controllers\api\adminDashboard\MarketerController::class,'changeStatus']);
Route::post('changeExplainVideosStatus/{id}', [App\Http\Controllers\api\adminDashboard\ExplainVideosController::class,'changeStatus']);
Route::post('changeCourseStatus/{id}', [App\Http\Controllers\api\adminDashboard\CourseController::class,'changeStatus']);
Route::post('changeUnitStatus/{id}', [App\Http\Controllers\api\adminDashboard\UnitController::class,'changeStatus']);
Route::post('changeVideoStatus/{id}', [App\Http\Controllers\api\adminDashboard\VideoController::class,'changeStatus']);
Route::post('changeActivityStatus/{id}',[App\Http\Controllers\api\adminDashboard\ActivityController::class,'changeStatus']);
Route::post('changePlatformStatus/{id}',[App\Http\Controllers\api\adminDashboard\PlatformController::class,'changeStatus']);
Route::post('changeServiceStatus/{id}', [App\Http\Controllers\api\adminDashboard\ServiceController::class,'changeStatus']);
Route::post('changeCategoryStatus/{id}', [App\Http\Controllers\api\adminDashboard\CategoryController::class,'changeStatus']);
Route::post('changeStoreCategoryStatus/{id}', [App\Http\Controllers\api\adminDashboard\CategoryController::class,'changeStatus']);
Route::post('changeShippingtypeStatus/{id}', [App\Http\Controllers\api\adminDashboard\ShippingtypeController::class,'changeStatus']);
Route::post('changePaymenttypeStatus/{id}', [App\Http\Controllers\api\adminDashboard\PaymenttypeController::class,'changeStatus']);
Route::post('changeCommentStatus/{id}', [App\Http\Controllers\api\adminDashboard\CommentController::class,'changeStatus']);
Route::post('changeReplaycommentStatus/{id}', [App\Http\Controllers\api\adminDashboard\ReplaycommentController::class,'changeStatus']);
Route::post('changeMaintenanceStatus/{id}', [App\Http\Controllers\api\adminDashboard\MaintenanceController::class,'changeStatus']);
Route::post('changePageStatus/{id}', [App\Http\Controllers\api\adminDashboard\PageController::class,'changeStatus']);
Route::post('changePageCategoryStatus/{id}', [App\Http\Controllers\api\adminDashboard\PageCategoryController::class,'changeStatus']);
Route::post('changeTechnicalSupportStatus/{id}', [App\Http\Controllers\api\adminDashboard\TechnicalSupportController::class,'changeStatus']);
Route::post('changeCurrencyStatus/{id}', [App\Http\Controllers\api\adminDashboard\CurrencyController::class,'changeStatus']);
Route::post('changewebsite_socialmediaStatus/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteSocialmediaController::class,'changeStatus']);
Route::post('changeHomepageStatus/{id}', [App\Http\Controllers\api\adminDashboard\HomepageController::class,'changeStatus']);
Route::post('changePlaneStatus/{id}', [App\Http\Controllers\api\adminDashboard\PlanController::class,'changeStatus']);
Route::post('changePackageStatus/{id}', [App\Http\Controllers\api\adminDashboard\PackageController::class,'changeStatus']);
Route::post('changeTemplateStatus/{id}', [App\Http\Controllers\api\adminDashboard\TemplateController::class,'changeStatus']);
Route::post('changeCouponStatus/{id}', [App\Http\Controllers\api\adminDashboard\CouponController::class,'changeStatus']);
Route::post('changePackagecouponStatus/{id}', [App\Http\Controllers\api\adminDashboard\PackagecouponController::class,'changeStatus']);
Route::post('changeSectionStatus/{id}', [App\Http\Controllers\api\adminDashboard\SectionController::class,'changeStatus']);
Route::post('changeSettingStatus/{id}', [App\Http\Controllers\api\adminDashboard\SettingController::class,'changeStatus']);
Route::post('changeReplaycontactStatus/{id}', [App\Http\Controllers\api\adminDashboard\ReplaycontactController::class,'changeStatus']);
Route::post('changeContactStatus/{id}', [App\Http\Controllers\api\adminDashboard\ContactController::class,'changeStatus']);
Route::post('changeSeoStatus/{id}', [App\Http\Controllers\api\adminDashboard\SeoController::class,'changeStatus']);
Route::post('changeSettingStatus/{id}', [App\Http\Controllers\api\adminDashboard\SettingController::class,'changeStatus']);
Route::post('changeStoreStatus/{id}', [App\Http\Controllers\api\adminDashboard\StoreController::class,'changeStatus']);
Route::post('changeOfferStatus/{id}', [App\Http\Controllers\api\adminDashboard\OfferController::class,'changeStatus']);
Route::post('changeProductStatus/{id}', [App\Http\Controllers\api\adminDashboard\ProductController::class,'changeStatus']);
Route::post('changeOptionStatus/{id}', [App\Http\Controllers\api\adminDashboard\OptionController::class,'changeStatus']);
Route::post('changeHomeStatus/{name}/{id}', [App\Http\Controllers\api\adminDashboard\HomepageController::class,'changeHomeStatus']);
Route::post('changeWebsiteorderStatus/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,'changeStatus']);
Route::post('changeclientStatus/{id}',[App\Http\Controllers\api\adminDashboard\ClientController::class,'changeStatus']);
Route::post('changeuserStatus/{id}',[App\Http\Controllers\api\adminDashboard\UserController::class,'changeStatus']);
// home page
Route::post('logoUpdate',[App\Http\Controllers\api\adminDashboard\HomepageController::class,'logoUpdate']);
Route::post('panarUpdate',[App\Http\Controllers\api\adminDashboard\HomepageController::class,'panarUpdate']);
Route::post('sliderUpdate',[App\Http\Controllers\api\adminDashboard\HomepageController::class,'sliderUpdate']);

Route::resource('country',App\Http\Controllers\api\adminDashboard\CountryController::class);
Route::resource('city',App\Http\Controllers\api\adminDashboard\CityController::class);
Route::resource('marketer',App\Http\Controllers\api\adminDashboard\MarketerController::class);
Route::resource('client',App\Http\Controllers\api\adminDashboard\ClientController::class);
Route::resource('explainVideos',App\Http\Controllers\api\adminDashboard\ExplainVideosController::class);
Route::resource('course',App\Http\Controllers\api\adminDashboard\CourseController::class);
Route::resource('unit',App\Http\Controllers\api\adminDashboard\UnitController::class);
Route::resource('video',App\Http\Controllers\api\adminDashboard\VideoController::class);
Route::resource('activity',App\Http\Controllers\api\adminDashboard\ActivityController::class);
Route::resource('platform',App\Http\Controllers\api\adminDashboard\PlatformController::class);
Route::resource('service',App\Http\Controllers\api\adminDashboard\ServiceController::class);

Route::get('servicedeleteall',[App\Http\Controllers\api\adminDashboard\ServiceController::class,'deleteall']);

Route::get('NotificationIndex',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'index']);
Route::get('NotificationRead/{id}',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'read']);
Route::get('NotificationDelete/{id}',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'deleteNotification']);
Route::get('NotificationDeleteAll',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'deleteNotificationAll']);
Route::get('NotificationShow/{id}',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'show']);
Route::post('addEmail',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'addEmail']);

Route::get('EmailIndex',[App\Http\Controllers\api\adminDashboard\EmailController::class,'index']);
Route::get('EmailDelete/{id}',[App\Http\Controllers\api\adminDashboard\EmailController::class,'deleteEmail']);
Route::get('EmailDeleteAll',[App\Http\Controllers\api\adminDashboard\EmailController::class,'deleteEmailAll']);
Route::get('EmailShow/{id}',[App\Http\Controllers\api\adminDashboard\EmailController::class,'show']);
Route::post('addEmail',[App\Http\Controllers\api\adminDashboard\EmailController::class,'addEmail']);





Route::resource('category',App\Http\Controllers\api\adminDashboard\CategoryController::class);
Route::resource('storecategory',App\Http\Controllers\api\adminDashboard\StoreCategoryController::class);
Route::post('addvideo',[App\Http\Controllers\api\adminDashboard\CourseController::class,'addvideo']);
Route::post('deletevideo',[App\Http\Controllers\api\adminDashboard\CourseController::class,'deletevideo']);


Route::resource('course',App\Http\Controllers\api\adminDashboard\CourseController::class);

Route::resource('shippingtype',App\Http\Controllers\api\adminDashboard\ShippingtypeController::class);
Route::resource('paymenttype',App\Http\Controllers\api\adminDashboard\PaymenttypeController::class);
Route::resource('comment',App\Http\Controllers\api\adminDashboard\CommentController::class);
Route::resource('page',App\Http\Controllers\api\adminDashboard\pageController::class);
Route::post('publish',[App\Http\Controllers\api\adminDashboard\pageController::class,'publish']);
Route::resource('pagecategory',App\Http\Controllers\api\adminDashboard\PageCategoryController::class);
Route::resource('technicalSupport',App\Http\Controllers\api\adminDashboard\TechnicalSupportController::class);
Route::resource('currency',App\Http\Controllers\api\adminDashboard\CurrencyController::class);
Route::resource('website_socialmedia',App\Http\Controllers\api\adminDashboard\WebsiteSocialmediaController::class);
Route::resource('homepage',App\Http\Controllers\api\adminDashboard\HomepageController::class);
Route::resource('plan',App\Http\Controllers\api\adminDashboard\PlanController::class);
Route::resource('package',App\Http\Controllers\api\adminDashboard\PackageController::class);
Route::resource('template',App\Http\Controllers\api\adminDashboard\TemplateController::class);
Route::resource('coupons',App\Http\Controllers\api\adminDashboard\CouponController::class);
Route::resource('packagecoupon',App\Http\Controllers\api\adminDashboard\PackagecouponController::class);
Route::resource('notification',App\Http\Controllers\api\adminDashboard\NotificationController::class);
Route::resource('notification_type',App\Http\Controllers\api\adminDashboard\Notification_typesController::class);
Route::resource('section',App\Http\Controllers\api\adminDashboard\SectionController::class);
Route::resource('contact',App\Http\Controllers\api\adminDashboard\ContactController::class);
Route::resource('replaycontact',App\Http\Controllers\api\adminDashboard\ReplaycontactController::class);
Route::resource('seo',App\Http\Controllers\api\adminDashboard\SeoController::class);
Route::resource('setting',App\Http\Controllers\api\adminDashboard\SettingController::class);
Route::resource('store',App\Http\Controllers\api\adminDashboard\StoreController::class);
Route::resource('offer',App\Http\Controllers\api\adminDashboard\OfferController::class);
Route::resource('product',App\Http\Controllers\api\adminDashboard\ProductController::class);
Route::resource('option',App\Http\Controllers\api\adminDashboard\OptionController::class);
Route::resource('user',App\Http\Controllers\api\adminDashboard\UserController::class);
Route::resource('etlobha',App\Http\Controllers\api\adminDashboard\EtlobhaController::class);
//Route::delete('etlobha/{id}',App\Http\Controllers\api\adminDashboard\EtlobhaController::class);
Route::post('sectionupdate',[App\Http\Controllers\api\adminDashboard\SectionController::class,'update']);

Route::post('storeReport', [App\Http\Controllers\api\adminDashboard\StoreReportController::class,'index']);
Route::get('registration_status_show', [App\Http\Controllers\api\adminDashboard\SettingController::class,'registration_status_show']);
Route::post('registration_status_update', [App\Http\Controllers\api\adminDashboard\SettingController::class,'registration_status_update']);

Route::post('optionsProduct/{id}', [App\Http\Controllers\api\adminDashboard\OptionController::class,'optionsProduct']);

Route::resource('websiteorder',App\Http\Controllers\api\adminDashboard\WebsiteorderController::class);
Route::get('websiteorderdeleteall',[App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,'deleteall']);

Route::post('acceptStore/{id}',[App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,'acceptStore']);
Route::post('rejectStore/{id}',[App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,'rejectStore']);
Route::post('acceptService/{id}',[App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,'acceptService']);
Route::post('rejectService/{id}',[App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,'rejectService']);





Route::resource('stock',App\Http\Controllers\api\adminDashboard\StockController::class);
Route::get('stockdeleteall',[App\Http\Controllers\api\adminDashboard\StockController::class,'deleteall']);
Route::get('storechangeSatusall',[App\Http\Controllers\api\adminDashboard\StoreController::class,'changeSatusall']);
Route::get('productchangeSatusall',[App\Http\Controllers\api\adminDashboard\ProductController::class,'changeSatusall']);
Route::get('etlobhachangeSatusall',[App\Http\Controllers\api\adminDashboard\EtlobhaController::class,'changeStatusall']);
Route::get('etlobhadeleteall',[App\Http\Controllers\api\adminDashboard\EtlobhaController::class,'deleteall']);
Route::get('productdeleteall',[App\Http\Controllers\api\adminDashboard\ProductController::class,'deleteall']);
Route::post('addToStore/{id}',[App\Http\Controllers\api\adminDashboard\StockController::class,'addToStore']);
Route::get('technicalSupportchangeStatusall',[App\Http\Controllers\api\adminDashboard\TechnicalSupportController::class,'changeStatusall']);


Route::get('pagechangeSatusall',[App\Http\Controllers\api\adminDashboard\PageController::class,'changeSatusall']);
Route::get('pagedeleteall',[App\Http\Controllers\api\adminDashboard\PageController::class,'deleteall']);
Route::get('userchangeSatusall',[App\Http\Controllers\api\adminDashboard\UserController::class,'changeSatusall']);
Route::get('userdeleteall',[App\Http\Controllers\api\adminDashboard\UserController::class,'deleteall']);
Route::get('couponchangeSatusall',[App\Http\Controllers\api\adminDashboard\CouponController::class,'changeSatusall']);
Route::get('coupondeleteall',[App\Http\Controllers\api\adminDashboard\CouponController::class,'deleteall']);
Route::get('marketerdeleteall',[App\Http\Controllers\api\adminDashboard\MarketerController::class,'deleteall']);
Route::get('categorychangeSatusall',[App\Http\Controllers\api\adminDashboard\CategoryController::class,'changeSatusall']);
Route::get('categorydeleteall',[App\Http\Controllers\api\adminDashboard\CategoryController::class,'deleteall']);
Route::get('categorystorechangeSatusall',[App\Http\Controllers\api\adminDashboard\StoreCategoryController::class,'changeSatusall']);
Route::get('categorystoredeleteall',[App\Http\Controllers\api\adminDashboard\StoreCategoryController::class,'deleteall']);
Route::get('cityedeleteall',[App\Http\Controllers\api\adminDashboard\CityController::class,'deleteall']);
Route::get('currencychangeSatusall',[App\Http\Controllers\api\adminDashboard\CurrencyController::class,'changeSatusall']);
Route::get('packagechangeSatusall',[App\Http\Controllers\api\adminDashboard\PackageController::class,'changeSatusall']);
Route::get('packagedeleteall',[App\Http\Controllers\api\adminDashboard\PackageController::class,'deleteall']);
Route::get('registration_marketer_show',[App\Http\Controllers\api\adminDashboard\SettingController::class,'registration_marketer_show']);

Route::resource('etlobha',App\Http\Controllers\api\adminDashboard\EtlobhaController::class);
Route::resource('note',App\Http\Controllers\api\adminDashboard\NoteController::class);
Route::post('productchangeSpecial/{id}',[App\Http\Controllers\api\adminDashboard\EtlobhaController::class,'specialStatus']);
Route::get('activitydeleteall',[App\Http\Controllers\api\adminDashboard\ActivityController::class,'deleteall']);
Route::post('addStoreNote',[App\Http\Controllers\api\adminDashboard\StoreController::class,'addNote']);
Route::post('acceptVerification/{id}',[App\Http\Controllers\api\adminDashboard\StoreController::class,'acceptVerification']);
Route::post('specialStatus/{id}',[App\Http\Controllers\api\adminDashboard\StoreController::class,'specialStatus']);
Route::post('rejectVerification/{id}',[App\Http\Controllers\api\adminDashboard\StoreController::class,'rejectVerification']);
Route::post('addProductNote',[App\Http\Controllers\api\adminDashboard\ProductController::class,'addNote']);
// Route::post('statusMarketer/{id}',[App\Http\Controllers\api\adminDashboard\SettingController::class,'statusMarketer']);
Route::post('registrationMarketer',[App\Http\Controllers\api\adminDashboard\SettingController::class,'registrationMarketer']);
Route::get('contactdeleteall',[App\Http\Controllers\api\adminDashboard\ContactController::class,'deleteall']);
Route::post('verification_update',[App\Http\Controllers\api\adminDashboard\StoreController::class,'verification_update']);
});
});
Auth::routes();
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    // Route::resource('users', UserController::class);

});

// Route::group(['prefix' => '/Store', 'middleware' => ['storeUsers']], function(){
Route::middleware([StoreUser::class])->group(function(){
Route::prefix('/Store')->group(function () {

    Route::get('pagechangeSatusall',[App\Http\Controllers\api\storeDashboard\PageController::class,'changeSatusall']);
    Route::get('pagedeleteall',[App\Http\Controllers\api\storeDashboard\PageController::class,'deleteall']);



Route::resource('country',App\Http\Controllers\api\storeDashboard\CountryController::class);
Route::resource('city',App\Http\Controllers\api\storeDashboard\CityController::class);
Route::post('changeCountryStatus/{id}',[App\Http\Controllers\api\storeDashboard\CountryController::class,'changeStatus']);
Route::post('changeCityStatus/{id}',[App\Http\Controllers\api\storeDashboard\CityController::class,'changeStatus']);
Route::resource('page',App\Http\Controllers\api\storeDashboard\pageController::class);
Route::post('publish',[App\Http\Controllers\api\storeDashboard\pageController::class,'publish']);
Route::get('couponchangeSatusall',[App\Http\Controllers\api\storeDashboard\CouponController::class,'changeSatusall']);
Route::get('coupondeleteall',[App\Http\Controllers\api\storeDashboard\CouponController::class,'deleteall']);

Route::post('changeMaintenanceStatus/{id}', [App\Http\Controllers\api\storeDashboard\MaintenanceController::class,'changeStatus']);

Route::post('logoUpdate',[App\Http\Controllers\api\storeDashboard\HomepageController::class,'logoUpdate']);
Route::post('panarUpdate',[App\Http\Controllers\api\storeDashboard\HomepageController::class,'panarUpdate']);
Route::post('sliderUpdate',[App\Http\Controllers\api\storeDashboard\HomepageController::class,'sliderUpdate']);
Route::post('commentUpdate',[App\Http\Controllers\api\storeDashboard\HomepageController::class,'commentUpdate']);


Route::resource('maintenance',App\Http\Controllers\api\storeDashboard\MaintenanceController::class);

Route::resource('pagecategory',App\Http\Controllers\api\storeDashboard\PageCategoryController::class);
Route::post('changePageStatus/{id}', [App\Http\Controllers\api\storeDashboard\PageController::class,'changeStatus']);
Route::post('changePageCategoryStatus/{id}', [App\Http\Controllers\api\storeDashboard\PageCategoryController::class,'changeStatus']);
Route::resource('explainVideos',App\Http\Controllers\api\storeDashboard\ExplainVideosController::class);
Route::post('changeProductStatus/{id}', [App\Http\Controllers\api\storeDashboard\ProductController::class,'changeStatus']);
Route::resource('product',App\Http\Controllers\api\storeDashboard\ProductController::class);
Route::resource('category',App\Http\Controllers\api\storeDashboard\CategoryController::class);
Route::post('changeCategoryStatus/{id}', [App\Http\Controllers\api\storeDashboard\CategoryController::class,'changeStatus']);
Route::resource('course',App\Http\Controllers\api\storeDashboard\CourseController::class);
Route::post('changeCourseStatus/{id}', [App\Http\Controllers\api\storeDashboard\CourseController::class,'changeStatus']);
Route::post('changeVideoStatus/{id}', [App\Http\Controllers\api\storeDashboard\VideoController::class,'changeStatus']);
Route::resource('video',App\Http\Controllers\api\storeDashboard\VideoController::class);
Route::post('changeUnitStatus/{id}', [App\Http\Controllers\api\storeDashboard\UnitController::class,'changeStatus']);
Route::resource('unit',App\Http\Controllers\api\storeDashboard\UnitController::class);
Route::post('changeTechnicalSupportStatus/{id}', [App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class,'changeStatus']);
Route::resource('technicalSupport',App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class);
Route::post('changeCouponStatus/{id}', [App\Http\Controllers\api\storeDashboard\CouponController::class,'changeStatus']);
Route::resource('coupons',App\Http\Controllers\api\storeDashboard\CouponController::class);
Route::post('changeShippingtypeStatus/{id}', [App\Http\Controllers\api\storeDashboard\ShippingtypeController::class,'changeStatus']);
Route::resource('shippingtype',App\Http\Controllers\api\storeDashboard\ShippingtypeController::class);
Route::post('changePaymenttypeStatus/{id}', [App\Http\Controllers\api\storeDashboard\PaymenttypeController::class,'changeStatus']);
Route::resource('paymenttype',App\Http\Controllers\api\storeDashboard\PaymenttypeController::class);
Route::post('changeOfferStatus/{id}', [App\Http\Controllers\api\storeDashboard\OfferController::class,'changeStatus']);
Route::resource('offer',App\Http\Controllers\api\storeDashboard\OfferController::class);
Route::post('changeSeoStatus/{id}', [App\Http\Controllers\api\storeDashboard\SeoController::class,'changeStatus']);
Route::resource('seo',App\Http\Controllers\api\storeDashboard\SeoController::class);
Route::post('updateSeo',[App\Http\Controllers\api\storeDashboard\SeoController::class,'updateSeo']);
Route::post('updateLink',[App\Http\Controllers\api\storeDashboard\SeoController::class,'updateLink']);
Route::post('updateRobots',[App\Http\Controllers\api\storeDashboard\SeoController::class,'updateRobots']);


Route::resource('client',App\Http\Controllers\api\storeDashboard\ClientController::class);
Route::post('changeClientStatus/{id}', [App\Http\Controllers\api\storeDashboard\ClientController::class,'changeStatus']);
Route::resource('homepage',App\Http\Controllers\api\storeDashboard\HomepageController::class);
Route::resource('comment',App\Http\Controllers\api\storeDashboard\CommentController::class);
Route::get('commentchangeSatusall',[App\Http\Controllers\api\storeDashboard\CommentController::class,'changeSatusall']);

Route::resource('replaycomment',App\Http\Controllers\api\storeDashboard\ReplaycommentController::class);
Route::post('changeCommentStatus/{id}', [App\Http\Controllers\api\storeDashboard\CommentController::class,'changeStatus']);
Route::post('changeReplaycommentStatus/{id}', [App\Http\Controllers\api\storeDashboard\ReplaycommentController::class,'changeStatus']);
Route::get('categoryStorechangeSatusall',[App\Http\Controllers\api\storeDashboard\CategoryController::class,'changeSatusall']);
Route::get('categoryStoredeleteall',[App\Http\Controllers\api\storeDashboard\CategoryController::class,'deleteall']);
Route::resource('replaycomment',App\Http\Controllers\api\storeDashboard\ReplaycommentController::class);
Route::resource('user',App\Http\Controllers\api\storeDashboard\UserController::class);
Route::post('changeCommentStatus/{id}', [App\Http\Controllers\api\storeDashboard\CommentController::class,'changeStatus']);
Route::post('changeReplaycommentStatus/{id}', [App\Http\Controllers\api\storeDashboard\ReplaycommentController::class,'changeStatus']);
Route::get('productchangeSatusall',[App\Http\Controllers\api\storeDashboard\ProductController::class,'changeSatusall']);
Route::get('productdeleteall',[App\Http\Controllers\api\storeDashboard\ProductController::class,'deleteall']);
Route::get('deleteImport/{product}',[App\Http\Controllers\api\storeDashboard\ProductController::class,'deleteImport']);
Route::get('clientdeleteall',[App\Http\Controllers\api\storeDashboard\ClientController::class,'deleteall']);
Route::get('socialMedia_store_show',[App\Http\Controllers\api\storeDashboard\storeInformationController::class,'socialMedia_store_show']);
Route::post('socialMedia_store_update',[App\Http\Controllers\api\storeDashboard\storeInformationController::class,'socialMedia_store_update']);
Route::get('etlobhaShow',[App\Http\Controllers\api\storeDashboard\ImportproductController::class,'etlobhaShow']);
Route::post('importproduct',[App\Http\Controllers\api\storeDashboard\ImportproductController::class,'store']);
Route::post('updateimportproduct/{id}',[App\Http\Controllers\api\storeDashboard\ImportproductController::class,'updateimportproduct']);
Route::get('verification_show',[App\Http\Controllers\api\storeDashboard\VerificationController::class,'verification_show']);
Route::post('verification_update',[App\Http\Controllers\api\storeDashboard\VerificationController::class,'verification_update']);
Route::get('setting_store_show',[App\Http\Controllers\api\storeDashboard\SettingController::class,'setting_store_show']);
Route::post('setting_store_update',[App\Http\Controllers\api\storeDashboard\SettingController::class,'setting_store_update']);

Route::get('profile',[App\Http\Controllers\api\storeDashboard\ProfileController::class,'index']);
Route::post('profile',[App\Http\Controllers\api\storeDashboard\ProfileController::class,'update']);
Route::get('NotificationIndex',[App\Http\Controllers\api\storeDashboard\NotificationController::class,'index']);
Route::get('NotificationRead/{id}',[App\Http\Controllers\api\storeDashboard\NotificationController::class,'read']);
Route::get('NotificationDelete/{id}',[App\Http\Controllers\api\storeDashboard\NotificationController::class,'deleteNotification']);
Route::get('NotificationDeleteAll',[App\Http\Controllers\api\storeDashboard\NotificationController::class,'deleteNotificationAll']);
Route::get('NotificationShow/{id}',[App\Http\Controllers\api\storeDashboard\NotificationController::class,'show']);


});
});
