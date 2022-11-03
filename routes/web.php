<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\reporte\ReporteaController;
use App\Http\Controllers\reporte\ReportebController;
use App\Http\Controllers\reporte\ReportecController;
use App\Http\Controllers\SolicitudArticulo\SolicitudArticuloController;

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

Route::resource('SolicitudArticulo/solicitud-articulo', SolicitudArticuloController::class);
Route::get('SolicitudArticulo/busquedacodigo',[SolicitudArticuloController::class,'buscarSolicitud']);
////////////////////////////////////////////////////////////
Route::resource('reporte/reportea', ReporteaController::class);
Route::get('reporte/busqueda',[ReporteaController::class,'busqueda']);
Route::get('reporte/busquedaregional',[ReporteaController::class,'busquedaRegional']);
////////////////////////////////////////////////////////////
Route::resource('reporte/reporteb', ReportebController::class);
Route::get('reporte/busquedacodigo',[ReportebController::class,'busquedaCodigo']);
////////////////////////////////////////////////////////////
Route::resource('reporte/reportec', ReportecController::class);