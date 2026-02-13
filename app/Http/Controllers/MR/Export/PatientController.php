<?php

namespace App\Http\Controllers\MR\Export;

use App\Http\Controllers\Controller;
use App\Exports\MR\PatientExport;
use Maatwebsite\Excel\Facades\Excel;

class PatientController extends Controller
{
    //Functionf for export patient record
    public function export_patient() {
        return Excel::download(new PatientExport, 'MR_TADA_Report.xlsx');
    }
}
