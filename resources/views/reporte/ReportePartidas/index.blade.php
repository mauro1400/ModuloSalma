@extends('layouts.app')
@section('content')
    @include('reporte.ReportePartidas.home', $codig)
@endsection
@section('reporte')
    <div class="table-responsive">
        @include('reporte.ReportePartidas.tabla', $reportePartidas)
    </div>
@endsection
