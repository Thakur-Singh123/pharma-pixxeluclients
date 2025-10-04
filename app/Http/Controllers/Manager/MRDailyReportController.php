<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\MrDailyReport;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Doctor;

class MRDailyReportController extends Controller
{
    //Function for show all daily reports
    public function index(Request $request) {
        //Get auth login
        $mrIds = auth()->user()->mrs->pluck('id')->toArray();
        //Query
        $query = MrDailyReport::with('mr','doctor_detail')->whereIn('mr_id', $mrIds);
        //Filter Logic
        $filter = $request->get('filter_by') ?? 'all';
        if ($filter === 'today') {
            $query->whereDate('report_date', now());
        } elseif ($filter === 'week') {
            $query->whereBetween('report_date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
        } elseif ($filter === 'month') {
            $query->whereMonth('report_date', now()->month);
        } elseif($filter === 'year') {
            $query->whereYear('report_date', now()->year);
        } elseif($filter === 'all') {
            $query->whereNotNull('report_date');    
        }
        //Get reports records
        $reports = $query->orderBy('report_date', 'desc')->paginate(10);

        return view('manager.daily_reports.index', compact('reports'));
    }

    //Function for review report
    public function review(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);
        //Get daily report 
        $report = MrDailyReport::findOrFail($id);
        //Update daily report
        $report->update([
            'status' => $request->action == 'approve' ? 'approved' : 'rejected',
            'manager_id' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Report ' . $request->action . 'ed successfully.');
    }

    //Function for edit daily report
    public function edit($id) {
        //Get report detail
        $report_detail = MrDailyReport::with('doctor_detail')->find($id);
        return view('manager.daily_reports.edit',compact('report_detail')); 
    }

    //Function for update daily report
    public function update(Request $request, $id) {
        //Validate input fields
         $request->validate([
            'doctor_id' =>'required',
            'report_date' =>'required|date',
            'area_name' =>'required',
            'total_visits' =>'required|integer|min:0',
            'patients_referred' =>'required|integer|min:0',
            'notes' =>'nullable|string',
        ]);
        //Update daily report
        $is_update_report = MrDailyReport::where('id', $id)->update([
            'doctor_id' => $request->doctor_id, 
            'report_date' => $request->report_date,
            'area_name' => $request->area_name,  
            'total_visits' => $request->total_visits,
            'patients_referred' => $request->patients_referred,
            'notes' => $request->notes,
        ]);
        //Check if report Updated or not
        if($is_update_report) {
            return redirect()->route('manager.daily-reports.index')->with('success','Daily report updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to updated Daily report!');
        }
    }

    //Function for download excel sheet
    public function export(Request $request) {
        //Filter
        $filter = $request->get('filter_by');
        return Excel::download(new ReportsExport($filter), 'daily_reports.xlsx');
    }

}
