@if ($opcion == 1)
    @include('reporte.reporteCertificadoOrigen.fecha')
@else
    @include('reporte.reporteCertificadoOrigen.regional')
@endif

<table class="table">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Fecha de Entrega</th>
            <th>Nro Solicitud</th>
            <th>Solicitante </th>
            <th>Administrador</th>
            <th>Departamento</th>
            <th>Articulo</th>
            <th>Block Certificado</th>
            <th>Entregado</th>
            <th>Total Entregado</th>
            <th>Observacion</th>
            <th>Del</th>
            <th>Al</th>
            <th>Certificado</th>
        </tr>
    </thead>
    @if (count($reporteCertificadoOrigen) != 0)
        <tbody>
            @foreach ($reporteCertificadoOrigen as $item)
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
