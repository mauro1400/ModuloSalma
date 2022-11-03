@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Reporteb</div>
                <div class="card-body">
                    <a href="{{ url('/reporte/reporteb/create') }}" class="btn btn-success btn-sm" title="Add New Reporteb">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add New
                    </a>
                    <form method="GET" action="{{ url('/reporte/busquedacodigo') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                        <div class="input-group">
                            <label for=""><b>Buscar Codigo:</b></label>&nbsp
                            <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Buscar Codigo..." value="{{ request('codigo') }}">&nbsp
                            <button class="btn btn-outline-success" type="submit">Buscar</button>&nbsp
                            <a href="{{ url('/reporte/reporteb') }}" class="btn btn-outline-danger">Borrar</a>
                        </div>
                    </form>

                    <br />
                    <br />
                </div>
                <div class="table-responsive">
                    @include('reporte.reporteb.tabla',$reporteb)
                </div>
            </div>
        </div>
    </div>
</div>
@endsection