@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><b><h5>Reporte Certificados de Origen</h5></b></div>
                <div class="card-body">
                    <a href="{{ url('/reporte/reportea/create') }}" class="btn btn-success btn-sm" title="Add New Reportea">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add New
                    </a>
                    <form method="GET" action="{{ url('/reporte/busquedaregional') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                        <label for="DataList" class="form-label"><b>Buscar Regional</b></label>
                        <input type="text" class="form-control" list="opciones" id="DataList" name="regional" id="regional" placeholder="Buscar Regional..." value="{{ request('regional') }}">
                        <datalist id="opciones">
                            <option value="REGIONAL COCHABAMBA">
                            <option value="DIRECCIÓN GENERAL EJECUTIVA">
                            <option value="REGIONAL EL ALTO">
                            <option value="REGIONAL SANTA CRUZ">
                            <option value="REGIONAL RIBERALTA">
                        </datalist>
                        <button class="btn btn-outline-success" type="submit">Buscar</button>
                        <a href="{{ url('/reporte/reportea') }}" class="btn btn-danger">Borrar</a>
                    </form>
                    <form method="GET" action="{{ url('/reporte/busqueda') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                        <div class="input-group">
                            <label for=""><b>Fecha Inicio</b></label>
                            <input type="date" class="form-control" name="fecha1" id="fecha1" value="{{ request('fecha1') }}">
                            <label for=""><b>Fecha Fin</b></label>
                            <input type="date" class="form-control" name="fecha2" id="fecha2" value="{{ request('fecha2') }}">
                            <button class="btn btn-outline-success" type="submit">Buscar</button>
                            <a href="{{ url('/reporte/reportea') }}" class="btn btn-danger">Borrar</a>
                        </div>
                    </form>
                    <br />
                    <br />
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha de Entrega</th>
                                <th>Nro Solicitud</th>
                                <th>Solicitante Administrador</th>
                                <th>Departamento</th>
                                <th>Articulo</th>
                                <th>Pedido</th>
                                <th>Entregado</th>
                                <th>Total</th>
                                <th>Entregado</th>
                                <th>Observacion</th>
                                <th>Del</th>
                                <th>Al</th>
                                <th>Certificado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportea as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->fecha_entrega }}</td>
                                <td>{{ $item->nro_solicitud }}</td>
                                <td>{{ $item->solicitante }}</td>
                                <td>{{ $item->administrador }}</td>
                                <td>{{ $item->departamento }}</td>
                                <td>{{ $item->articulo }}</td>
                                <td>{{ $item->pedido }}</td>
                                <td>{{ $item->entregado }}</td>
                                <td>{{ $item->total_entregado }}</td>
                                <td>{{ $item->observacion }}</td>
                                <td>{{ $item->del }}</td>
                                <td>{{ $item->al }}</td>
                                <td>{{ $item->certificados }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection