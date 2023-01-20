@extends('layouts.app')
@section('content')
    @include('reporte.ReporteArticulos.home',$codig)
@endsection
@section('reporteArticulos')
<div class="table-responsive">
    @include('reporte.ReporteArticulos.tabla',$reporteArticulos)
</div>
@endsection