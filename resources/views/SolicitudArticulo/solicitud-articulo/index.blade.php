@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><b><h5>Solicitud de Articulo</h5></b></div>
                <div class="card-body">
                    <form method="GET" action="{{ url('/SolicitudArticulo/busquedacodigo') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                        <div class="input-group">
                            <label for=""><b>Buscar Solicitud: </b></label>&nbsp
                            <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">&nbsp
                            <button class="btn btn-outline-success" type="submit">Buscar</button>&nbsp
                            <a href="{{ url('/SolicitudArticulo/solicitud-articulo') }}" class="btn btn-outline-danger">Borrar</a>
                        </div>
                    </form>

                    <br />
                    <br />
                </div>
                <div class="table-responsive">
                    @include('SolicitudArticulo.solicitud-articulo.tabla',$solicitudarticulo)
                </div>
            </div>
        </div>
    </div>
</div>
@endsection