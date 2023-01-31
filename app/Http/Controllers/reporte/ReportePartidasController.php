<?php

namespace App\Http\Controllers\reporte;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Exports\ReportePartidasExport;

class ReportePartidasController extends Controller
{
    public function index()
    {
        $codig = DB::table('materials')
                  ->select('materials.code', DB::raw("CONCAT(materials.code,'-',materials.description) as codigo"))
                  ->get();
        $reportePartidas = DB::select('SELECT r.delivery_date as fecha_entrega, r.nro_solicitud, u.name as solicitante, 
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
        order by DATE(r.created_at), s.code, s.description');
        //dd(count($reportePartidas));
        return view('reporte.ReportePartidas.index',['reportePartidas'=>$reportePartidas,'codig'=>$codig]);

    }
    public function busquedaPartida()
    {
        $codig = DB::table('materials')
                  ->select('materials.code', DB::raw("CONCAT(materials.code,'-',materials.description) as codigo"))
                  ->get();
                  
        $partida = request('partida');
        $busqueda = DB::select('SELECT r.delivery_date as fecha_entrega, r.nro_solicitud, u.name as solicitante, 
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
        order by DATE(r.created_at), s.code, s.description', array(
            'partida' => "$partida"
        ));
       // dd(request('partida'));
        return view('reporte.ReportePartidas.index', ['reportePartidas'=>$busqueda,'codig'=>$codig]);
    }
    public function exportarReportePartidas()
    {
        $hoy = now();
        $partida = request('partida');
        return (new ReportePartidasExport)->partida($partida)->download("reporte.$hoy.xlsx");
        //return Excel::download(new ReportecExport, "reporte.$hoy.xlsx");
    }
}
