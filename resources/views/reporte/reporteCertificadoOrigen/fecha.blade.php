@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><b>
                        <h5>Reporte Certificados de Origen</h5>
                    </b></div><br>
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-1-fluid">
                            @include('reporte.reporteCertificadoOrigen.app')
                        </div>
                        <div class="col-5-fluid">

                            <form method="GET" action="{{ url('/reporte/busquedafechas') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                                <div class="input-group">
                                    <label for=""><b>Fecha Inicio:&nbsp</b></label>
                                    <input type="date" class="form-control me-2" name="fechaInicio" id="fechaInicio" value="{{ request('fechaInicio') }}">&nbsp
                                    <label for=""><b>Fecha Fin:&nbsp</b></label>
                                    <input type="date" class="form-control me-2" name="fechaFin" id="fechaFin" value="{{ request('fechaFin') }}">&nbsp
                                    <button class="btn btn-outline-success" type="submit">Buscar</button>&nbsp
                                    <a href="{{ url('/reporte/filtrofecha') }}" class="btn btn-outline-danger">Borrar</a>&nbsp
                                </div>
                                <a href="{{ url('/reporte/exportarReporteCOFechas?fechaInicio=' . request('fechaInicio') . '&fechaFin=' . request('fechaFin')) }}" class="btn btn-outline-success">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Exportar</a>
                            </form>
                        </div>
                    </div>
                </div>
                <br />
                <div class="table-responsive">
                    @include('reporte.reporteCertificadoOrigen.tabla',$reporteCertificadoOrigen)
                </div>
                <div class="dropdown">

                </div>
            </div>
        </div>
    </div>
    @endsection