<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\BookVendorController;
use App\Http\Controllers\ISBNLookUpController;
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
    Route::middleware('auth:sanctum')->post('/logout', [ApiAuthController::class, 'logout']); 
});

Route::get('/isbn/{isbn}', [ISBNLookUpController::class, 'lookup']);

Route::get('/vendors', [BookVendorController::class, 'getAll']);
Route::middleware('auth:sanctum')->post('/vendors/suggest', [BookVendorController::class, 'suggest']);