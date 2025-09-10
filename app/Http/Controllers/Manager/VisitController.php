<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visit;

class VisitController extends Controller
{
    //Function for show all daily visits
    public function index() {
        //Get visits
        $mrs = auth()->user()->mrs->pluck('id');
        //Get visits
        $all_visits = Visit::whereIn('mr_id',$mrs)->with('mr')->OrderBy('ID','DESC')->paginate(5);
        return view('manager.daily-visits.all-visits', compact('all_visits'));
    }

    //Function for edit visit
    public function edit($id) {
        //Get visit detail
        $visit_detail = Visit::with('mr','doctor')->find($id);
        $mr = auth()->user();
        $assignedDoctors = $mr->doctors()->where('status', 'active')->get();
        return view('manager.daily-visits.edit-visit', compact('visit_detail','assignedDoctors'));
    }

    //Function for update visit
    public function update(Request $request, $id) {
        //echo "yes"; exit;
        //Validate input fields
        $request->validate([
            'area_name' => 'required|string',
            'area_block' => 'required|string',
            'district' => 'required|string',
            'state' => 'required|string',
            'area_code' => 'required|string',
            'status' => 'required|string',
        //     'visit_type' => 'required|in:doctor,religious_places,other',
        //     'doctor_id' => 'required_if:visit_type,doctor',
        //     'religious_place_name' => 'required_if:visit_type,religious_places',
        //     'other_visit_details' => 'required_if:visit_type,other',
        // ], [
        //     'doctor_id.required_if' => 'The doctor name field is required.',
        //     'religious_place_name.required_if' => 'The religious place name field is required.',
        //     'other_visit_details.required_if'  => 'The other visit details field is required.',
        ]);
        //Update visit
        $is_update_visit = Visit::where('id', $id)->update([
            'area_name' => $request->area_name,
            'area_block' => $request->area_block,
            'district' => $request->district,
            'state' => $request->state,
            'area_code' => $request->area_code,
            'status' => $request->status,
            // 'visit_type' => $request->visit_type,
            // 'doctor_id' => $request->visit_type == 'doctor' ? $request->doctor_id : NULL,
            // 'religious_place' => $request->visit_type == 'religious_places' ? $request->religious_place_name : NULL,
            // 'other_visit' => $request->visit_type == 'other' ? $request->other_visit_details : NULL,
        ]);
        //Check if visit updated or not
        if ($is_update_visit) {
            return back()->with('success','Visit updated successfully.');
        } else {
            return back()->with('unsuccess','Opps something went wrong!');
        }
    }

    //Function for delete visit
    public function destroy($id) {
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
