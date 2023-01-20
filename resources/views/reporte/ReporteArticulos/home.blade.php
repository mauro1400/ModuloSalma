@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><b>
                            <h5>Reporte de Articulos</h5>
                        </b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ url('/reporte/exportarReporteArticulos?codigo=' . request('codigo')) . '&fecha_inicio=' . request('fecha_inicio') . '&fecha_fin=' . request('fecha_fin') }}"
                                    class="btn btn-outline-success">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Exportar</a>
                            </div>
                            <div class="col-md-9">
                                <dir class="row">
                                    <form method="GET" action="{{ url('/reporte/busquedacodigo') }}"
                                        accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                                        
                                            <div class="row">
                                                <label for="" class="col-md-1 col-form-label"><b>Codigo:
                                                    </b></label>
                                                <div class="col-md-2">
                                                    <select name="codigo" class="form-control" id="codigo">
                                                        @foreach ($codig as $item)
                                                            <option value="{{ $item->code }}">{{ $item->code }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <span for="" class="col-md-1 col-form-label"><b>Desde: </b></span>
                                                <div class="col-md-2">
                                                    <input type="date" class="form-control" name="fecha_inicio"
                                                        id="fecha_inicio" placeholder="Buscar Fecaha Inicio..."
                                                        value="{{ request('fecha_inicio') }}">
                                                </div>

                                                <label for="" class="col-md-1 col-form-label"><b>Hasta:</b></label>
                                                <div class="col-md-2">
                                                    <input type="date" class="form-control" name="fecha_fin"
                                                        id="fecha_fin" placeholder="Buscar Fecha Fin..."
                                                        value="{{ request('fecha_fin') }}">
                                                </div>

                                                <dir class="col-md-1">
                                                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                                                </dir>
                                                <div class="col-md-1">
                                                    <a href="{{ url('/reporte/ReporteArticulos') }}"
                                                        class="btn btn-outline-danger">Borrar</a>
                                                </div>
                                            </div>
                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
