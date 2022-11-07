@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><b>
                        <h5>Reporte Certificados de Origen</h5>
                    </b></div>
                <div class="card-body">
                    <a href="{{ url('/reportea/export') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus" aria-hidden="true"></i> Exportar a Excel</a>
                    <form method="GET" action="{{ url('/reporte/busqueda') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                        <div class="container-fluid">
                            <div class="input-group">
                                <label for=""><b>Fecha Inicio:&nbsp</b></label>
                                <input type="date" class="form-control me-2" name="fecha1" id="fecha1" value="{{ request('fecha1') }}">&nbsp
                                <label for=""><b>Fecha Fin:&nbsp</b></label>
                                <input type="date" class="form-control me-2" name="fecha2" id="fecha2" value="{{ request('fecha2') }}">&nbsp
                                <button class="btn btn-outline-success" type="submit">Buscar</button>&nbsp
                                <a href="{{ url('/reporte/reportea') }}" class="btn btn-outline-danger">Borrar</a>
                            </div>
                        </div>
                    </form>
                    <form method="GET" action="{{ url('/reporte/busquedaregional') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                        <label for="DataList" class="form-label"><b>Buscar Regional:&nbsp</b></label>
                        <input type="text" class="form-control" list="opciones" id="DataList" name="regional" id="regional" placeholder="Buscar Regional..." value="{{ request('regional') }}">&nbsp
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
                        <button class="btn btn-outline-success" type="submit">Buscar</button>&nbsp
                        <a href="{{ url('/reporte/reportea') }}" class="btn btn-outline-danger">Borrar</a>
                    </form>
                    <br />
                    <br />
                </div>
                <div class="table-responsive">
                    @include('reporte.reportea.tabla',$reportea)
                </div>
            </div>
        </div>
    </div>
</div>
@endsection