<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookVendorController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ISBNLookUpController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SocialActivityController;
use App\Http\Controllers\UserController;
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

Route::group(['as'=> 'v' . env('APP_VERSION', 69).'.', 'prefix' => 'v'.env('APP_VERSION', 69)], function(){
    Route::group(['as' => 'auth.', 'prefix' => 'auth'], function () {
        Route::post('/register', [ApiAuthController::class, 'register'])->name('register');
        Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
        Route::get('/verify/{user}/{token}', [ApiAuthController::class, 'verifyEmail']);
        Route::post('/reset/request', [ApiAuthController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('/reset-password', [ApiAuthController::class, 'resetPassword'])->name('reset-password');

        Route::get('/email-change/{user}/{email_crypted}/{token}', [UserController::class, 'confirmEmail']);
        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::post('/email-change', [UserController::class, 'changeEmail']);
            Route::post('/change-password', [UserController::class, 'changePassword']);
            Route::post('/change-info', [UserController::class, 'updateInfo']);
            Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout');
        });
    });

    Route::get('/isbn/{isbn}', [ISBNLookUpController::class, 'lookup']);

    Route::post('/book/bulk', [BookController::class, 'findBulk'])->middleware(["auth:sanctum"]);
    Route::get('/book/{book}', [BookController::class, 'find']);


    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'user'], function () {
        Route::get('/{user_id?}', [UserController::class, 'find']);
        Route::get('/search/{query}/{page?}/{count?}', [FollowController::class, 'searchUsers']);
    });

    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'report'], function () {
        Route::post('/book/{book}/', [ReportController::class, 'reportBookInfo']);
        Route::post('/series/{series}/', [ReportController::class, 'reportSeriesInfo']);
    });

    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'social'], function () {
        Route::get('/activity/likes/{activity}', [SocialActivityController::class, 'likes']);
        Route::post('/activity/like/{activity}', [SocialActivityController::class, 'likeActivity']);
        Route::post('/activity/unlike/{activity}', [SocialActivityController::class, 'unlikeActivity']);

        Route::get('/activity/{page?}/{user?}/{count?}', [SocialActivityController::class, 'getUserActivity']);
        Route::get('/global/feed/{page?}/{count?}', [SocialActivityController::class, 'getGlobalFeed']);
        Route::get('/feed/{page?}/{count?}', [SocialActivityController::class, 'getActivityFeed']);

        Route::get('/followers/{user?}', [FollowController::class, 'getFollowers']);
        Route::get('/following/{user?}', [FollowController::class, 'getFollowing']);
        Route::post('/follow/{user}', [FollowController::class, 'follow']);
        Route::post('/unfollow/{user}', [FollowController::class, 'unfollow']);
    });

    Route::get('/vendors', [BookVendorController::class, 'getAll']);
    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'vendors'], function () {
        Route::post('/bulk', [BookVendorController::class, 'findBulk']);
        Route::get('/private', [BookVendorController::class, 'getPrivate']);
        Route::post('/private', [BookVendorController::class, 'createPrivate']);
        Route::put('/private/{vendor}', [BookVendorController::class, 'updatePrivate']);
        Route::delete('/private/{vendor}', [BookVendorController::class, 'deletePrivate']);
        Route::post('/suggest', [BookVendorController::class, 'suggest']);
    });

    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'collection'], function () {
        Route::get('/list', [CollectionController::class, 'list']);
        Route::get('/find/{collection}', [CollectionController::class, 'find']);
        Route::get('/items/{collection}/{page?}/{count?}', [CollectionController::class, 'items']);
        Route::post('/add', [CollectionController::class, 'store']);
        Route::post('/update/{collection}', [CollectionController::class, 'update']);
        Route::post('/destroy/{collection}', [CollectionController::class, 'destroy']);
    });

    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'item'], function () {
        Route::get('/find/{item}', [ItemController::class, 'find']);
        Route::post('/add', [ItemController::class, 'store']);
        Route::post('/update/{item}', [ItemController::class, 'update']);
        Route::post('/destroy/{item}', [ItemController::class, 'destroy']);
    });
});

/* API V2 */
Route::group(['prefix' => 'v2', 'as' => 'v2.'], function () {

    Route::post('/book/bulk', [BookController::class, 'findBulk'])->middleware(["auth:sanctum"])->name('book-bulk');
    Route::get('/book/{book}', [BookController::class, 'find'])->name('book');
    Route::get('/isbn/{isbn}', [ISBNLookUpController::class, 'lookup'])->name('isbn');

    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
        Route::post('/register', [ApiAuthController::class, 'register'])->name('register');
        Route::post('/password/forgot', [ApiAuthController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('/password/reset', [ApiAuthController::class, 'resetPassword'])->name('reset-password');
        Route::get('/verify/{user}/{email_crypted}/{token}', [ApiAuthController::class, 'verifyEmail'])->name('verify-email'); //110 included here

        // Route::get('/email-change/{user}/{email_crypted}/{token}', [UserController::class, 'changeEmailConfirm']);
    });

    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/{user_id?}', [UserController::class, 'getUserInfo'])->name('info');
        Route::get('/{query}/{page?}/{count?}', [FollowController::class, 'searchUsers'])->name('search');

        Route::post('/email', [UserController::class, 'changeEmail'])->name('change-email');
        Route::post('/password', [UserController::class, 'changePassword'])->name('change-password');
        Route::post('/info', [UserController::class, 'update'])->name('change-info');
        Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout');
    });

    // Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'report'], function () {
    //     Route::post('/book/{book}/', [ReportController::class, 'reportBookInfo']);
    //     Route::post('/series/{series}/', [ReportController::class, 'reportSeriesInfo']);
    // });

    // Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'social'], function () {
    //     Route::get('/activity/likes/{activity}', [SocialActivityController::class, 'likes']);
    //     Route::post('/activity/like/{activity}', [SocialActivityController::class, 'likeActivity']);
    //     Route::post('/activity/unlike/{activity}', [SocialActivityController::class, 'unlikeActivity']);

    //     Route::get('/activity/{page?}/{user?}/{count?}', [SocialActivityController::class, 'getUserActivity']);
    //     Route::get('/global/feed/{page?}/{count?}', [SocialActivityController::class, 'getGlobalFeed']);
    //     Route::get('/feed/{page?}/{count?}', [SocialActivityController::class, 'getActivityFeed']);

    //     Route::get('/followers/{user?}', [FollowController::class, 'getFollowers']);
    //     Route::get('/following/{user?}', [FollowController::class, 'getFollowing']);
    //     Route::post('/follow/{user}', [FollowController::class, 'follow']);
    //     Route::post('/unfollow/{user}', [FollowController::class, 'unfollow']);
    // });

    // Route::get('/vendors', [BookVendorController::class, 'getAll']);
    // Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'vendors'], function () {
    //     Route::post('/bulk', [BookVendorController::class, 'findBulk']);
    //     Route::get('/private', [BookVendorController::class, 'getPrivate']);
    //     Route::post('/private', [BookVendorController::class, 'createPrivate']);
    //     Route::put('/private/{vendor}', [BookVendorController::class, 'updatePrivate']);
    //     Route::delete('/private/{vendor}', [BookVendorController::class, 'deletePrivate']);
    //     Route::post('/suggest', [BookVendorController::class, 'suggest']);
    // });

    // Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'collection'], function () {
    //     Route::get('/list', [CollectionController::class, 'list']);
    //     Route::get('/find/{collection}', [CollectionController::class, 'find']);
    //     Route::get('/items/{collection}/{page?}/{count?}', [CollectionController::class, 'items']);
    //     Route::post('/add', [CollectionController::class, 'store']);
    //     Route::post('/update/{collection}', [CollectionController::class, 'update']);
    //     Route::post('/destroy/{collection}', [CollectionController::class, 'destroy']);
    // });

    // Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'item'], function () {
    //     Route::get('/find/{item}', [ItemController::class, 'find']);
    //     Route::post('/add', [ItemController::class, 'store']);
    //     Route::post('/update/{item}', [ItemController::class, 'update']);
    //     Route::post('/destroy/{item}', [ItemController::class, 'destroy']);
    // });
});