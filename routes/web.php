<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GotexController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('/.well-known/apple-developer-merchantid-domain-association.txt', function () {
    $file = fopen(public_path('well-known/apple-developer-merchantid-domain-association.txt1'), 'r');
    return $file;
});
Route::post('webhook', [App\Http\Controllers\api\WebhookController::class, 'handleWebhook']);

Route::get('/', function () {
    return view('welcome');
});
Route::get('/welcome', function () {
    return view('welcome2');
});
Route::get('/roqia', function () {
    return view('visitpage');
});

Route::get('/tagpage', function () {
    return view('tagpage');
});
Route::get('/test', function () {
    $res = app(GotexController::class)->printSticker('655f5689ebfe6bfa43d3ea2b');
    // dd( $res);
    return $res->body();

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
