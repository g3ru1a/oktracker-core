<?php

use App\Http\Controllers\BookVendorController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ISBNLookUpController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SocialActivityController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\V1\UserController as UserControllerV1;
use App\Http\Controllers\Api\V1\ApiAuthController as AuthControllerV1;
use App\Http\Controllers\Api\V1\CollectionController as CollectionControllerV1;
use App\Http\Controllers\Api\V1\BookController as BookControllerV1;
use App\Http\Controllers\Api\V1\VendorController as VendorControllerV1;

use App\Http\Controllers\Api\V2\UserController as UserControllerV2;
use App\Http\Controllers\Api\V2\AuthController as AuthControllerV2;
use App\Http\Controllers\Api\V2\CollectionController as CollectionControllerV2;
use App\Http\Controllers\Api\V2\BookController as BookControllerV2;
use App\Http\Controllers\Api\V2\VendorController as VendorControllerV2;

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

Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {
    Route::group(['as' => 'auth.', 'prefix' => 'auth'], function () {
        Route::post('/register', [AuthControllerV1::class, 'register'])->name('register');
        Route::post('/login', [AuthControllerV1::class, 'login'])->name('login');
        Route::get('/verify/{user}/{token}', [AuthControllerV1::class, 'verifyEmail']);
        Route::post('/reset/request', [AuthControllerV1::class, 'forgotPassword'])->name('forgot-password');
        Route::post('/reset-password', [AuthControllerV1::class, 'resetPassword'])->name('reset-password');

        Route::get('/email-change/{user}/{email_crypted}/{token}', [UserControllerV1::class, 'confirmEmail']);
        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::post('/email-change', [UserControllerV1::class, 'changeEmail']);
            Route::post('/change-password', [UserControllerV1::class, 'changePassword']);
            Route::post('/change-info', [UserControllerV1::class, 'updateInfo']);
            Route::post('/logout', [AuthControllerV1::class, 'logout'])->name('logout');
        });
    });

    Route::get('/isbn/{isbn}', [ISBNLookUpController::class, 'lookup']);

    Route::post('/book/bulk', [BookControllerV1::class, 'findBulk'])->middleware(["auth:sanctum"]);
    Route::get('/book/{book}', [BookControllerV1::class, 'find']);

    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'user'], function () {
        Route::get('/{user_id?}', [UserControllerV1::class, 'find']);
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

    Route::get('/vendors', [VendorControllerV1::class, 'getAll'])->name('vendors.all');
    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'vendors', 'as' => 'vendors.'], function () {
        Route::post('/bulk', [VendorControllerV1::class, 'findBulk'])->name('bulk');
        Route::get('/private', [VendorControllerV1::class, 'getPrivate'])->name('private-all');
        Route::post('/private', [VendorControllerV1::class, 'createPrivate'])->name('private-create');
        Route::put('/private/{vendor}', [VendorControllerV1::class, 'updatePrivate'])->name('private-update');
        Route::delete('/private/{vendor}', [VendorControllerV1::class, 'deletePrivate'])->name('private-destroy');
        Route::post('/suggest', [VendorControllerV1::class, 'suggest'])->name('suggest');
    });

    Route::group(['middleware' => 'auth:sanctum', 'prefix' =>'collection', 'as' => 'collections.'], function () {
        Route::get('/list', [CollectionControllerV1::class, 'list'])->name('list');
        Route::get('/items/{collection}/{page?}/{count?}', [CollectionControllerV1::class, 'items'])->name('items');
        
        Route::get('/find/{collection}', [CollectionControllerV1::class, 'find'])->name('find');
        Route::post('/add', [CollectionControllerV1::class, 'store'])->name('store');
        Route::post('/update/{collection}', [CollectionControllerV1::class, 'update'])->name('update');
        Route::post('/destroy/{collection}', [CollectionControllerV1::class, 'destroy'])->name('destroy');
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

    Route::get('/isbn/{isbn}', [ISBNLookUpController::class, 'lookup'])->name('isbn');

    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('/login', [AuthControllerV2::class, 'login'])->name('login');
        Route::post('/register', [AuthControllerV2::class, 'register'])->name('register');
        Route::post('/password/forgot', [AuthControllerV2::class, 'forgotPassword'])->name('forgot-password');
        Route::post('/password/reset', [AuthControllerV2::class, 'resetPassword'])->name('reset-password');
        Route::get('/verify/{user}/{email_crypted}/{token}', [UserControllerV2::class, 'confirmEmail'])->name('verify-email');
    });

    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/{user_id?}', [UserControllerV2::class, 'find'])->name('info');
        Route::get('/{query}/{page?}/{count?}', [FollowController::class, 'searchUsers'])->name('search');

        Route::post('/email', [UserControllerV2::class, 'changeEmail'])->name('change-email');
        Route::post('/password', [UserControllerV2::class, 'changePassword'])->name('change-password');
        Route::post('/info', [UserControllerV2::class, 'changeInfo'])->name('change-info');
        Route::post('/logout', [AuthControllerV2::class, 'logout'])->name('logout');
    });

    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'books', 'as' => 'books.'], function () {
        Route::get('/{book_ids}', [BookControllerV2::class, 'findBulk'])->name('bulk');
        Route::get('/{book}', [BookControllerV2::class, 'find'])->name('find');
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

    Route::group(['prefix' => 'vendors', 'as' => 'vendors.'], function (){
        Route::get('/', [VendorControllerV2::class, 'all'])->name('all');

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::post('/bulk/', [VendorControllerV2::class, 'bulk'])->name('bulk');
            Route::post('/suggest', [VendorControllerV2::class, 'suggest'])->name('suggest');
        });

        Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'private', 'as' => 'private.'], function () {
            Route::get('/', [VendorControllerV2::class, 'showPrivateVendors'])->name('show');
            Route::post('/', [VendorControllerV2::class, 'createPrivateVendor'])->name('create');
            Route::put('/{vendor}', [VendorControllerV2::class, 'updatePrivateVendor'])->name('update');
            Route::delete('/{vendor}', [VendorControllerV2::class, 'destroyPrivateVendor'])->name('destroy');
        });
    });

    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'collections', 'as' => 'collections.'], function () {
        Route::get('/', [CollectionControllerV2::class, 'all'])->name('all');
        // TODO: Move this -> Route::get('/{collection}/items/{page?}/{count?}', [CollectionControllerV2::class, 'items'])->name('items');

        Route::post('/', [CollectionControllerV2::class, 'create'])->name('create');
        Route::get('/{collection}', [CollectionControllerV2::class, 'show'])->name('show');
        Route::put('/{collection}', [CollectionControllerV2::class, 'update'])->name('update');
        Route::delete('/{collection}', [CollectionControllerV2::class, 'destroy'])->name('destroy');
    });

    // Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'item'], function () {
    //     Route::get('/find/{item}', [ItemController::class, 'find']);
    //     Route::post('/add', [ItemController::class, 'store']);
    //     Route::post('/update/{item}', [ItemController::class, 'update']);
    //     Route::post('/destroy/{item}', [ItemController::class, 'destroy']);
    // });
});
