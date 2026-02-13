<?php

namespace App\Http\Controllers\MR\Export;

use App\Http\Controllers\Controller;
use App\Exports\MR\TadaExport;
use Maatwebsite\Excel\Facades\Excel;

class TadaExportController extends Controller
{
    //Functionf for export tada record
    public function export_tada() {
        return Excel::download(new TadaExport, 'MR_TADA_Report.xlsx');
    }
}
