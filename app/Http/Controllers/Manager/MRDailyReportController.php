<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\MrDailyReport;
use Illuminate\Http\Request;

class MRDailyReportController extends Controller
{
    //function for show all daily reports
    public function index()
    {
        $mr = auth()->user()->mrs->pluck('id')->toArray();
        //echo "<pre>";print_r($mr);exit;
        $reports = MrDailyReport::with('mr')->where('mr_id', $mr)
            ->orderBy('report_date', 'desc')
            ->paginate(10);
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


}
