<?php

namespace App\Exports;

use App\Models\Reportea;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;


class ReporteaExport implements ShouldAutoSize, WithHeadings,WithStyles,FromCollection
{
    public function collection()
    {
        return Reportea::all();
    }

    public function headings(): array
    {
        return [
            'Fecha de Entrega',
            'Nro Solicitud',
            'Solicitante',
            'Administrador',
            'Departamento',
            'Articulo',
            'Pedido',
            'Entregado',
            'Total Entregado',
            'Observacion',
            'Del',
            'Al',
            'Certificados',
            ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
