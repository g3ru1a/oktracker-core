<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookVendorController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ISBNLookUpController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SeriesController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function (){
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::get('/verify/{user}/{token}', [ApiAuthController::class, 'verifyEmail']);
    Route::post('/reset/request', [ApiAuthController::class, 'resetPasswordRequest']);
    Route::post('/reset-password', [ApiAuthController::class, 'resetPassword']);
    Route::middleware('auth:sanctum')->post('/logout', [ApiAuthController::class, 'logout']); 
});

Route::get('/isbn/{isbn}', [ISBNLookUpController::class, 'lookup']);

Route::get('/vendors', [BookVendorController::class, 'getAll']);
Route::middleware('auth:sanctum')->post('/vendors/suggest', [BookVendorController::class, 'suggest']);

Route::get('/book/{book}', [BookController::class, 'find']);

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'collection'], function() {
    Route::get('/list', [CollectionController::class, 'list']);
    Route::get('/find/{collection}', [CollectionController::class, 'find']);
    Route::get('/items/{collection}', [CollectionController::class, 'items']);
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