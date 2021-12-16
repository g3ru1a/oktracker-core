<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SeriesController;
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

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth', 'verified'])->resource('series', SeriesController::class);
Route::middleware(['auth', 'verified'])->resource('book', BookController::class);

Route::middleware(['auth', 'verified'])->get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::middleware(['auth', 'verified'])->delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');