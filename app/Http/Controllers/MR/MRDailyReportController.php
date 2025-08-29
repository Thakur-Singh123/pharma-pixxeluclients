<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MrDailyReport;

class MRDailyReportController extends Controller
{
    //function for show all daily reports
    public function index()
    {
        $reports = MrDailyReport::where('mr_id', auth()->id())
            ->orderBy('report_date', 'desc')
            ->paginate(10);
        return view('mr.daily_reports.index',compact('reports')); 
    }

    //function for create daily report
    public function create()
    {
        return view('mr.daily_reports.create');
    }

    //function for store daily report
    public function store(Request $request)
    {
        //validate the request
        $request->validate([
            'report_date' => 'required|date',
            'total_visits' => 'required|integer|min:0',
            'patients_referred' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);
        //store the daily report
        MrDailyReport::create([
            'mr_id' => auth()->id(),
            'report_date' => $request->report_date, 
            'total_visits' => $request->total_visits,
            'patients_referred' => $request->patients_referred,
            'notes' => $request->notes,
        ]);

        return redirect()->route('mr.daily-reports.index')->with('success','Daily Report created successfully.');
    }

}
