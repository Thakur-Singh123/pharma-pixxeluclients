<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CampReportExport;

class CampReportExportController extends Controller
{
    //Function for camp report export
    public function export_campReport() {
        return Excel::download(new CampReportExport, 'camp_report.xlsx');
    }
}
