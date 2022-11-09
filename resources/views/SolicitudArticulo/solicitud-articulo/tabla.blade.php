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
            
                @if($item->estado == "0")
              
                <a href="{{ route('SolicitudArticulo.aprobado',$item->id) }}" class="btn btn-warning btn-sm" role="button" aria-hidden="true">Pendiente</a>
                @elseif($item->estado == "1")
                <a href="{{ route('SolicitudArticulo.pendiente',$item->id) }}" class="btn btn-success btn-sm disabled" tabindex="-1" role="button" aria-hidden="true">Aprobado</a>
                @else
                @endif
            </td>
            <td>
                @if($item->estado == "0" or $item->estado == null)
                <a href="{{ url('/SolicitudArticulo/solicitud-articulo/' . $item->id . '/edit') }}" title="Edit SolicitudArticulo" class="btn btn-warning btn-sm" role="button" aria-hidden="true"><i class="fa fa-pencil-square-o"></i> Editar</a>
                @elseif($item->estado == "1")
                <a href="{{ url('/SolicitudArticulo/solicitud-articulo/' . $item->id . '/edit') }}" title="Edit SolicitudArticulo" class="btn btn-warning btn-sm disabled" tabindex="-1" role="button" aria-hidden="true" ><i class="fa fa-pencil-square-o" ></i> Editar</a>
                @else
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>