<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MrDailyReport;
use App\Models\Doctor;

class MRDailyReportController extends Controller
{
    //Function for all daily reports
    public function index() {
        //Get daily reports
        $reports = MrDailyReport::with('doctor_detail')->where('mr_id', auth()->id())->orderBy('report_date', 'desc')->paginate(10);
        return view('mr.daily_reports.index',compact('reports')); 
    }

    //Function for create daily report
    public function create() {
        //Get doctors
        $mr = auth()->user();
        $assignedDoctors = $mr->doctors()->where('status', 'active')->get();
        return view('mr.daily_reports.create', compact('assignedDoctors'));
    }

    //Function for store daily report
    public function store(Request $request) {
        //Validate input fields
        $request->validate([
            'doctor_id' =>'required',
            'report_date' =>'required|date',
            'area_name' =>'required',
            'total_visits' =>'required|integer|min:0',
            'patients_referred' =>'required|integer|min:0',
            'notes' =>'nullable|string',
        ]);
        //Create daily report
        $is_create_report = MrDailyReport::create([
            'mr_id' => auth()->id(),
            'doctor_id' => $request->doctor_id, 
            'report_date' => $request->report_date, 
            'area_name' => $request->area_name, 
            'total_visits' => $request->total_visits,
            'patients_referred' => $request->patients_referred,
            'notes' => $request->notes,
        ]);
        //Check if report created or not
        if($is_create_report) {
            return redirect()->route('mr.daily-reports.index')->with('success','Daily report created successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to created Daily report!');
        }
    }

    //Function for edit daily report
    public function edit($id) {
        //Get report detail
        $report_detail = MrDailyReport::find($id);
       //Get doctors
        $mr = auth()->user();
        $assignedDoctors = $mr->doctors()->where('status', 'active')->get();
        return view('mr.daily_reports.edit-daily-report',compact('report_detail','assignedDoctors')); 
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
            'mr_id' => auth()->id(),
            'doctor_id' => $request->doctor_id, 
            'report_date' => $request->report_date, 
            'area_name' => $request->area_name,  
            'total_visits' => $request->total_visits,
            'patients_referred' => $request->patients_referred,
            'notes' => $request->notes,
        ]);
        //Check if report Updated or not
        if($is_update_report) {
            return redirect()->route('mr.daily-reports.index')->with('success','Daily report updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to updated Daily report!');
        }
    }

    //Function for delete daily report
    public function destroy($id) {
        //Delete report
        $is_delete_report = MrDailyReport::where('id',$id)->delete();
        //Check if daily report deleted or not
        if ($is_delete_report) {
            return redirect()->route('mr.daily-reports.index')->with('success','Daily report deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to deleted Daily report!');
        }
    }
}
