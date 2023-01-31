@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><b>
                            <h5>Reporte Certificados de Origen</h5>
                        </b>
                    </div>
                    <div class="card-body">
                            <div class="row g-0 text-center">
                                <div class="col-md-2">
                                    @include('reporte.reporteCertificadoOrigen.app')
                                </div>
                            </div>
                    </div>
                </div>
                <div class="table-responsive">
                    @include('reporte.reporteCertificadoOrigen.tabla', $reporteCertificadoOrigen)
                </div>
            </div>
        </div>
    </div>
@endsection
