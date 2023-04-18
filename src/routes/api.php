<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RefreshController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResendOTPController;
use App\Http\Controllers\Auth\VerifyUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ProfileUpdateController;
use App\Http\Controllers\Auth\PasswordUpdateController;
use App\Http\Controllers\Banner\CreateController as BannerCreateController;
use App\Http\Controllers\Banner\DisplayController as BannerDisplayController;
use App\Http\Controllers\Banner\PaginateController as BannerPaginateController;
use App\Http\Controllers\Banner\DeleteController as BannerDeleteController;
use App\Http\Controllers\Banner\UpdateController as BannerUpdateController;
use App\Http\Controllers\Banner\RandomController as BannerRandomController;
use App\Http\Controllers\Counter\CreateController as CounterCreateController;
use App\Http\Controllers\Counter\DisplayController as CounterDisplayController;
use App\Http\Controllers\Counter\PaginateController as CounterPaginateController;
use App\Http\Controllers\Counter\DeleteController as CounterDeleteController;
use App\Http\Controllers\Counter\UpdateController as CounterUpdateController;
use App\Http\Controllers\Counter\RandomController as CounterRandomController;
use App\Http\Controllers\DonationPage\CreateController as DonationPageCreateController;
use App\Http\Controllers\DonationPage\DisplayController as DonationPageDisplayController;
use App\Http\Controllers\DonationPage\SlugController as DonationPageSlugController;
use App\Http\Controllers\DonationPage\PaginateController as DonationPagePaginateController;
use App\Http\Controllers\DonationPage\DeleteController as DonationPageDeleteController;
use App\Http\Controllers\DonationPage\UpdateController as DonationPageUpdateController;
use App\Http\Controllers\DonationPage\RandomController as DonationPageRandomController;
use App\Http\Controllers\Donation\CreateController as DonationCreateController;
use App\Http\Controllers\Donation\DisplayController as DonationDisplayController;
use App\Http\Controllers\Donation\PaginateController as DonationPaginateController;
use App\Http\Controllers\Donation\DeleteController as DonationDeleteController;
use App\Http\Controllers\Donation\VerifyController as DonationVerifyController;
use App\Http\Controllers\Donation\RandomController as DonationRandomController;

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

Route::group(['middleware' => ['cors', 'json.response']], function () {

    Route::prefix('/auth')->group(function () {
        Route::group(['middleware' => 'throttle:3,1'], function () {
            Route::post('/login', [LoginController::class, 'login', 'as' => 'login']);
            Route::post('/admin-login', [AdminLoginController::class, 'login', 'as' => 'login']);
            Route::post('/verify-user/{user_id}', [VerifyUserController::class, 'verify_user', 'as' => 'verify_user']);
            Route::post('/reset-password/{user_id}', [ResetPasswordController::class, 'reset_password', 'as' => 'reset_password']);
            Route::get('/resend-otp/{user_id}', [ResendOTPController::class, 'send_otp', 'as' => 'send_otp']);
        });
        Route::post('/register', [RegisterController::class, 'register', 'as' => 'register']);
        Route::post('/forgot-password', [ForgotPasswordController::class, 'forgot_password', 'as' => 'forgot_password']);
        Route::group(['middleware' => ['auth:api','has.access']], function () {
            Route::get('/refresh', [RefreshController::class, 'refresh', 'as' => 'refresh']);
            Route::get('/profile', [ProfileController::class, 'profile', 'as' => 'profile']);
            Route::post('/profile-update', [ProfileUpdateController::class, 'profile_update', 'as' => 'profile_update']);
            Route::post('/password-update', [PasswordUpdateController::class, 'password_update', 'as' => 'password_update']);
            Route::get('/logout', [LogoutController::class, 'logout', 'as' => 'logout']);
        });
    });

    Route::prefix('/banner')->group(function () {

        Route::group(['middleware' => ['auth:api','has.access']], function () {
            Route::post('/create', [BannerCreateController::class, 'create', 'as' => 'create']);
            Route::get('/display/{id}', [BannerDisplayController::class, 'display', 'as' => 'display']);
            Route::get('/paginate', [BannerPaginateController::class, 'paginate', 'as' => 'paginate']);
            Route::delete('/delete/{id}', [BannerDeleteController::class, 'delete', 'as' => 'delete']);
            Route::post('/update/{id}', [BannerUpdateController::class, 'update', 'as' => 'update']);
        });

        Route::get('/random', [BannerRandomController::class, 'random', 'as' => 'random']);
    });

    Route::prefix('/counter')->group(function () {

        Route::group(['middleware' => ['auth:api','has.access']], function () {
            Route::post('/create', [CounterCreateController::class, 'create', 'as' => 'create']);
            Route::get('/display/{id}', [CounterDisplayController::class, 'display', 'as' => 'display']);
            Route::get('/paginate', [CounterPaginateController::class, 'paginate', 'as' => 'paginate']);
            Route::delete('/delete/{id}', [CounterDeleteController::class, 'delete', 'as' => 'delete']);
            Route::post('/update/{id}', [CounterUpdateController::class, 'update', 'as' => 'update']);
        });

        Route::get('/random', [CounterRandomController::class, 'random', 'as' => 'random']);
    });

    Route::prefix('/donation-page')->group(function () {

        Route::group(['middleware' => ['auth:api','has.access']], function () {
            Route::post('/create', [DonationPageCreateController::class, 'create', 'as' => 'create']);
            Route::get('/display/{id}', [DonationPageDisplayController::class, 'display', 'as' => 'display']);
            Route::get('/paginate', [DonationPagePaginateController::class, 'paginate', 'as' => 'paginate']);
            Route::delete('/delete/{id}', [DonationPageDeleteController::class, 'delete', 'as' => 'delete']);
            Route::post('/update/{id}', [DonationPageUpdateController::class, 'update', 'as' => 'update']);
        });

        Route::get('/slug/{slug}', [DonationPageSlugController::class, 'slug', 'as' => 'slug']);
        Route::get('/random', [DonationPageRandomController::class, 'random', 'as' => 'random']);
    });

    Route::prefix('/donation')->group(function () {

        Route::group(['middleware' => ['auth:api','has.access']], function () {
            Route::get('/display/{id}', [DonationDisplayController::class, 'display', 'as' => 'display']);
            Route::get('/paginate', [DonationPaginateController::class, 'paginate', 'as' => 'paginate']);
            Route::delete('/delete/{id}', [DonationDeleteController::class, 'delete', 'as' => 'delete']);
        });

        Route::post('/create', [DonationCreateController::class, 'create', 'as' => 'create']);
        Route::post('/verify', [DonationVerifyController::class, 'verify', 'as' => 'verify']);
        Route::get('/random', [DonationRandomController::class, 'random', 'as' => 'random']);
    });
});
