<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\MrDailyReport;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class MRDailyReportController extends Controller
{
    //function for show all daily reports
    public function index(Request $request)
    {
        $mrIds = auth()->user()->mrs->pluck('id')->toArray();

        $query = MrDailyReport::with('mr')->whereIn('mr_id', $mrIds);
        //Filter Logic
        $filter = $request->get('filter_by') ?? 'today';
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

        $reports = $query->orderBy('report_date', 'desc')->paginate(10);

        return view('manager.daily_reports.index', compact('reports'));
    }
    //function for review report
    public function review(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $report = MrDailyReport::findOrFail($id);

        $report->update([
            'status' => $request->action == 'approve' ? 'approved' : 'rejected',
            'manager_id' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Report ' . $request->action . 'ed successfully.');
    }

    //edit daily report
    public function edit($id) {
        //Get report detail
        $report_detail = MrDailyReport::find($id);
        return view('manager.daily_reports.edit',compact('report_detail')); 
    }

     //Function for update daily report
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'report_date' =>'required|date',
            'total_visits' =>'required|integer|min:0',
            'patients_referred' =>'required|integer|min:0',
            'notes' =>'nullable|string',
        ]);
        //Update daily report
        $is_update_report = MrDailyReport::where('id', $id)->update([
            'report_date' => $request->report_date, 
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

    //function for download
    public function export(Request $request)
    {
        $filter = $request->get('filter_by');
        return Excel::download(new ReportsExport($filter), 'daily_reports.xlsx');
    }

}
