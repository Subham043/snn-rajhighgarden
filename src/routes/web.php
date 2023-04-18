<?php

use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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
Route::get('/', [HomeController::class, 'index', 'as' => 'home'])->name('home');
Route::group(['middleware' => 'throttle:3,1'], function () {
    Route::post('/enquiry', [EnquiryController::class, 'index', 'as' => 'enquiry'])->name('enquiry');
});
