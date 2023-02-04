<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ConsultaReporteCertificadosOrigen extends Model
{
    public static function fecha($fechaInicio, $fechaFin)
    {
        $busquedaFechas = DB::select('SELECT t.*, (((t.al-t.del)+1)/25) as certificados FROM (
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
            where t.al is not null AND date(t.fecha_entrega) BETWEEN :fecha1 AND :fecha2', array(
            'fecha1' => "$fechaInicio",
            'fecha2' => "$fechaFin"
        ));
        return $busquedaFechas;
    }
    public static function regional($regional)
    {
        $busquedaRegional = DB::select('SELECT t.*, (((t.al-t.del)+1)/25) as certificados FROM (
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
            where t.al is not null AND t.departamento in (:regional)', array(
            'regional' => "$regional"
        ));
        return $busquedaRegional;
    }
    public static function query()
    {
        $reporteCertificadoOrigen=DB::select('SELECT t.*, (((t.al-t.del)+1)/25) as certificados FROM (
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
            where t.al is not null');
            return $reporteCertificadoOrigen;
    }
}
