@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><b>
                            <h5>Reporte de Solicitudes</h5>
                        </b>
                    </div>
                    <div class="card-body">
                        <div class="row g-0 text-center">
                            <div class="col-md-11">

                                <form action="{{ url('/reporte/busquedapartida') }}"
                                    class="form-inline my-2 my-lg-0 float-right" role="search">
                                    <div class="row g-0">
                                        <label for="" class="col-md-3 col-form-label"><b>Buscar Partida:
                                            </b></label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="partida"
                                                placeholder="Buscar Articulos..." value="{{ request('partida') }}">
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-outline-success" type="submit"><i
                                                    class="fa-sharp fa-solid fa-magnifying-glass"></i> Buscar</button>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="{{ url('/reporte/reportePartidas') }}"
                                                class="btn btn-outline-danger"><i class="fa-solid fa-trash-can"></i>
                                                Borrar</a>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="col-md-1">
                                <a href="{{ url('/reporte/exportarReportePartida?partida=' . request('partida')) }}"
                                    class="btn btn-outline-success">
                                    <i class="fa-regular fa-file-excel"></i> Exportar</a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    @include('reporte.ReportePartidas.tabla', $reportePartidas)
                </div>

            </div>
        </div>
    </div>
@endsection
