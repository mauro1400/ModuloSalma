@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><b>
                            <h5>Solicitud de Articulo</h5>
                        </b>
                    </div>
                    <div class="card-body">
                        <div class="row g-0 justify-content-center">
                            <div class="col-md-12">
                                <div class="row g-0 text-center">
                                    <form method="GET" action="{{ url('/SolicitudArticulo/busquedacodigo') }}"
                                        accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                                        <div class="row justify-content-center g-0">
                                            <label for="" class="col-md-1 col-form-label"><b>Buscar Solicitud:
                                                </b></label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" name="search"
                                                    placeholder="Buscar..." value="{{ request('search') }}">
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-outline-success" type="submit"><i
                                                        class="fa-sharp fa-solid fa-magnifying-glass"></i> Buscar</button>
                                            </div>
                                            <div class="col-md-1">
                                                <a href="{{ url('/SolicitudArticulo/solicitud-articulo') }}"
                                                    class="btn btn-outline-danger"><i class="fa-solid fa-trash-can"></i>
                                                    Borrar</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        @include('SolicitudArticulo.solicitud-articulo.tabla', $solicitudarticulo)
                    </div>
                    <div class="pagination justify-content-center">
                        {!! $solicitudarticulo->appends(['search' => Request::get('search')])->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
