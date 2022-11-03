<table class="table">
    <thead class="table-dark">
        <tr>
            <th>fecha_entrega</th>
            <th>nro_solicitud</th>
            <th>solicitante</th>
            <th>administrador</th>
            <th>departamento</th>
            <th>articulo</th>
            <th>pedido</th>
            <th>entregado</th>
            <th>total_entregado</th>
            <th>codigo</th>
            <th>code</th>
            <th>created_at</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reportec as $item)
        <tr>
            <td>{{ $item->fecha_entrega}}</td>
            <td>{{ $item->nro_solicitud}}</td>
            <td>{{ $item->solicitante}}</td>
            <td>{{ $item->administrador}}</td>
            <td>{{ $item->departamento}}</td>
            <td>{{ $item->articulo}}</td>
            <td>{{ $item->pedido}}</td>
            <td>{{ $item->entregado}}</td>
            <td>{{ $item->total_entregado}}</td>
            <td>{{ $item->codigo}}</td>
            <td>{{ $item->code}}</td>
            <td>{{ $item->created_at}}</td>
        </tr>
        @endforeach
    </tbody>
</table>