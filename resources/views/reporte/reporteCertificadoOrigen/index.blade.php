@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><b>
                        <h5>Reporte Certificados de Origen</h5>
                    </b></div><br>
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-1-ms-auto-fluid">
                            @include('reporte.reporteCertificadoOrigen.app')
                        </div>
                    </div>
                </div><br>
                <div class="table-responsive">
                    @include('reporte.reporteCertificadoOrigen.tabla',$reporteCertificadoOrigen)
                </div>
            </div>
        </div>
    </div>
</div>
@endsection