<?php

namespace App\Exports;

use App\Models\Reportec;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReportecExport implements FromCollection
{
    public function collection()
    {
        return Reportec::all();
    }
}
