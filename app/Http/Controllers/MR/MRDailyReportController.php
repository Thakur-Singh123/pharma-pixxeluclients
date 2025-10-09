<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MangerMR;
use App\Models\DailyReport;
use App\Models\DailyReportDetail;
use App\Models\Doctor;

class MRDailyReportController extends Controller
{
    //Function for all daily reports
    public function index() {
        //Get daily reports
        $reports = DailyReport::where('mr_id', auth()->id())->orderBy('ID','DESC')->paginate(10);
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
        //Get manager id
        $manager_id = MangerMR::where('mr_id', auth()->id())->value('manager_id');
        //Create daily report
        $is_create_report = DailyReport::create([
            'mr_id' => auth()->id(),
            'manager_id' => $manager_id,
            'report_date' => $request->report_date, 
            'staus' => 'Pending',
        ]);
        //Check if report created or not
        if($is_create_report) {
            //Get request for input fileds
            foreach($request->doctor_id as $index => $doctor_id) {
                //Create daily report detail
                DailyReportDetail::create([
                    'report_id' => $is_create_report->id,
                    'doctor_id' => $doctor_id,
                    'area_name' => $request->area_name[$index],
                    'total_visits' => $request->total_visits[$index],
                    'patients_referred'=> $request->patients_referred[$index],
                    'notes' => $request->notes[$index],
                ]);
            }
            return redirect()->route('mr.daily-reports.index')->with('success','Daily report created successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to created Daily report!');
        }
    }

    //Function for edit daily report
    public function edit($id) {
        //Get report detail
        $report_detail = DailyReport::with('report_details')->find($id);
       //Get doctors
        $mr = auth()->user();
        $assignedDoctors = $mr->doctors()->where('status', 'active')->get();
        return view('mr.daily_reports.edit-daily-report',compact('report_detail','assignedDoctors')); 
    }

    //Function for update daily report
    public function update(Request $request, $id) {
        //Update daily report
        $is_update_report = DailyReport::where('id', $id)->update([
            'mr_id' => auth()->id(),
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
            return redirect()->route('mr.daily-reports.index')->with('success','Daily report updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to updated Daily report!');
        }
    }

    //Function for delete daily report
    public function destroy($id) {
        //Delete report
        $is_delete_report = DailyReport::where('id',$id)->delete();
        //Check if daily report deleted or not
        if ($is_delete_report) {
            DailyReportDetail::where('report_id', $id)->delete();
            return redirect()->route('mr.daily-reports.index')->with('success','Daily report deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to deleted Daily report!');
        }
    }
}
