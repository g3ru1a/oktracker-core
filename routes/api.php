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

Route::group(['prefix' => 'auth'], function (){
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::get('/verify/{user}/{token}', [ApiAuthController::class, 'verifyEmail']);
    Route::post('/reset/request', [ApiAuthController::class, 'resetPasswordRequest']);
    Route::post('/reset-password', [ApiAuthController::class, 'resetPassword']);

    Route::get('/email-change/{user}/{email_crypted}/{token}', [UserController::class, 'changeEmailConfirm']);
    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::post('/email-change', [UserController::class, 'changeEmail']);
        Route::post('/change-password', [UserController::class, 'changePassword']);
        Route::post('/change-info', [UserController::class, 'update']); 
        Route::post('/logout', [ApiAuthController::class, 'logout']); 
    });
});

Route::get('/isbn/{isbn}', [ISBNLookUpController::class, 'lookup']);

Route::post('/book/bulk', [BookController::class, 'findBulk'])->middleware(["auth:sanctum"]);
Route::get('/book/{book}', [BookController::class, 'find']);


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'getUserInfo']);
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
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'vendors'], function() {
    Route::post('/bulk', [BookVendorController::class, 'findBulk']);
    Route::get('/private', [BookVendorController::class, 'getPrivate']);
    Route::post('/private', [BookVendorController::class, 'createPrivate']);
    Route::put('/private/{vendor}', [BookVendorController::class, 'updatePrivate']);
    Route::delete('/private/{vendor}', [BookVendorController::class, 'deletePrivate']);
    Route::post('/suggest', [BookVendorController::class, 'suggest']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'collection'], function() {
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