<?php

namespace App\Http\Controllers\reporte;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\ConsultaReporteArticulos;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    try {
      // Código que puede generar una excepción
      $codig = DB::table('materials')
        ->select('materials.code', DB::raw("CONCAT(materials.code,'-',materials.description) as codigo"))
        ->get();

      $codigo = request('codigo');
      $fecha_inicio = request('fecha_inicio');
      $fecha_fin = request('fecha_fin');

      $reporteArticulos = ConsultaReporteArticulos::obtenerInformacion($codigo, $fecha_inicio, $fecha_fin);

      $totales = ConsultaReporteArticulos::total($codigo, $fecha_inicio, $fecha_fin);
      
      return view('reporte.ReporteArticulos.index', ["codig" => $codig, "reporteArticulos" => $reporteArticulos, "totales" => $totales]);
    } catch (\Exception $e) {
      return response()->json([
          'message' => 'Error interno',
          'errors' => ['exception' => [$e->getMessage()]]
      ], 500);
    }
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

    $totales = ConsultaReporteArticulos::total($codigo, $fecha_inicio, $fecha_fin);

    $query = ConsultaReporteArticulos::obtenerInformacion($codigo, $fecha_inicio, $fecha_fin);

    foreach ($query as $item) {
      $hoja->setCellValue('A' . $fila, $item->code_subarticle)->getStyle('A' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('B' . $fila, $item->description)->getStyle('B' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('C' . $fila, $item->description_material)->getStyle('C' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('D' . $fila, $item->unit)->getStyle('D' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('E' . $fila, $item->p_unitario)->getStyle('E' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('F' . $fila, $item->fisico_inicial)->getStyle('F' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('G' . $fila, $item->fisico_ingreso)->getStyle('G' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('H' . $fila, $item->fisico_egreso)->getStyle('H' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('I' . $fila, $item->fisico_final)->getStyle('I' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('J' . $fila, $item->valorado_inicial)->getStyle('J' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('K' . $fila, $item->valorado_ingreso)->getStyle('K' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('L' . $fila, $item->valorado_egreso)->getStyle('L' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('M' . $fila, $item->valorado_final)->getStyle('M' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $fila++;
      $p = $fila;
    }
    //dd($p);
    foreach ($totales as $item) {
      $hoja->setCellValue('J' . $p, $item->valorado_inicial)->getStyle('B7')->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('K' . $p, $item->valorado_ingreso)->getStyle('B7')->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('L' . $p, $item->valorado_egreso)->getStyle('B7')->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      $hoja->setCellValue('M' . $p, $item->valorado_final)->getStyle('B7')->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }
    $hoja->getStyle('A' . $p . ':M' . $p)->getNumberFormat()->setFormatCode('#,##0.00');
    $hoja->fromArray($cabecera0, null, 'A' . $p)->mergeCells('A' . $p . ':I' . $p)->getStyle('A' . $p . ':I' . $p)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $hoja->getStyle('A' . $p . ':M' . $p)->applyFromArray($borde);
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

    $hoja->getPageSetup()->setScale(55);

    $nombreDelDocumento = "Reporte_Articulos.$hoy.xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
    header('Cache-Control: max-age=0');
    $writer = IOFactory::createWriter($documento, 'Xlsx');
    $writer->save('php://output');
  }
}
