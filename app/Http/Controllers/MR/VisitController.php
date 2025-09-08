<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visit;

class VisitController extends Controller
{
    //Function for add visit
    public function add_visit() {
        $mr = auth()->user();
        $assignedDoctors = $mr->doctors()->where('status', 'active')->get();
        return view('mr.visits.add-visit',compact('assignedDoctors'));
    }

    //Function for submit visit
    public function submit_visit(Request $request) {
        //Validate input fields
        $request->validate([
            'area_name' => 'required|string',
            'area_block' => 'required|string',
            'district' => 'required|string',
            'state' => 'required|string',
            'area_code' => 'required|string',
            'status' => 'required|string',
        ]);
        //Create visit
        $is_create_visit = Visit::create([
            'area_name' => $request->area_name,
            'area_block' => $request->area_block,
            'district' => $request->district,
            'state' => $request->state,
            'area_code' => $request->area_code,
            'status' => 'Pending',
            'mr_id' => auth()->user()->id,
            'doctor_id' => $request->doctor_id ?? NULL,
            'visit_type' => $request->visit_type,
            'religious_type' => $request->religious_type ?? NULL,
        ]);
        //Check if visit created or not
        if ($is_create_visit) {
            return back()->with('success','Visit created successfully.');
        } else {
            return back()->with('unsuccess','Opps something went wrong!');
        }
    }

    //Function for all visits
    public function all_visits() {
        //Get visits
        $all_visits = Visit::OrderBy('ID','DESC')->paginate(10);
        return view('mr.visits.all-visits', compact('all_visits'));
    }

    //Function for edit visit
    public function edit_visit($id) {
        //Get visit detail
        $visit_detail = Visit::with('mr','doctor')->find($id);
        $mr = auth()->user();
        $assignedDoctors = $mr->doctors()->where('status', 'active')->get();
       //echo "<pre>";print_r($assignedDoctors->toArray());exit;
        return view('mr.visits.edit-visit', compact('visit_detail','assignedDoctors'));
    }

    //Function for update visit
    public function update_visit(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'area_name' => 'required|string',
            'area_block' => 'required|string',
            'district' => 'required|string',
            'state' => 'required|string',
            'area_code' => 'required|string',
            'status' => 'required|string',
        ]);
        //Update visit
        $is_update_visit = Visit::where('id', $id)->update([
            'area_name' => $request->area_name,
            'area_block' => $request->area_block,
            'district' => $request->district,
            'state' => $request->state,
            'area_code' => $request->area_code,
            'status' => $request->status,
            'mr_id' => auth()->user()->id,
            'doctor_id' => $request->doctor_id ?? NULL,
            'religious_type' => $request->religious_type ?? NULL,
            'visit_type' => $request->visit_type,
        ]);
        //Check if visit updated or not
        if ($is_update_visit) {
            return back()->with('success','Visit updated successfully.');
        } else {
            return back()->with('unsuccess','Opps something went wrong!');
        }
    }

    //Function for delete visit
    public function delete_visit($id) {
        //Delete visit
        $is_delete_visit = Visit::where('id', $id)->delete();
         //Check if visit updated or not
        if ($is_delete_visit) {
            return back()->with('success','Visit deleted successfully.');
        } else {
            return back()->with('unsuccess','Opps something went wrong!');
        }
    }
}
