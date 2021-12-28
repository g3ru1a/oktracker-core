<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookVendorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SeriesController;
use App\Models\BookVendor;
use GuzzleHttp\Middleware;
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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/mm')->middleware(['auth', 'verified'])->group(function(){
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('series', SeriesController::class);
    Route::resource('book', BookController::class);
    Route::resource('bookvendors', BookVendorController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/batch/{batch_size}', [ReportController::class, 'take_batch'])->name('reports.take_batch');
    Route::put('/reports/{report}', [ReportController::class, 'complete'])->name('reports.complete');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    Route::delete('/reports/assignee/{report}', [ReportController::class, 'remove_assignee'])->name('reports.remove_assignee');
});