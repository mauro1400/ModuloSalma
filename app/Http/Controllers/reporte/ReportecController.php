<?php

namespace App\Http\Controllers\reporte;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Reportec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportecController extends Controller
{
    public function index()
    {
        $reportec=DB::select('SELECT r.delivery_date as fecha_entrega, r.nro_solicitud, u.name as solicitante, 
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
        return view('reporte.reportec.index', compact('reportec'));
    }
}
