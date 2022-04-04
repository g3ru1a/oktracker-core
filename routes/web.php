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
    return redirect(route('dashboard'));
});

Route::get('/app/login', function(){})->name('open.app.login');
Route::get('/.well-known/assetlinks.json', function(){
    return response()->json('[{"relation":["delegate_permission/common.handle_all_urls"],"target":{"namespace":"android_app","package_name":"com.oktracker","sha256_cert_fingerprints":["9C:C1:25:E0:80:9C:2B:30:A2:2D:AF:EA:27:2D:A2:03:58:9D:16:9C:C6:6D:AC:2F:CB:57:B6:20:0B:C9:6F:95"]}},{"relation":["delegate_permission/common.handle_all_urls"],"target":{"namespace":"android_app","package_name":"com.oktracker","sha256_cert_fingerprints":["3A:52:D1:4A:C8:F3:E8:D5:EE:C7:EA:45:FD:1C:D8:49:29:38:5B:92:29:BD:66:EB:3E:60:FA:6D:A3:86:51:96"]}}]');
});

Route::get('/policy', function () {
    return view('policy');
});

Route::prefix('/mm')->middleware(['auth', 'verified'])->group(function(){
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('series', SeriesController::class);
    Route::resource('book', BookController::class);
    Route::resource('bookvendors', BookVendorController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/batch/', [ReportController::class, 'take_batch'])->name('reports.take_batch');
    Route::put('/reports/drop/{report}', [ReportController::class, 'drop'])->name('reports.drop');
    Route::put('/reports/{report}', [ReportController::class, 'complete'])->name('reports.complete');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    Route::delete('/reports/assignee/{report}', [ReportController::class, 'remove_assignee'])->name('reports.remove_assignee');
});
