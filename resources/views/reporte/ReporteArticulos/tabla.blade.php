<table class="table">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Codigo</th>
            <th>Cod_ant</th>
            <th>Description</th>
            <th>Unidad de Medida</th>
            <th>Partida</th>
            <th>Numero de Factura</th>
            <th>Detalle</th>
            <th>Precio Unitario</th>
            <th>Fecha_e</th>
            <th>Fecha_e2</th>
            <th>Fecha_s</th>
            <th>Ingreso</th>
            <th>Egreso</th>
            <th>Saldo</th>
            <th>Ingreso_e</th>
            <th>Egreso_e</th>
            <th>Saldo_e</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reporteArticulos as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->codigo }}</td>
            <td>{{ $item->cod_ant }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->uni_med }}</td>
            <td>{{ $item->partida }}</td>
            <td>{{ $item->num_fac }}</td>
            <td>{{ $item->detalle }}</td>
            <td>{{ $item->precio_u }}</td>
            <td>{{ $item->fecha_e }}</td>
            <td>{{ $item->fecha_e2 }}</td>
            <td>{{ $item->fecha_s }}</td>
            <td>{{ $item->ingreso }}</td>
            <td>{{ $item->egreso }}</td>
            <td>{{ $item->saldo }}</td>
            <td>{{ $item->ingreso_e }}</td>
            <td>{{ $item->egreso_e }}</td>
            <td>{{ $item->saldo_e }}</td>
        </tr>
        @endforeach
    </tbody>
</table>