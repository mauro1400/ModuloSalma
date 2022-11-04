@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><b>
                        <h5>Reportec</h5>
                    </b></div>
                <div class="card-body">
                    <a href="{{ url('/reporte/export') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus" aria-hidden="true"></i> Exportar a Excel</a>

                    <form method="GET" action="{{ url('/reporte/busquedapartida') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                        <div class="input-group">
                            <label for=""><b>Buscar Partida: </b></label>&nbsp
                            <input type="text" class="form-control" name="partida" placeholder="Buscar Partida..." value="{{ request('partida') }}">&nbsp
                            <button class="btn btn-outline-success" type="submit">Buscar</button>&nbsp
                            <a href="{{ url('/reporte/reportec') }}" class="btn btn-outline-danger">Borrar</a>
                        </div>
                    </form>

                    <br />
                    <br />
                </div>
                <div class="table-responsive">
                    @include('reporte.reportec.tabla',$reportec)
                </div>
            </div>
        </div>
    </div>
</div>
@endsection