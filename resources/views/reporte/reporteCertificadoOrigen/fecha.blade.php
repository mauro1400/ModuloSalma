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
                        <div class="row justify-content-between">
                            <div class="col-md-3">
                                @include('reporte.reporteCertificadoOrigen.app')
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <form method="GET" action="{{ url('/reporte/busquedafechas') }}"
                                        accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                                        <div class="row">
                                            <span for="" class="col-md-1 col-form-label"><b>Fecha
                                                    Inicio:&nbsp</b></span>
                                            <div class="col-md-2">
                                                <input type="date" class="form-control me-2" name="fechaInicio"
                                                    id="fechaInicio" value="{{ request('fechaInicio') }}">&nbsp
                                            </div>
                                            <label for="" class="col-md-1 col-form-label"><b>Fecha
                                                    Fin:&nbsp</b></label>
                                            <div class="col-md-2">
                                                <input type="date" class="form-control me-2" name="fechaFin"
                                                    id="fechaFin" value="{{ request('fechaFin') }}">&nbsp
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-outline-success" type="submit">Buscar</button>&nbsp
                                            </div>
                                            <div class="col-md-1">
                                                <a href="{{ url('/reporte/filtrofecha') }}"
                                                    class="btn btn-outline-danger">Borrar</a>&nbsp
                                            </div>
                                            <div class="col-md-2">
                                                <a href="{{ url('/reporte/exportarReporteCOFechas?fechaInicio=' . request('fechaInicio') . '&fechaFin=' . request('fechaFin')) }}"
                                                    class="btn btn-outline-success">
                                                    <i class="fa fa-plus" aria-hidden="true"></i> Exportar</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="table-responsive">
                @include('reporte.reporteCertificadoOrigen.tabla', $reporteCertificadoOrigen)
            </div>
        </div>
    @endsection
