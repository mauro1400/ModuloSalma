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

Route::resource('/SolicitudArticulo/solicitud-articulo', SolicitudArticuloController::class);
Route::get('/SolicitudArticulo/busquedacodigo',[SolicitudArticuloController::class,'buscarSolicitud']);
Route::get('/aprovado/{id}',[SolicitudArticuloController::class,'aprobado'])->name('SolicitudArticulo.aprobado');
Route::get('/pendiente/{id}',[SolicitudArticuloController::class,'pendiente'])->name('SolicitudArticulo.pendiente');

////////////////////////////////////////////////////////////
Route::resource('/reporte/reportea', ReporteaController::class);
Route::get('/reporte/busqueda',[ReporteaController::class,'busqueda']);
Route::get('/reporte/busquedaregional',[ReporteaController::class,'busquedaRegional']);
Route::get('/reportea/export', [ReporteaController::class, 'export']); 
Route::get('/reportea/exporta', [ReporteaController::class, 'exporta']); 

////////////////////////////////////////////////////////////
Route::resource('/reporte/reporteb', ReportebController::class);
Route::get('/reporte/busquedacodigo',[ReportebController::class,'busquedaCodigo']);
Route::get('/reporteb/export', [ReportebController::class, 'export']); 
////////////////////////////////////////////////////////////
Route::resource('/reporte/reportec', ReportecController::class);
Route::get('/reporte/busquedapartida',[ReportecController::class,'busquedaPartida'])->name('reporte.busquedaPartida');
Route::get('/reporte/export', [ReportecController::class, 'export'])->name('reporte.export');