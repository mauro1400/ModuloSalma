<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\reporte\ReporteaController;
use App\Http\Controllers\reporte\ReportebController;
use App\Http\Controllers\reporte\ReportecController;

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

Route::resource('reporte/reportea', ReporteaController::class);
Route::resource('reporte/reporteb', ReportebController::class);
Route::resource('reporte/reportec', ReportecController::class);