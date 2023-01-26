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
                        <div class="row g-0 text-center">
                            <div class="col-md-11">
                                <div class="row g-0 text-center">
                                    <form method="GET" action="{{ url('/reporte/busquedacodigo') }}" accept-charset="UTF-8"
                                        class="form-inline my-2 my-lg-0 float-right" role="search">

                                        <div class="row g-0">
                                            <label for="" class="col-md-1 col-form-label"><b>Codigo:
                                                </b></label>
                                            <div class="col-md-2">
                                                <select name="codigo" class="form-control" id="codigo">
                                                    <option value="">Todo</option>
                                                    @foreach ($codig as $item)
                                                        <option value="{{ $item->code }}">{{ $item->codigo }}</option>
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
                                            <div class="col-md-2 ">
                                                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin"
                                                    placeholder="Buscar Fecha Fin..." value="{{ request('fecha_fin') }}">
                                            </div>

                                            <div class="col-md-1 col-sm-2">
                                                <button class="btn btn-outline-success" type="submit"><i
                                                        class="fa-sharp fa-solid fa-magnifying-glass"></i> Buscar</button>
                                            </div>
                                            <div class="col-md-1 col-sm-2">
                                                <a href="{{ url('/reporte/ReporteArticulos') }}"
                                                    class="btn btn-outline-danger"><i class="fa-solid fa-trash-can"></i>
                                                    Borrar</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ url('/reporte/exportarReporteArticulos?codigo=' . request('codigo')) . '&fecha_inicio=' . request('fecha_inicio') . '&fecha_fin=' . request('fecha_fin') }}"
                                    class="btn btn-outline-success">
                                    <i class="fa-regular fa-file-excel"></i> Exportar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
