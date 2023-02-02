<table class="table">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Fecha Entrega</th>
            <th>Numero Solicitud</th>
            <th>Solicitante</th>
            <th>Administrador</th>
            <th>Departamento</th>
            <th>Articulo</th>
            <th>Pedido</th>
            <th>Entregado</th>
            <th>Total Entregado</th>
            <th>Codigo de Articulo</th>
            <th>Partida</th>
            <th>Creado el</th>
        </tr>
    </thead>
    @if (count($reportePartidas) != 0)
        <tbody>
            @foreach ($reportePartidas as $item)
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
                    <td>{{ $item->codigo }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->created_at }}</td>
                </tr>
        </tbody>
    @endforeach
@else
    <tbody>
        <tr>
            <td colspan="14">
                @include('reporte.busqueda.noHayResultados')
            </td>
        </tr>
    </tbody>
    @endif

</table>
