<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReportecExport implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles
{
    use Exportable;
    public function headings(): array
    {
        return [
            'Fecha Entrega',
            'nNumero Solicitud',
            'Solicitante',
            'Administrador',
            'Departamento',
            'Articulo',
            'Pedido',
            'Entregado',
            'Total Entregado',
            'Codigo de Articulo',
            'Partida',
            'Creado el',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function dato($partida)
    {
        $this->partida = $partida;
        return $this;
    }

    public function collection()
    {
        $busquedapartida = DB::select('SELECT r.delivery_date as fecha_entrega, r.nro_solicitud, u.name as solicitante, 
        u1.name as administrador, d.name as departamento, s.description as articulo, sq.amount as pedido, 
        sq.amount_delivered as entregado, sq.total_delivered as total_entregado, s.code as codigo, m.code,r.created_at
        from requests r left join subarticle_requests sq on r.id=sq.request_id 
        left join users u on r.user_id=u.id 
        left join users u1 on r.admin_id=u1.id 
        left join subarticles s on s.id=sq.subarticle_id 
        left join departments d on d.id=u.department_id 
        left join materials m on s.material_id = m.id 
        where sq.observacion is not null 
        and m.code in (32100,32200)
        and m.code  LIKE :partida
        order by DATE(r.created_at), s.code, s.description;', array(
            'partida' => "$this->partida"
        ));
        return collect($busquedapartida);
    }
}
