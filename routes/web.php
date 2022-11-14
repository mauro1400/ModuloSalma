<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\reporte\ReporteArticulosController;
use App\Http\Controllers\reporte\ReporteCertificadoOrigenController;
use App\Http\Controllers\reporte\ReportePartidasController;
use App\Http\Controllers\SolicitudArticulo\SolicitudArticuloController;

Route::get('/', function () {
    return view('welcome');
});
////////////////////////////////////////////////////////////
Route::resource('/SolicitudArticulo/solicitud-articulo', SolicitudArticuloController::class);
Route::get('/SolicitudArticulo/busquedacodigo',[SolicitudArticuloController::class,'buscarSolicitud']);
Route::get('/aprovado/{id}',[SolicitudArticuloController::class,'aprobado'])->name('SolicitudArticulo.aprobado');
Route::get('/pendiente/{id}',[SolicitudArticuloController::class,'pendiente'])->name('SolicitudArticulo.pendiente');

////////////////////////////////////////////////////////////
Route::resource('/reporte/reporteCertificadoOrigen', ReporteCertificadoOrigenController::class);
Route::get('/reporte/busquedafechas',[ReporteCertificadoOrigenController::class,'busquedaFechas']);
Route::get('/reporte/busquedaregional',[ReporteCertificadoOrigenController::class,'busquedaRegional']);
Route::get('/reporte/exportarReporteCORegional', [ReporteCertificadoOrigenController::class, 'exportarReporteRegional']); 
Route::get('/reporte/exportarReporteCOFechas', [ReporteCertificadoOrigenController::class, 'exportarReporteFechas']); 

////////////////////////////////////////////////////////////
Route::resource('/reporte/reporteb', ReporteArticulosController::class);
Route::get('/reporte/busquedacodigo',[ReporteArticulosController::class,'busquedaCodigo']);
Route::get('/reporteb/export', [ReporteArticulosController::class, 'export']);

////////////////////////////////////////////////////////////
Route::resource('/reporte/reportec', ReportePartidasController::class);
Route::get('/reporte/busquedapartida',[ReportePartidasController::class,'busquedaPartida']);
Route::get('/reporte/export', [ReportePartidasController::class, 'export']);