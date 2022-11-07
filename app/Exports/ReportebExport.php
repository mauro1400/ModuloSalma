<?php

namespace App\Exports;

use App\Models\Reporteb;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportebExport implements FromCollection,ShouldAutoSize, WithHeadings,WithStyles
{
    public function collection()
    {
        return Reporteb::all();
    }

    public function headings(): array
    {
        return [
            'Codigo',
            'Cod_ant',
            'Description',
            'Uni_med',
            'Partida',
            'Num_fac',
            'Detalle',
            'Precio_u',
            'Fecha_e',
            'Fecha_e2',
            'Fecha_s',
            'Ingreso',
            'Egreso',
            'Saldo',
            'Ingreso_e',
            'Egreso_e',
            'Saldo_e',
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
