<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\DailyReport;
use App\Models\DailyReportDetail;
use App\Models\Doctor;
use Auth;

class MRDailyReportController extends Controller
{
    //Function for show all daily reports
    public function index(Request $request) {
        //Get auth login detail
        $auth_login = Auth::id();
        //Query
        $query = DailyReport::with('mr_details','report_details.doctor')->where('manager_id', $auth_login);
        //Filter
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
        $reports = $query->orderBy('ID','DESC')->paginate(5);
        // echo "<pre>"; print_r($reports->toArray());exit;
        return view('manager.daily_reports.index', compact('reports'));
    }

    //Function for review report
    // public function review(Request $request, $id) {
    //     //Validate input fields
    //     $request->validate([
    //         'action' => 'required|in:approve,reject',
    //     ]);
    //     //Get daily report 
    //     $report = DailyReport::findOrFail($id);
    //     //Update daily report
    //     $report->update([
    //         'status' => $request->action == 'approve' ? 'approved' : 'rejected',
    //         'manager_id' => auth()->id(),
    //         'reviewed_at' => now(),
    //     ]);

    //     return back()->with('success', 'Report ' . $request->action . 'ed successfully.');
    // }

    //Function for approval report
    public function report_approve($id) {
        //Get report detail
        $report_detail = DailyReport::findOrFail($id);
        //Update status
        $report_detail->status = 'Approved';
        $report_detail->approved_by = '1';
        $report_detail->save();

        return redirect()->back()->with('success', 'Report approved successfully.');
    }

    //Function for reject report
    public function report_reject($id) {
        //Get report detail
        $report_detail = DailyReport::findOrFail($id);
        //Update status
        $report_detail->status = 'Reject';
        $report_detail->approved_by = '0';
        $report_detail->save();
        
        return redirect()->back()->with('success', 'Report reject successfully.');
    }

    //Function for edit daily report
    public function edit($id) {
        //Get report detail
        $report_detail = DailyReport::with('report_details')->find($id);
        //Get auth login id
        $auth_login = Auth::id();
        //Get doctors
        $assignedDoctors = Doctor::where('user_id', $auth_login)->orderBy('id', 'DESC')->where('status', 'active')->get();

        return view('manager.daily_reports.edit',compact('report_detail','assignedDoctors')); 
    }

    //Function for update daily report
    public function update(Request $request, $id) {
        //Update daily report
        $is_update_report = DailyReport::where('id', $id)->update([
            'report_date' => $request->report_date,
        ]);
        //Check if report Updated or not
        if($is_update_report) {
            //Delete old report
            DailyReportDetail::where('report_id', $id)->delete();
            //Create daily report detail
            foreach($request->doctor_id as $index => $doctor_id) {
                DailyReportDetail::create([
                    'report_id'  => $id,
                    'doctor_id' => $doctor_id,
                    'area_name' => $request->area_name[$index],
                    'total_visits' => $request->total_visits[$index],
                    'patients_referred' => $request->patients_referred[$index] ?? 0,
                    'notes' => $request->notes[$index] ?? null,
                ]);
            }
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
