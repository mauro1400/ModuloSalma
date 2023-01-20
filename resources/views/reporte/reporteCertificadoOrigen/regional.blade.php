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
                                    <form method="GET" action="{{ url('/reporte/busquedaregional') }}"
                                        accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                                        <div class="row">
                                            <label for="DataList" class="col-md-2 col-form-label"><b>Buscar
                                                    Regional:&nbsp</b></label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" list="opciones" id="DataList"
                                                    name="regional" id="regional" placeholder="Buscar Regional..."
                                                    value="{{ request('regional') }}">&nbsp
                                                <datalist id="opciones">
                                                    <option value="REGIONAL COCHABAMBA">
                                                    <option value="REGIONAL EL ALTO">
                                                    <option value="REGIONAL SANTA CRUZ">
                                                    <option value="REGIONAL RIBERALTA">
                                                    <option value="REGIONAL LA PAZ">
                                                    <option value="REGIONAL SUCRE">
                                                    <option value="REGIONAL POTOSI">
                                                    <option value="REGIONAL ORURO">
                                                    <option value="REGIONAL TARIJA">
                                                    <option value="UNIDAD DE SISTEMAS Y PLANIFICACION (USP)">
                                                    <option value="UNIDAD ADMINISTRATIVA FINANCIERA (UAF)">
                                                    <option value="UNIDAD DE CERTIFICACIÓN DE ORIGEN (UCO)">
                                                    <option value="UNIDAD DE CONTROL LEGAL Y ASUNTOS JURIDICOS (UCLAJ)">
                                                    <option value="DIRECCIÓN GENERAL EJECUTIVA">
                                                </datalist>
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-outline-success" type="submit">Buscar</button>&nbsp
                                            </div>
                                            <div class="col-md-1">
                                                <a href="{{ url('/reporte/filtroregional') }}"
                                                    class="btn btn-outline-danger">Borrar</a>&nbsp
                                            </div>
                                            <div class="col-md-2">
                                                <a href="{{ url('/reporte/exportarReporteCORegional?regional=' . request('regional')) }}"
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
            <div class="table-responsive">
                @include('reporte.reporteCertificadoOrigen.tabla', $reporteCertificadoOrigen)
            </div>
        </div>
    </div>
@endsection
