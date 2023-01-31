<?php

namespace App\Http\Controllers\reporte;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;

class ReporteArticulosController extends Controller
{

      public function inicio()
      {

            $codig = DB::table('materials')
                  ->select('materials.code', DB::raw("CONCAT(materials.code,'-',materials.description) as codigo"))
                  ->get();
            return view('reporte.ReporteArticulos.home', ["codig" => $codig]);
      }

      public function busquedaCodigo()
      {
            $codig = DB::table('materials')
                  ->select('materials.code', DB::raw("CONCAT(materials.code,'-',materials.description) as codigo"))
                  ->get();

            $codigo = request('codigo');
            $fecha_inicio = request('fecha_inicio');
            $fecha_fin = request('fecha_fin');

            $reporteArticulos = DB::select(DB::raw(
                  "select material_id,
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
            $totales = DB::select(DB::raw("select 
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
            //dd(substr($reporteArticulos[1]->code_subarticle, 0, -1));
            //dd($reporteArticulos[1]->code_subarticle);
            //dd($reporteArticulos);
            //dd($totales);
            return view('reporte.ReporteArticulos.index', ["codig" => $codig, "reporteArticulos" => $reporteArticulos,"totales"=>$totales]);
      }

      public function exportarReporteArticulos()
      {
            $codigo = request('codigo');
            $fecha_inicio = request('fecha_inicio');
            $fecha_fin = request('fecha_fin');

            $documento = new Spreadsheet();
            $documento
                  ->getProperties()
                  ->setCreator("SENAVEX")
                  ->setLastModifiedBy('SENAVEX') // última vez modificado por
                  ->setTitle('Reporte Articulos');
            $hoja = $documento->getActiveSheet();
            $hoja->setTitle("Reporte de Articulos");
            $cabeceraFecha = ["Del $fecha_inicio Al $fecha_fin"];
            $cabecera1 = ["INVENTARIO DE ALMACENES FISICO VALORADO SENAVEX"];
            $cabecera0 = ["TOTALES"];
            $cabecera2 = ["(Expresados en Bolivianos)"];

            $encabezado1 = ["CODIGO", "PARTIDA PRESUPRESTARIA", "PARTIDA", "UNIDAD", "PRECIO UNITARIO"];
            $encabezado2 = ["Inicio", "Ingreso", "Egreso", "Saldo", "Inicio*", "Ingreso*", "Egreso*", "Saldo*"];
            $encabezado3 = ["FISICO", "", "", "", "VALORADO"];

            $hoja->getCell('A1')->getStyle()->getFont()->setSize(15);
            $hoja->getCell('A3')->getStyle()->getFont()->setSize(10);
            
            

            $hoja->fromArray($cabecera1, null, 'A1')->mergeCells('A1:M1')->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($cabecera2, null, 'A3')->mergeCells('A3:M3')->getStyle('A3:M3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($cabeceraFecha, null, 'A2')->mergeCells('A2:M2')->getStyle('A2:M2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $hoja->getColumnDimension('B')->setAutoSize(true);
            $hoja->getColumnDimension('D')->setAutoSize(true);

            $hoja->getStyle('A5:M6')->getAlignment()->setWrapText(true);
            $hoja->getStyle('D')->getAlignment()->setWrapText(true);

            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('A5:A6')->getStyle('A5:A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('B5:B6')->getStyle('B5:B6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('C5:C6')->getStyle('C5:C6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('D5:D6')->getStyle('D5:D6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('E5:E6')->getStyle('E5:E6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


            $hoja->fromArray($encabezado3, null, 'F5')->mergeCells('F5:I5')->getStyle('F5:I5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado3, null, 'F5')->mergeCells('J5:M5')->getStyle('J5:M5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $hoja->fromArray($encabezado2, null, 'F6')->getStyle('H6')->getFill()->getStartColor()->setARGB('FFFF0000');
            $hoja->fromArray($encabezado2, null, 'F6');
            $hoja->fromArray($encabezado2, null, 'F6');

            $hoja->getStyle('A5:M6')->getFill()
                  ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                  ->getStartColor()->setARGB('bacbe6');

            $borde = [

                  'font' => [
                        'bold' => true,
                  ],
                  'borders' => [
                        'allBorders' => [
                              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                        ],
                  ],
            ];
            $hoja->getStyle('A5:M6')->applyFromArray($borde);
            

            $hoja->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $hoja->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER);

            $codigo = request('codigo');
            $fecha_inicio = request('fecha_inicio');
            $fecha_fin = request('fecha_fin');
            $fila = 7;
            $totales = DB::select(DB::raw("select 
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
            $query = DB::select(DB::raw(
                  "select material_id,
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

            //$hoja->setCellValue('A7', $query[0]->code_material)->getStyle('A7')->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            
            //dd($query);
            foreach ($query as $item) {
                  $hoja->setCellValue('A' . $fila, $item->code_subarticle)->getStyle('A' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('B' . $fila, $item->description)->getStyle('B' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('C' . $fila, $item->description_material)->getStyle('C' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('D' . $fila, $item->unit)->getStyle('D' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('E' . $fila, $item->p_unit)->getStyle('E' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('F' . $fila, $item->fisico_inicial)->getStyle('F' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('G' . $fila, $item->fisico_ingreso)->getStyle('G' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('H' . $fila, $item->fisico_egreso)->getStyle('H' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('I' . $fila, $item->fisico_final)->getStyle('I' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('J' . $fila, $item->valorado_inicial)->getStyle('J' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('K' . $fila, $item->valorado_ingreso)->getStyle('K' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('L' . $fila, $item->valorado_egreso)->getStyle('L' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('M' . $fila, $item->valorado_final)->getStyle('M' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $fila++;
                  $p=$fila;
            }
           //dd($p);
            foreach ($totales as $item) {
                  $hoja->setCellValue('J'.$p, $item->valorado_inicial)->getStyle('B7')->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('K'.$p, $item->valorado_ingreso)->getStyle('B7')->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('L'.$p, $item->valorado_egreso)->getStyle('B7')->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('M'.$p, $item->valorado_final)->getStyle('B7')->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $hoja->getStyle('A'.$p.':M'.$p)->getNumberFormat()->setFormatCode('#,##0.00');
            $hoja->fromArray($cabecera0, null, 'A'.$p)->mergeCells('A'.$p.':I'.$p)->getStyle('A'.$p.':I'.$p)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->getStyle('A'.$p.':M'.$p)->applyFromArray($borde);
            $hoy = now();
            //insercion de logo
            $logo = new MemoryDrawing();
$logo->setName('Image');
$logo->setDescription('Image');
$logo->setImageResource(imagecreatefromjpeg(public_path('logo/logo_senavex.jpg')));
$logo->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
$logo->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
$logo->setHeight(80);
$logo->setCoordinates('A1');
$logo->setWorksheet($hoja);

            $nombreDelDocumento = "Reporte_Articulos.$hoy.xlsx";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
            header('Cache-Control: max-age=0');
            $writer = IOFactory::createWriter($documento, 'Xlsx');
            $writer->save('php://output');
      }
}
