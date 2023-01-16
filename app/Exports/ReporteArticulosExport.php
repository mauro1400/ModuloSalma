<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReporteArticulosExport implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles
{
    use Exportable;
    public function headings(): array
    {
        return [
            'Codigo', 'Cod_ant', 'Description', 'Uni_med', 'Partida', 'Num_fac', 'Detalle',
            'Precio_u', 'Fecha_e', 'Fecha_e2', 'Fecha_s', 'Ingreso', 'Egreso', 'Saldo', 'Ingreso_e',
            'Egreso_e', 'Saldo_e',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function dato($fecha_inicio, $fecha_fin)
    {

        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        return $this;
    }

    public function collection()
    {
        $busquedacodigo = DB::select('select s.code as codigo,
        s.code_old as cod_ant,
        s.description,
        s.unit as uni_med,
        m.description as partida,
        m.code,
        t1.num_fac,
        t1.fec_ent,
        su.name as detalle,
        t1.subarticle_id,
        t1.note_entry_id,
        t1.entry_subarticle_id,
        t1.entry_date,
        round(t1.unit_cost,2) as p_unitario,
        round(t1.amount,2) as ingreso,     
        round((t1.unit_cost * t1.amount),2) as t_ingreso,
        t1.date
        from (SELECT es.subarticle_id as subarticle_id,
              ne.invoice_number as num_fac,
              ne.id as note_entry_id,
              ne.invoice_date as fec_ent,
              ne.supplier_id,
              es.id as entry_subarticle_id,
              IFNULL(ne.note_entry_date, es.date) as entry_date,
              es.unit_cost as unit_cost,
              es.amount as amount,
              es.date
              FROM note_entries ne RIGHT JOIN entry_subarticles es on es.note_entry_id = ne.id
              WHERE (ne.invalidate = 0 OR (ne.invalidate is null and ne.id is null))
              AND es.subarticle_id = subarticle_id
              AND es.invalidate = 0
              and es.unit_cost > 0
              ORDER BY entry_date, note_entry_id, entry_subarticle_id) t1 
        left join subarticles s on s.id=t1.subarticle_id
        left join materials m on s.material_id = m.id
        left join suppliers su on su.id=t1.supplier_id
        where s.created_at <= :fecha_fin
        and s.created_at > :fecha_inicio
        and s.status = 1 and m.status = 1', array(

            'fecha_inicio' => "$this->fecha_inicio",
            'fecha_fin' => "$this->fecha_fin"
        ));
        return collect($busquedacodigo);
    }
}
