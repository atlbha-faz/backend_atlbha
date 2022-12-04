<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// change status routers
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
Route::post('changeNotificationStatus/{id}', [App\Http\Controllers\api\adminDashboard\NotificationController::class,'changeStatus']);
Route::post('changeNotification_typeStatus/{id}', [App\Http\Controllers\api\adminDashboard\Notification_typesController::class,'changeStatus']);
Route::post('changeSectionStatus/{id}', [App\Http\Controllers\api\adminDashboard\SectionController::class,'changeStatus']);
Route::post('changeSettingStatus/{id}', [App\Http\Controllers\api\adminDashboard\SettingController::class,'changeStatus']);
Route::post('changeReplaycontactStatus/{id}', [App\Http\Controllers\api\adminDashboard\ReplaycontactController::class,'changeStatus']);
Route::post('changeContactStatus/{id}', [App\Http\Controllers\api\adminDashboard\ContactController::class,'changeStatus']);
Route::post('changeSeoStatus/{id}', [App\Http\Controllers\api\adminDashboard\SeoController::class,'changeStatus']);
Route::post('changeStoreStatus/{id}', [App\Http\Controllers\api\adminDashboard\StoreController::class,'changeStatus']);
Route::post('changeOfferStatus/{id}', [App\Http\Controllers\api\adminDashboard\OfferController::class,'changeStatus']);

Route::resource('country',App\Http\Controllers\api\adminDashboard\CountryController::class);
Route::resource('city',App\Http\Controllers\api\adminDashboard\CityController::class);
Route::resource('marketer',App\Http\Controllers\api\adminDashboard\MarketerController::class);
Route::resource('explainVideos',App\Http\Controllers\api\adminDashboard\ExplainVideosController::class);
Route::resource('course',App\Http\Controllers\api\adminDashboard\CourseController::class);
Route::resource('unit',App\Http\Controllers\api\adminDashboard\UnitController::class);
Route::resource('video',App\Http\Controllers\api\adminDashboard\VideoController::class);
Route::resource('activity',App\Http\Controllers\api\adminDashboard\ActivityController::class);
Route::resource('platform',App\Http\Controllers\api\adminDashboard\PlatformController::class);
Route::resource('service',App\Http\Controllers\api\adminDashboard\ServiceController::class);
Route::resource('category',App\Http\Controllers\api\adminDashboard\CategoryController::class);
Route::resource('shippingtype',App\Http\Controllers\api\adminDashboard\ShippingtypeController::class);
Route::resource('paymenttype',App\Http\Controllers\api\adminDashboard\PaymenttypeController::class);
Route::resource('comment',App\Http\Controllers\api\adminDashboard\CommentController::class);
Route::resource('replaycomment',App\Http\Controllers\api\adminDashboard\ReplaycommentController::class);
Route::resource('maintenance',App\Http\Controllers\api\adminDashboard\MaintenanceController::class);
Route::resource('page',App\Http\Controllers\api\adminDashboard\pageController::class);
Route::resource('pagecategory',App\Http\Controllers\api\adminDashboard\PageCategoryController::class);
Route::resource('technicalSupport',App\Http\Controllers\api\adminDashboard\TechnicalSupportController::class);
Route::resource('currency',App\Http\Controllers\api\adminDashboard\CurrencyController::class);
Route::resource('website_socialmedia',App\Http\Controllers\api\adminDashboard\WebsiteSocialmediaController::class);
Route::resource('homepage',App\Http\Controllers\api\adminDashboard\HomepageController::class);
Route::resource('plan',App\Http\Controllers\api\adminDashboard\PlanController::class);
Route::resource('package',App\Http\Controllers\api\adminDashboard\PackageController::class);
Route::resource('template',App\Http\Controllers\api\adminDashboard\TemplateController::class);
Route::resource('coupons',App\Http\Controllers\api\adminDashboard\CouponController::class);
Route::resource('notification',App\Http\Controllers\api\adminDashboard\NotificationController::class);
Route::resource('notification_type',App\Http\Controllers\api\adminDashboard\Notification_typesController::class);
Route::resource('section',App\Http\Controllers\api\adminDashboard\SectionController::class);
Route::resource('contact',App\Http\Controllers\api\adminDashboard\ContactController::class);
Route::resource('replaycontact',App\Http\Controllers\api\adminDashboard\ReplaycontactController::class);
Route::resource('seo',App\Http\Controllers\api\adminDashboard\SeoController::class);
Route::resource('store',App\Http\Controllers\api\adminDashboard\StoreController::class);
Route::resource('offer',App\Http\Controllers\api\adminDashboard\OfferController::class);
