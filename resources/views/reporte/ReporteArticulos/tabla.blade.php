<table class="table">
    <thead class="table-primary text-center ">
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Codigo</th>
            <th rowspan="2">Cod_ant</th>
            <th rowspan="2">Description</th>
            <th rowspan="2">Unidad de Medida</th>
            <th rowspan="2">Partida</th>
            <th rowspan="2">Numero de Factura</th>
            <th rowspan="2">Detalle</th>
            <th rowspan="2">Precio Unitario</th>
            <th colspan="3">FISICO</th>
            <th colspan="3">VALORADO</th>

        </tr>
        <tr>
            <th>Ingreso</th>
            <th>Egreso</th>
            <th>Saldo</th>
            <th>*Ingreso</th>
            <th>*Egreso</th>
            <th>*Saldo</th>
        </tr>
    </thead>
    <tbody>

        @for ($i = 0; $i < 12; $i++)
            @if (substr($codig[$i]->code, 0, -1) == substr(request('codigo'), 0, -1))
                <tr>
                    <td></td>
                    <td colspan="14">
                        <b>{{ $codig[$i]->codigo }}
                        </b>
                    </td>
                </tr>
                <tr>
                    @foreach ($reporteArticulos as $item)
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->codigo }}</td>
                        <td>{{ $item->cod_ant }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->uni_med }}</td>
                        <td>{{ $item->partida }}</td>
                        <td>{{ $item->num_fac }}</td>
                        <td>{{ $item->detalle }}</td>
                        <td>{{ $item->p_unitario }}</td>
                        <td>{{ $item->ingreso }}</td>
                        <td></td>
                        <td></td>
                        <td>{{ $item->t_ingreso }}</td>
                        <td></td>
                        <td></td>
                </tr>
            @endforeach
        @endif
        @endfor
    </tbody>
</table>
