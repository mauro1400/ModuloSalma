<?php

namespace App\Http\Controllers\reporte;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Exports\ReportebExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportebController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $reporteb = DB::select('SELECT t3.*, 
        (t3.ingreso - t3.egreso) as saldo,  
        round((t3.precio_u * t3.ingreso),2) as ingreso_e,
        round((t3.precio_u * t3.egreso),2) as egreso_e,
        round(((t3.precio_u * t3.ingreso) - (t3.precio_u * t3.egreso)),2) as saldo_e
 from (select t2.codigo,
           t2.cod_ant,
           t2.description,
           t2.uni_med,
           t2.partida,
           t2.num_fac,
           t2.detalle,
           round(t2.p_unitario,2) as precio_u,
           t2.fec_ent as fecha_e,
           t2.date as fecha_e2,
           r.delivery_date as fecha_s,
           round(t2.ingreso,2) as ingreso, 
           case when (round(sum(sr.total_delivered),2)) > round(t2.ingreso,2)
                THEN case when t2.fec_ent = t2.date then 0 else round(t2.ingreso,2) end                
                else round(sum(sr.total_delivered),2) end as egreso
           from (select s.code as codigo,
                 s.code_old as cod_ant,
                 s.description,
                 s.unit as uni_med,
                 m.description as partida,
                 t1.num_fac,
                 t1.fec_ent,
                 su.name as detalle,
                 t1.subarticle_id,
                 t1.note_entry_id,
                 t1.entry_subarticle_id,
                 t1.entry_date,
                 t1.unit_cost as p_unitario,
                 t1.amount as ingreso,     
                 (t1.unit_cost * t1.amount) as t_ingreso,
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
                 where t1.entry_date <= "2022-02-15"
                 and t1.entry_date > "2021-08-09"
                 and s.status = 1 and m.status = 1)t2
           left join subarticle_requests sr on sr.subarticle_id=t2.subarticle_id
           left join requests r on r.id=sr.request_id
           where r.delivery_date >= t2.date          
           group by t2.codigo,t2.num_fac,r.delivery_date)t3
 order by t3.codigo');
        return view('reporte.reporteb.index', compact('reporteb'));
    }


    public function busquedaCodigo()
    {
        
        $codigo = request('codigo');
        $busqueda = DB::select('SELECT t3.*, 
        (t3.ingreso - t3.egreso) as saldo,  
        round((t3.precio_u * t3.ingreso),2) as ingreso_e,
        round((t3.precio_u * t3.egreso),2) as egreso_e,
        round(((t3.precio_u * t3.ingreso) - (t3.precio_u * t3.egreso)),2) as saldo_e
 from (select t2.codigo,
           t2.cod_ant,
           t2.description,
           t2.uni_med,
           t2.partida,
           t2.num_fac,
           t2.detalle,
           round(t2.p_unitario,2) as precio_u,
           t2.fec_ent as fecha_e,
           t2.date as fecha_e2,
           r.delivery_date as fecha_s,
           round(t2.ingreso,2) as ingreso, 
           case when (round(sum(sr.total_delivered),2)) > round(t2.ingreso,2)
                THEN case when t2.fec_ent = t2.date then 0 else round(t2.ingreso,2) end                
                else round(sum(sr.total_delivered),2) end as egreso
           from (select s.code as codigo,
                 s.code_old as cod_ant,
                 s.description,
                 s.unit as uni_med,
                 m.description as partida,
                 t1.num_fac,
                 t1.fec_ent,
                 su.name as detalle,
                 t1.subarticle_id,
                 t1.note_entry_id,
                 t1.entry_subarticle_id,
                 t1.entry_date,
                 t1.unit_cost as p_unitario,
                 t1.amount as ingreso,     
                 (t1.unit_cost * t1.amount) as t_ingreso,
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
                 where t1.entry_date <= "2022-02-15"
                 and t1.entry_date > "2021-08-09"
                 and s.status = 1 and m.status = 1)t2
           left join subarticle_requests sr on sr.subarticle_id=t2.subarticle_id
           left join requests r on r.id=sr.request_id
           where r.delivery_date >= t2.date          
           group by t2.codigo,t2.num_fac,r.delivery_date)t3
           WHERE t3.codigo LIKE :codigo order by t3.codigo', array(
            'codigo' => "$codigo"
        ));
        $reporteb['reporteb']=$busqueda;
            return view('reporte.reporteb.index', $reporteb);
    }
    public function export()
    {
        $hoy= now();
        return (new ReportebExport)->dato(request('codigo'))->download("reporte.$hoy.xlsx");
        //return Excel::download(new ReportebExport, "reporte.$hoy.xlsx");
    }
}
