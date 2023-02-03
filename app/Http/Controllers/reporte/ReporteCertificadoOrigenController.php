<?php

namespace App\Http\Controllers\reporte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteCertificadoOrigenController extends Controller
{
    public function index()
    {
        return view('reporte.reporteCertificadoOrigen.index');
    }

    public function busquedaFechas()
    {
        $opcion = 1;
        $fechaInicio = request('fechaInicio');
        $fechaFin = request('fechaFin');
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
        //dd($opcion);
        return view('reporte.reporteCertificadoOrigen.tabla', ["reporteCertificadoOrigen" => $busquedaFechas, "opcion" => $opcion]);
    }
    public function busquedaRegional()
    {
        $codigoRegional = DB::table('departments')->select('name')->get();
        $opcion = 2;
        $regional = request('regional');
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
        //dd($busquedaRegional);
        //dd(count($busquedaRegional));
        //dd($opcion);
        return view('reporte.reporteCertificadoOrigen.tabla', ['reporteCertificadoOrigen' => $busquedaRegional, 'codigoRegional' => $codigoRegional, "opcion" => $opcion]);
    }

    public function filtroFecha()
    {
        $reporteCertificadoOrigen = DB::select('SELECT t.*, (((t.al-t.del)+1)/25) as certificados FROM (
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
        return view('reporte.reporteCertificadoOrigen.fecha', ["reporteCertificadoOrigen" => $reporteCertificadoOrigen]);
    }

    public function filtroRegional()
    {
        $codigoRegional = DB::table('departments')->select('name')->get();
        $reporteCertificadoOrigen = DB::select('SELECT t.*, (((t.al-t.del)+1)/25) as certificados FROM (
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
        return view('reporte.reporteCertificadoOrigen.regional', compact('reporteCertificadoOrigen', 'codigoRegional'));
    }

    public function exportarReporteRegional()
    {

        
        $regional = request('regional');
        $hoy = now();
        $documento = new Spreadsheet();
        $documento
            ->getProperties()
            ->setCreator("SENAVEX")
            ->setLastModifiedBy('SENAVEX') // última vez modificado por
            ->setTitle('Reporte Partidas');
        $hoja = $documento->getActiveSheet();
        $hoja->setTitle("Rep-Cert-Orig-Reg");

        $hoja->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $hoja->getPageSetup()->setScale(60);

        $cabecera1 = ["REPORTE CERTIFICADO DE ORIGEN"];
        $hoja->fromArray($cabecera1, null, 'A2')->mergeCells('A2:M2')->getStyle('A2:M2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $cabeceraFecha = ["Unidad: $regional"];
        $hoja->fromArray($cabeceraFecha, null, 'A3')->mergeCells('A3:M3')->getStyle('A3:M3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $encabezado = ["Fecha de Entrega", "Nro Solicitud", "Solicitante ", "Administrador", "Departamento", "Articulo", "Block Certificado", "Entregado", "Total Entregado", "Observacion", "Del", "Al", "Certificado",];
        $hoja->getStyle('A5:M5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('bacbe6');

        $hoja->getStyle('A5:M5')->getAlignment()->setWrapText(true);
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
        $hoja->getStyle('A5:M5')->applyFromArray($borde);

        $hoja->getCell('A2')->getStyle()->getFont()->setSize(15);
        $hoja->getCell('A3')->getStyle()->getFont()->setSize(10);

        $hoja->fromArray($encabezado, null, 'A5');
        $hoja->getStyle('A5:M5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $hoja->getColumnDimension('A')->setWidth(12);
        $hoja->getColumnDimension('B')->setWidth(10);
        $hoja->getColumnDimension('C')->setWidth(25);
        $hoja->getColumnDimension('D')->setWidth(25);
        $hoja->getColumnDimension('E')->setWidth(25);
        $hoja->getColumnDimension('F')->setWidth(25);
        $hoja->getColumnDimension('H')->setWidth(12);
        $hoja->getColumnDimension('I')->setWidth(12);
        $hoja->getColumnDimension('J')->setWidth(15);
        $hoja->getColumnDimension('G')->setWidth(12);
        $hoja->getColumnDimension('M')->setWidth(12);

        
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

        $fila = 6;
        foreach ($busquedaRegional as $item) {
            $hoja->setCellValue('A' . $fila, $item->fecha_entrega)->getStyle('A' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('B' . $fila, $item->nro_solicitud)->getStyle('B' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('C' . $fila, $item->solicitante)->getStyle('C' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('D' . $fila, $item->administrador)->getStyle('D' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('E' . $fila, $item->departamento)->getStyle('E' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('F' . $fila, $item->articulo)->getStyle('F' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('G' . $fila, $item->pedido)->getStyle('G' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('H' . $fila, $item->entregado)->getStyle('H' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('I' . $fila, $item->total_entregado)->getStyle('I' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('J' . $fila, $item->observacion)->getStyle('J' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('K' . $fila, $item->del)->getStyle('K' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('L' . $fila, $item->al)->getStyle('L' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('M' . $fila, $item->certificados)->getStyle('M' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->getStyle('A'.$fila.':M'.$fila)->getAlignment()->setWrapText(true);
            $fila++;
        }

        $logo = new MemoryDrawing();
        $logo->setName('Image');
        $logo->setDescription('Image');
        $logo->setImageResource(imagecreatefromjpeg(public_path('logo/logo_senavex.jpg')));
        $logo->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
        $logo->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
        $logo->setHeight(80);
        $logo->setCoordinates('A1');
        $logo->setWorksheet($hoja);

        $nombreDelDocumento = "Reporte-Certidicado-de-Origen.$hoy.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
    }

    public function exportarReporteFechas()
    {
        $fechaInicio = request('fechaInicio');
        $fechaFin = request('fechaFin');
        $hoy = now();
        $documento = new Spreadsheet();
        $documento
            ->getProperties()
            ->setCreator("SENAVEX")
            ->setLastModifiedBy('SENAVEX') // última vez modificado por
            ->setTitle('Reporte Partidas');
        $hoja = $documento->getActiveSheet();
        $hoja->setTitle("Rep-Cert-de-Orig-Fechas");

        $hoja->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $hoja->getPageSetup()->setScale(60);

        $cabecera1 = ["REPORTE CERTIFICADO DE ORIGEN"];
        $hoja->fromArray($cabecera1, null, 'A2')->mergeCells('A2:M2')->getStyle('A2:M2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $cabeceraFecha = ["Del $fechaInicio Al $fechaFin"];
        $hoja->fromArray($cabeceraFecha, null, 'A3')->mergeCells('A3:M3')->getStyle('A3:M3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $hoja->getCell('A2')->getStyle()->getFont()->setSize(15);
        $hoja->getCell('A3')->getStyle()->getFont()->setSize(10);

        $encabezado = ["Fecha de Entrega", "Nro Solicitud", "Solicitante ", "Administrador", "Departamento", "Articulo", "Block Certificado", "Entregado", "Total Entregado", "Observacion", "Del", "Al", "Certificado",];
        $hoja->getStyle('A5:M5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('bacbe6');

        $hoja->getStyle('A5:M5')->getAlignment()->setWrapText(true);
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
        $hoja->getStyle('A5:M5')->applyFromArray($borde);

        $hoja->fromArray($encabezado, null, 'A5');
        $hoja->getStyle('A5:M5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $hoja->getColumnDimension('A')->setWidth(12);
        $hoja->getColumnDimension('B')->setWidth(10);
        $hoja->getColumnDimension('C')->setWidth(25);
        $hoja->getColumnDimension('D')->setWidth(25);
        $hoja->getColumnDimension('E')->setWidth(25);
        $hoja->getColumnDimension('F')->setWidth(25);
        $hoja->getColumnDimension('H')->setWidth(12);
        $hoja->getColumnDimension('I')->setWidth(12);
        $hoja->getColumnDimension('J')->setWidth(15);
        $hoja->getColumnDimension('G')->setWidth(12);
        $hoja->getColumnDimension('M')->setWidth(12);

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

        $fila = 6;
        foreach ($busquedaFechas as $item) {
            $hoja->setCellValue('A' . $fila, $item->fecha_entrega)->getStyle('A' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('B' . $fila, $item->nro_solicitud)->getStyle('B' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('C' . $fila, $item->solicitante)->getStyle('C' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('D' . $fila, $item->administrador)->getStyle('D' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('E' . $fila, $item->departamento)->getStyle('E' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('F' . $fila, $item->articulo)->getStyle('F' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('G' . $fila, $item->pedido)->getStyle('G' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('H' . $fila, $item->entregado)->getStyle('H' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('I' . $fila, $item->total_entregado)->getStyle('I' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('J' . $fila, $item->observacion)->getStyle('J' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('K' . $fila, $item->del)->getStyle('K' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('L' . $fila, $item->al)->getStyle('L' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->setCellValue('M' . $fila, $item->certificados)->getStyle('M' . $fila)->getBorders()->getallBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $hoja->getStyle('A'.$fila.':M'.$fila)->getAlignment()->setWrapText(true);
            $fila++;
        }

        $logo = new MemoryDrawing();
        $logo->setName('Image');
        $logo->setDescription('Image');
        $logo->setImageResource(imagecreatefromjpeg(public_path('logo/logo_senavex.jpg')));
        $logo->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
        $logo->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
        $logo->setHeight(80);
        $logo->setCoordinates('A1');
        $logo->setWorksheet($hoja);

        $nombreDelDocumento = "Reporte-Certidicado-de-Origen.$hoy.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
    }
}
