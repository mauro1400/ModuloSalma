@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><b>
                            <h5>Reporte Certificados de Origen</h5>
                        </b>
                    </div>
                    <div class="card-body">
                        <div class="row g-0 text-center">
                            <div class="col-md-2">
                                @include('reporte.reporteCertificadoOrigen.app')
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <form method="GET" action="{{ url('/reporte/busquedafechas') }}"
                                        accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                                        <div class="row">
                                            <span for="" class="col-md-1 col-form-label"><b>Fecha
                                                    Inicio:</b></span>
                                            <div class="col-md-2">
                                                <input type="date" class="form-control me-2" name="fechaInicio"
                                                    id="fechaInicio" value="{{ request('fechaInicio') }}">
                                            </div>
                                            <label for="" class="col-md-1 col-form-label"><b>Fecha
                                                    Fin:</b></label>
                                            <div class="col-md-2">
                                                <input type="date" class="form-control me-2" name="fechaFin"
                                                    id="fechaFin" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <button class="btn btn-outline-success" type="submit"><i
                                                        class="fa-sharp fa-solid fa-magnifying-glass"></i> Buscar</button>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <a href="{{ url('/reporte/filtrofecha') }}"
                                                    class="btn btn-outline-danger"><i class="fa-solid fa-trash-can"></i>
                                                    Borrar</a>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <a href="{{ url('/reporte/exportarReporteCOFechas?fechaInicio=' . request('fechaInicio') . '&fechaFin=' . request('fechaFin')) }}"
                                                    class="btn btn-outline-success">
                                                    <i class="fa-regular fa-file-excel"></i> Exportar</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
