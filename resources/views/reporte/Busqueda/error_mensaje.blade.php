@if (isset($error))
<div class="alert alert-danger">
    <h6>{{ $error }}</h6>
</div>
@endif
@include('reporte.ReporteArticulos.home',$codig)
