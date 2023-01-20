<?php

namespace App\Http\Controllers\reporte;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Exports\ReporteArticulosExport;
use GuzzleHttp\Psr7\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpParser\ErrorHandler\Collecting;

class ReporteArticulosController extends Controller
{

      public function inicio()
      {
            $codig = DB::table('subarticles')->select('code')->get();
            //dd($query);
            return view('reporte.ReporteArticulos.home', ["codig"=>$codig]);
      }

      public function busquedaCodigo()
      {
            $codig = DB::table('subarticles')->select('code')->get();
            $porPagina = 10;
            $codigo = request('codigo');
            $fecha_inicio = request('fecha_inicio');
            $fecha_fin = request('fecha_fin');
            $reporteArticulos = DB::table('subarticles')
                  ->select([
                        's.code as codigo',
                        's.code_old as cod_ant',
                        's.description',
                        's.unit as uni_med',
                        'm.description as partida',
                        'm.code',
                        't1.num_fac',
                        't1.fec_ent',
                        'su.name as detalle',
                        't1.subarticle_id',
                        't1.note_entry_id',
                        't1.entry_subarticle_id',
                        't1.entry_date',
                        DB::raw('round(t1.unit_cost,2) as p_unitario'),
                        DB::raw('round(t1.amount,2) as ingreso'),
                        DB::raw('round((t1.unit_cost * t1.amount),2) as t_ingreso'),
                        't1.date'
                  ])
                  ->from(DB::raw("(SELECT es.subarticle_id as subarticle_id,
                   ne.invoice_number as num_fac, 
                   ne.id as note_entry_id,
                    ne.invoice_date as fec_ent, 
                    ne.supplier_id, 
                    es.id as entry_subarticle_id,
                     IFNULL(ne.note_entry_date, es.date) as entry_date, 
                     es.unit_cost as unit_cost, 
                     es.amount as amount, es.date 
                     FROM note_entries ne RIGHT JOIN entry_subarticles es on es.note_entry_id =ne.id 
                     WHERE (ne.invalidate = 0 OR (ne.invalidate is null and ne.id is null)) 
                     AND es.subarticle_id = subarticle_id 
                     AND es.invalidate = 0 
                     AND es.unit_cost > 0 
                     ORDER BY entry_date, note_entry_id, entry_subarticle_id) t1"))
                  ->leftJoin('subarticles as s', 's.id', '=', 't1.subarticle_id')
                  ->leftJoin('materials as m', 's.material_id', '=', 'm.id')
                  ->leftJoin('suppliers as su', 'su.id', '=', 't1.supplier_id')
                  ->where('s.created_at', '<=', "$fecha_fin")
                  ->where('s.created_at', '>=', "$fecha_inicio")
                  ->where('s.code', '=', "$codigo")
                  ->where('s.status', '=', 1)
                  ->where('m.status', '=', 1)
                  ->paginate(100);

            return view('reporte.ReporteArticulos.index', ["codig" => $codig, "reporteArticulos" => $reporteArticulos]);
      }
      public function exportarReporteArticulos()
      {
            $hoy = now();
            return (new ReporteArticulosExport)->dato(request('fecha_inicio'), request('fecha_fin'))->download("reporte.$hoy.xlsx");
      }
}
