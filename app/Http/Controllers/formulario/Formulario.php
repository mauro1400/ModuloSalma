<?php

namespace App\Http\Controllers\Formulario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

class Formulario extends Controller
{
    public function index()
    {
        return view('formulario.index');
    }

    public function guardarDatos(Request $request)
{
    $descripcion = $request->input('descripcion');
    $unidad_medida = $request->input('unidad_medida');
    $cantidad = $request->input('cantidad');
    $observaciones = $request->input('observaciones');
    
    $datos_tabla = $request->json('datos_tabla');
    foreach ($datos_tabla as $fila) {
        $descripcion = $fila['descripcion'];
        $unidad_medida = $fila['unidad_medida'];
        $cantidad = $fila['cantidad'];
        $observaciones = $fila['observaciones'];
        // Guardar los datos en la base de datos, por ejemplo
    }
    dd($datos_tabla);
}
}
