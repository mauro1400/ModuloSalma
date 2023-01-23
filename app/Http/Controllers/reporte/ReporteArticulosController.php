<?php

namespace App\Http\Controllers\reporte;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Exports\ReporteArticulosExport;
use GuzzleHttp\Psr7\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Row;
use mysqli;
use PhpParser\ErrorHandler\Collecting;

class ReporteArticulosController extends Controller
{

      public function inicio()
      {
            $codig = DB::table('subarticles')->select('code')->get();
            //dd($query);
            return view('reporte.ReporteArticulos.home', ["codig" => $codig]);
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
            $codigo = request('codigo');
            $fecha_inicio = request('fecha_inicio');
            $fecha_fin = request('fecha_fin');

            $documento = new Spreadsheet();
            $documento
                  ->getProperties()
                  ->setCreator("Aquí va el creador, como cadena")
                  ->setLastModifiedBy('Parzibyte') // última vez modificado por
                  ->setTitle('Mi primer documento creado con PhpSpreadSheet')
                  ->setSubject('El asunto')
                  ->setDescription('Este documento fue generado para parzibyte.me')
                  ->setKeywords('etiquetas o palabras clave separadas por espacios')
                  ->setCategory('La categoría');
            $hoja = $documento->getActiveSheet();
            $hoja->setTitle("Reporte de Articulos");
            //$hoja->mergeCells('A1:N1');
            $cabeceraFecha = ["Del $fecha_inicio Al $fecha_fin"];
            $cabecera1 = ["INVENTARIO DE ALMACENES FISICO VALORADO SENAVEX"];
            $cabecera2 = ["(Expresados en Bolivianos)"];

            $encabezado1 = ["Codigo", "Cod_ant", "Description", "Uni_med", "Partida", "Num_fac", "Detalle", "Precio_u"];
            $encabezado2 = ["Ingreso", "Egreso", "Saldo", "Ingreso*", "Egreso*", "Saldo*"];
            $encabezado3 = ["FISICO", "", "", "VALORADO"];

            $hoja->fromArray($cabecera1, null, 'A1')->mergeCells('A1:N1')->getStyle('A1:N1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($cabecera2, null, 'A3')->mergeCells('A3:N3')->getStyle('A3:N3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($cabeceraFecha, null, 'A2')->mergeCells('A2:N2')->getStyle('A2:N2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('A5:A6')->getStyle('A5:A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('B5:B6')->getStyle('B5:B6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('C5:C6')->getStyle('C5:C6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('D5:D6')->getStyle('D5:D6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('E5:E6')->getStyle('E5:E6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('F5:F6')->getStyle('F5:F6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('G5:G6')->getStyle('G5:G6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado1, null, 'A5')->mergeCells('H5:H6')->getStyle('H5:H6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $hoja->fromArray($encabezado3, null, 'I5')->mergeCells('I5:K5')->getStyle('I5:K5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $hoja->fromArray($encabezado3, null, 'I5')->mergeCells('L5:N5')->getStyle('L5:N5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $hoja->fromArray($encabezado2, null, 'I6');
            $hoja->fromArray($encabezado2, null, 'I6');
            $hoja->fromArray($encabezado2, null, 'I6');

            $styleArray = [
                  'borders' => [
                        'allBorders' => [
                              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                        ],
                  ],
            ];

            $hoja->getStyle('A5:N6')->applyFromArray($styleArray);

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
                  ->where('s.code', '=', "$codigo")
                  ->where('s.status', '=', 1)
                  ->where('m.status', '=', 1)
                  ->get();
            //dd($query[0]);

            foreach ($query as $item) {
                  $hoja->setCellValue('A' . $fila, $item->codigo)->getStyle('A' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('B' . $fila, $item->cod_ant)->getStyle('B' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('C' . $fila, $item->description)->getStyle('C' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('D' . $fila, $item->uni_med)->getStyle('D' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('E' . $fila, $item->partida)->getStyle('E' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('F' . $fila, $item->num_fac)->getStyle('F' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('G' . $fila, $item->detalle)->getStyle('G' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('H' . $fila, $item->p_unitario)->getStyle('H' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('I' . $fila, $item->p_unitario)->getStyle('I' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('J' . $fila, '')->getStyle('J' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('K' . $fila, '')->getStyle('K' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('L' . $fila, $item->t_ingreso)->getStyle('L' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('M' . $fila, '')->getStyle('M' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $hoja->setCellValue('N' . $fila, '')->getStyle('N' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                  $fila++;
            }

            $hoy = now();
            $nombreDelDocumento = "Reporte_Articulos.$hoy.xlsx";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
            header('Cache-Control: max-age=0');
            $writer = IOFactory::createWriter($documento, 'Xlsx');
            $writer->save('php://output');
            /*return Excel::download(new ReporteArticulosExport(request('codigo'),request('fecha_inicio'), request('fecha_fin')), 'users.xlsx');*/
      }
}
