@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><b>
                        <h5>Reporte de Articulos</h5>
                    </b></div>
                <div class="card-body">
                    <form method="GET" action="{{ url('/reporte/busquedacodigo') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                        <div class="input-group">
                            <label for=""><b>Codigo:</b></label>&nbsp
                            <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Buscar Codigo..." value="{{ request('codigo') }}">&nbsp
                            <label for=""><b>Desde</b></label>&nbsp
                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" placeholder="Buscar Fecaha Inicio..." value="{{ request('fecha_inicio') }}">&nbsp
                            <label for=""><b>Hasta:</b></label>&nbsp
                            <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" placeholder="Buscar Fecha Fin..." value="{{ request('fecha_fin') }}">&nbsp
                            <button class="btn btn-outline-success" type="submit">Buscar</button>&nbsp
                            <a href="{{ url('/reporte/ReporteArticulos') }}" class="btn btn-outline-danger">Borrar</a>
                        </div>
                    </form>
                    <a href="{{ url('/reporte/exportarReporteArticulos?codigo=' . request('codigo')) . '&fecha_inicio='.request('fecha_inicio').'&fecha_fin='.request('fecha_fin') }}" class="btn btn-outline-success">
                        <i class="fa fa-plus" aria-hidden="true"></i> Exportar</a>
                    <br />
                </div>
                <div class="table-responsive">
                    @include('reporte.ReporteArticulos.tabla',$reporteArticulos)
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection