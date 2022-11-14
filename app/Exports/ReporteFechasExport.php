<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReportefechasExport implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles
{
    use Exportable;
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
    public function fechas($fechaInicio, $fechaFin )
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        return $this;
    }
    public function collection()
    {
        $busquedafechas = DB::select('SELECT t.*, (((t.al-t.del)+1)/25) as certificados FROM (
            SELECT r.delivery_date as fecha_entrega, r.nro_solicitud, u.name as solicitante,
            u1.name as administrador, d.name as departamento, s.description as articulo, 
            sq.amount as pedido, sq.amount_delivered as entregado, sq.total_delivered as total_entregado, 
            sq.observacion, case (LENGTH(sq.observacion) - LENGTH(replace(sq.observacion, "-", ""))) / LENGTH("-") 
                when 0 then sq.observacion
                when 1 then SUBSTRING_INDEX(sq.observacion, "-", 1)
                end del,
            case (LENGTH(sq.observacion) - LENGTH(replace(sq.observacion, "-", ""))) / LENGTH("-") 
            when 1 then SUBSTRING_INDEX(SUBSTRING_INDEX(sq.observacion, "-", 2), "-", -1)
            end al
            from requests r left join subarticle_requests sq on r.id=sq.request_id 
            left join users u on r.user_id=u.id 
            left join users u1 on r.admin_id=u1.id 
            left join subarticles s on s.id=sq.subarticle_id 
            left join departments d on d.id=u.department_id 
            where sq.observacion is not null order by d.name, s.description)t
            where t.al is not null AND date(t.fecha_entrega) BETWEEN :fechaInicio AND :fechaFin', array(
            'fechaInicio' => "$this->fechaInicio",
            'fechaFin' => "$this->fechaFin"
        ));
        return collect($busquedafechas);
    }
}
