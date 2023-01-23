@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><b>
                            <h5>Reporte Certificados de Origen</h5>
                    </div>
                    <br>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-3">
                                @include('reporte.reporteCertificadoOrigen.app')
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        @include('reporte.reporteCertificadoOrigen.tabla', $reporteCertificadoOrigen)
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
