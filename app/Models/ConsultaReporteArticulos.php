<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConsultaReporteArticulos extends Model
{
    public static function obtenerInformacion($codigo, $fecha_inicio, $fecha_fin)
    {
    $reporteArticulos = DB::select(DB::raw(
        "select material_id,
                    round((((valorado_inicial/fisico_inicial)+p_unit)/2),2)as p_unitario,
                    code_material,
                    description_material,
                    id,
                    code_subarticle,
                    description,
                    unit,
                    round(p_unit,2) as p_unit,
                    fisico_inicial,
                    fisico_ingreso,
                    fisico_egreso,
                    fisico_final,
                    valorado_inicial,
                    valorado_ingreso,
                    valorado_egreso,
                    valorado_final
               from (select material_id,
                            code_material,
                            description_material,
                            id,
                            code_subarticle,
                            description,
                            unit,
                            p_unit,
                            fisico_inicial,
                            fisico_ingreso,
                            (fisico_inicial + fisico_ingreso - fisico_final) as fisico_egreso,
                            fisico_final,
                            valorado_inicial,
                            valorado_ingreso,
                            (valorado_inicial + valorado_ingreso - valorado_final) as valorado_egreso,
                            valorado_final
                       from (select material_id,
                                    code_material,
                                    description_material,
                                    id,
                                    code_subarticle,
                                    description,
                                    unit,
                                    p_unit,
                                    CAST(SUBSTRING_INDEX(saldo_final_fecha_1,'|', 1) AS DECIMAL(10,2)) as fisico_inicial,
                                    CAST(SUBSTRING_INDEX(total_ingresos,'|', 1) AS DECIMAL(10,2)) as fisico_ingreso,
                                    CAST(SUBSTRING_INDEX(saldo_final_fecha_2,'|',1) AS DECIMAL(10,2)) as fisico_final,
                                    CAST(SUBSTRING_INDEX(saldo_final_fecha_1,'|',-1) AS DECIMAL(10,2)) as valorado_inicial,
                                    CAST(SUBSTRING_INDEX(total_ingresos,'|',-1) AS DECIMAL(10,2)) as valorado_ingreso,
                                    CAST(SUBSTRING_INDEX(saldo_final_fecha_2,'|',-1) AS DECIMAL(10,2)) as valorado_final
                               from (select s2.material_id,
                                            m2.code as code_material,
                                            m2.description as description_material,
                                            s2.id,
                                            s2.code as code_subarticle,
                                            s2.description,
                                            s2.unit,
                                            sum(es.unit_cost),
                                            count(s2.id),
                                            sum(case when es.unit_cost is null then 0 else es.unit_cost end)/count(s2.id) as p_unit,
                                            total_ingresos_v1(s2.id, cast(concat('$fecha_inicio', ' 00:00:00') as datetime), cast(concat('$fecha_fin', ' 23:59:59') as datetime)) as total_ingresos,
                                            saldo_final_v1(s2.id, cast(concat('$fecha_fin', ' 23:59:59') as datetime)) as saldo_final_fecha_2,
                                            saldo_final_v1(s2.id, cast(concat('$fecha_inicio', ' 00:00:00') as datetime)) as saldo_final_fecha_1
                                       from subarticles s2 inner join materials m2 on s2.material_id = m2.id left join entry_subarticles es on es.subarticle_id=s2.id and es.date between cast(concat('$fecha_inicio', ' 00:00:00') as datetime) and cast(concat('$fecha_fin', ' 23:59:59') as datetime)
                                       where s2.status in ('1','0')
                                       and m2.status = 1 
                                       and m2.code like ('%$codigo%') 
                                       group by s2.material_id,
                              m2.description,
                              m2.code,
                              s2.id,
                              s2.code,
                              s2.description,
                              s2.unit) t1) t2
                                       where (fisico_inicial > 0 or fisico_ingreso > 0 or fisico_final > 0)) t3
             order by code_material, code_subarticle"
      ));
      
  return $reporteArticulos;
    }
    public static function total($codigo, $fecha_inicio, $fecha_fin)
    {
    $totales=DB::select(DB::raw("select 
            sum(fisico_inicial) as total_fisico_inicial,
            sum(fisico_ingreso) as fisico_ingreso,
            sum(fisico_egreso) as fisico_egreso,
            sum(fisico_final) as fisico_final,
            sum(valorado_inicial) as valorado_inicial,
            sum(valorado_ingreso) as valorado_ingreso,
            sum(valorado_egreso) as valorado_egreso,
            sum(valorado_final) as valorado_final
            from(select material_id,
                              code_material,
                              description_material,
                              id,
                              code_subarticle,
                              description,
                              unit,
                              round(p_unit,2) as p_unit,
                              fisico_inicial,
                              fisico_ingreso,
                              fisico_egreso,
                              fisico_final,
                              valorado_inicial,
                              valorado_ingreso,
                              valorado_egreso,
                              valorado_final
                         from (select material_id,
                                      code_material,
                                      description_material,
                                      id,
                                      code_subarticle,
                                      description,
                                      unit,
                                      p_unit,
                                      fisico_inicial,
                                      fisico_ingreso,
                                      (fisico_inicial + fisico_ingreso - fisico_final) as fisico_egreso,
                                      fisico_final,
                                      valorado_inicial,
                                      valorado_ingreso,
                                      (valorado_inicial + valorado_ingreso - valorado_final) as valorado_egreso,
                                      valorado_final
                                 from (select material_id,
                                              code_material,
                                              description_material,
                                              id,
                                              code_subarticle,
                                              description,
                                              unit,
                                              p_unit,
                                              CAST(SUBSTRING_INDEX(saldo_final_fecha_1,'|', 1) AS DECIMAL(10,2)) as fisico_inicial,
                                              CAST(SUBSTRING_INDEX(total_ingresos,'|', 1) AS DECIMAL(10,2)) as fisico_ingreso,
                                              CAST(SUBSTRING_INDEX(saldo_final_fecha_2,'|',1) AS DECIMAL(10,2)) as fisico_final,
                                              CAST(SUBSTRING_INDEX(saldo_final_fecha_1,'|',-1) AS DECIMAL(10,2)) as valorado_inicial,
                                              CAST(SUBSTRING_INDEX(total_ingresos,'|',-1) AS DECIMAL(10,2)) as valorado_ingreso,
                                              CAST(SUBSTRING_INDEX(saldo_final_fecha_2,'|',-1) AS DECIMAL(10,2)) as valorado_final
                                         from (select s2.material_id,
                                                      m2.code as code_material,
                                                      m2.description as description_material,
                                                      s2.id,
                                                      s2.code as code_subarticle,
                                                      s2.description,
                                                      s2.unit,
                                                      sum(es.unit_cost),
                                                      count(s2.id),
                                                      sum(case when es.unit_cost is null then 0 else es.unit_cost end)/count(s2.id) as p_unit,
                                                      total_ingresos_v1(s2.id, cast(concat('$fecha_inicio', ' 00:00:00') as datetime), cast(concat('$fecha_fin', ' 23:59:59') as datetime)) as total_ingresos,
                                          saldo_final_v1(s2.id, cast(concat('$fecha_fin', ' 23:59:59') as datetime)) as saldo_final_fecha_2,
                                          saldo_final_v1(s2.id, cast(concat('$fecha_inicio', ' 00:00:00') as datetime)) as saldo_final_fecha_1
                                     from subarticles s2 inner join materials m2 on s2.material_id = m2.id left join entry_subarticles es on es.subarticle_id=s2.id and es.date between cast(concat('$fecha_inicio', ' 00:00:00') as datetime) and cast(concat('$fecha_fin', ' 23:59:59') as datetime)
                                     where s2.status in ('1','0')
                                     and m2.status = 1 
                                     and m2.code like ('%$codigo%') 
                                                 group by s2.material_id,
                                        m2.description,
                                        m2.code,
                                        s2.id,
                                        s2.code,
                                        s2.description,
                                        s2.unit) t1) t2
                                                 where (fisico_inicial > 0 or fisico_ingreso > 0 or fisico_final > 0)) t3
            order by code_material, code_subarticle)t4"));
            return $totales;
    }
    
}
