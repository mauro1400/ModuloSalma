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

   
}
