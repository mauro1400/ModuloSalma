<?php

namespace App\Http\Controllers\reporte;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Http\Controllers\Controller;
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
                     ORDER BY entry_date, note_entry_id, entry_subarticle_id) t1"))
                  ->leftJoin('subarticles as s', 's.id', '=', 't1.subarticle_id')
                  ->leftJoin('materials as m', 's.material_id', '=', 'm.id')
                  ->leftJoin('suppliers as su', 'su.id', '=', 't1.supplier_id')
                  ->where('s.created_at', '<=', "$fecha_fin")
                  ->where('s.created_at', '>=', "$fecha_inicio")
                  ->where('s.code', 'like', "%$codigo%")
                  ->where('s.status', '=', 1)
                  ->where('m.status', '=', 1)
                  ->paginate(100);
                  
                  //dd(substr(($codig[0]->code), 0, -1));
                  //dd(substr((request('codigo')), 0, -1));
                  return view('reporte.ReporteArticulos.index', ["codig" => $codig, "reporteArticulos" => $reporteArticulos]);
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
            $cabecera2 = ["(Expresados en Bolivianos)"];

            $encabezado1 = ["CODIGO", "PARTIDA PRESUPRESTARIA", "UNIDAD", "PARTIDA", "NUMERO FACTURA", "DETALLE", "PRECIO UNITARIO"];
            $encabezado2 = ["Ingreso", "Egreso", "Saldo", "Ingreso*", "Egreso*", "Saldo*"];
            $encabezado3 = ["FISICO", "", "", "VALORADO"];

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
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('F5:F6')->getStyle('F5:F6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('G5:G6')->getStyle('G5:G6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $hoja->fromArray($encabezado3, null, 'H5')->mergeCells('H5:J5')->getStyle('H5:J5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado3, null, 'H5')->mergeCells('K5:M5')->getStyle('K5:M5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $hoja->fromArray($encabezado2, null, 'H6')->getStyle('I6')->getFill()->getStartColor()->setARGB('FFFF0000');
            $hoja->fromArray($encabezado2, null, 'H6');
            $hoja->fromArray($encabezado2, null, 'H6');

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

            $query = DB::table('subarticles')
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
                  ->where('s.code', 'like', "%$codigo%")
                  ->where('s.status', '=', 1)
                  ->where('m.status', '=', 1)
                  ->get();

            foreach ($query as $item) {
                  $hoja->setCellValue('A' . $fila, $item->codigo)->getStyle('A' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('B' . $fila, $item->description)->getStyle('B' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('C' . $fila, $item->uni_med)->getStyle('C' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('D' . $fila, $item->partida)->getStyle('D' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('E' . $fila, $item->num_fac)->getStyle('E' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('F' . $fila, $item->detalle)->getStyle('F' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('G' . $fila, $item->p_unitario)->getStyle('G' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('H' . $fila, $item->ingreso)->getStyle('H' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('I' . $fila, '')->getStyle('I' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('J' . $fila, '')->getStyle('J' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('K' . $fila, $item->t_ingreso)->getStyle('K' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('L' . $fila, '')->getStyle('L' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('M' . $fila, '')->getStyle('M' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $fila++;
            }

            $hoy = now();
            $nombreDelDocumento = "Reporte_Articulos.$hoy.xlsx";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
            header('Cache-Control: max-age=0');
            $writer = IOFactory::createWriter($documento, 'Xlsx');
            $writer->save('php://output');
      }
}
