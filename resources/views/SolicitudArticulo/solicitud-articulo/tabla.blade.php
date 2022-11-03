<table class="table">
    <thead class="table-dark">
        <tr>
            <th>Numero de solicitud</th>
            <th>Descripcion</th>
            <th>Unidad</th>
            <th>Cantidad</th>
            <th>Cantidad_Anterior</th>
            <th>Cantidad Entregado</th>
            <th>total Entregado</th>
            <th>Actualizado en</th>
            <th>Observación</th>
            <th>Estado de Solicitud</th>
            <th>Editar Cantidad</th>
        </tr>
    </thead>
    <tbody>
        @foreach($solicitudarticulo as $item)
        <tr>
            <td>{{ $item->nro_solicitud }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->unit }}</td>
            <td>{{ $item->amount }}</td>
            <td>{{ $item->Monto_Anterior }}</td>
            <td>{{ $item->amount_delivered }}</td>
            <td>{{ $item->total_delivered }}</td>
            <td>{{ $item->updated_at }}</td>
            <td>{{ $item->observacion }}</td>
            <td>
                @if($item->estado =="0")
                <a href="{{ url('/SolicitudArticulo/solicitud-articulo/') }}" title="Edit SolicitudArticulo"><button class="btn btn-warning btn-sm"><i aria-hidden="true"></i>Pendiente</button></a>
                @else
                <a href="{{ url('/SolicitudArticulo/solicitud-articulo/') }}" title="Edit SolicitudArticulo"><button class="btn btn-success btn-sm"><i aria-hidden="true"></i>Aprobado</button></a>
                @endif
            </td>
            <td>
                <a href="{{ url('/SolicitudArticulo/solicitud-articulo/' . $item->id . '/edit') }}" title="Edit SolicitudArticulo"><button class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar</button></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>