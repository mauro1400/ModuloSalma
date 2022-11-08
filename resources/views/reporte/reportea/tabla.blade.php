<table class="table">
    <thead class="table-dark">
        <tr>
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
    <tbody>
        @foreach($reportea as $item)
        <tr>
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
            <td>{{ $item->certificados}}</td>
        </tr>
        @endforeach
    </tbody>
</table>