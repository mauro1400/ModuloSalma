<table class="table">
    <thead class="table-primary text-center ">
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">CODGIO</th>
            <th rowspan="2">PARTIDA PRESUPUESTARIA</th>
            <th rowspan="2">PARTIDA</th>
            <th rowspan="2">UNIDAD</th>

            <th rowspan="2">Precio Unitario</th>
            <th colspan="4">FISICO</th>
            <th colspan="4">VALORADO</th>

        </tr>
        <tr>
            <th>Inicio</th>
            <th>Ingreso</th>
            <th>Egreso</th>
            <th>Saldo</th>
            <th>*Inicio</th>
            <th>*Ingreso</th>
            <th>*Egreso</th>
            <th>*Saldo</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($reporteArticulos as $item)
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->code_subarticle }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->description_material }}</td>
            <td>{{ $item->unit }}</td>
            <td>{{ $item->p_unit }}</td>
            <td>{{ $item->fisico_inicial }}</td>
            <td>{{ $item->fisico_ingreso }}</td>
            <td>{{ $item->fisico_egreso }}</td>
            <td>{{ $item->fisico_final }}</td>
            <td>{{ $item->valorado_inicial }}</td>
            <td>{{ $item->valorado_ingreso }}</td>
            <td>{{ $item->valorado_egreso }}</td>
            <td>{{ $item->valorado_final }}</td>
            </tr>
        @endforeach
        @foreach ($totales as $items)
            <tr>
                <td colspan="10" class="text-center"><b>TOTALES</b></td>
                <td><b>{{ $items->valorado_inicial }}</b></td>
                <td><b>{{ $items->valorado_ingreso }}</b></td>
                <td><b>{{ $items->valorado_egreso }}</b></td>
                <td><b>{{ $items->valorado_final }}</b></td>
            </tr>
        @endforeach
    </tbody>
</table>
