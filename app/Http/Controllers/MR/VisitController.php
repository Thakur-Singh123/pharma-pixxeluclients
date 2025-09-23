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
            'pin_code' => 'required|string',
            'visit_date' => 'required|string',
            'comments' => 'required|string',
            'status' => 'required|string',
            'visit_type' => 'required|in:doctor,bams_rmp_dental,asha_workers,health_workers,anganwadi,school,villages,city,societies,ngo,religious_places,other',
            'doctor_id' => 'required_if:visit_type,doctor',
            'religious_place_name' => 'required_if:visit_type,religious_places',
            'school_type' => 'required_if:visit_type,school',
            'villages' => 'required_if:visit_type,villages',
            'city' => 'required_if:visit_type,city',
            'societies' => 'required_if:visit_type,societies',
            'ngo' => 'required_if:visit_type,ngo',
            'other_visit_details' => 'required_if:visit_type,other',
        ], [
            'doctor_id.required_if' => 'The doctor name field is required.',
            'religious_place_name.required_if' => 'The religious place name field is required.',
            'school_type.required_if' => 'The school type field is required.',
            'villages.required_if' => 'The villages type field is required.',
            'city.required_if' => 'The city type field is required.',
            'societies.required_if' => 'The societies type field is required.',
            'ngo.required_if' => 'The ngo type field is required.',
            'other_visit_details.required_if'  => 'The other visit details field is required.',
        ]);
        //Create visit
        $is_create_visit = Visit::create([
            'area_name' => $request->area_name,
            'area_block' => $request->area_block,
            'district' => $request->district,
            'state' => $request->state,
            'pin_code' => $request->pin_code,
            'visit_date' => $request->visit_date,
            'comments' => $request->comments,
            'status' => 'Pending',
            'mr_id' => auth()->user()->id,
            'doctor_id' => $request->doctor_id ?? NULL,
            'visit_type' => $request->visit_type,
            'religious_place' => $request->religious_place_name ?? NULL,
            'school_type' => $request->school_type ?? NULL,
            'villages' => $request->villages ?? NULL,
            'city' => $request->city ?? NULL,
            'societies' => $request->societies ?? NULL,
            'ngo' => $request->ngo ?? NULL,
            'other_visit' => $request->other_visit_details ?? NULL,
        ]);
        //Check if visit created or not
        if ($is_create_visit) {
            return back()->with('success','Visit created successfully.');
        } else {
            return back()->with('unsuccess','Opps something went wrong!');
        }
    }

    //Function for all visits
    public function all_visits(Request $request) {
        $query = Visit::where('mr_id', auth()->id())->with('doctor');
        //Get search request for inputs
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('area_name', 'LIKE', "%$search%")
                ->orWhere('status', 'LIKE', "%$search%")
                ->orWhereHas('doctor', function($q2) use ($search) {
                    $q2->where('doctor_name', 'LIKE', "%$search%");
                });
            });
        }
        $all_visits = $query->orderBy('id','DESC')->paginate(5);

        return view('mr.visits.all-visits', compact('all_visits'));
    }

    //Function for filter visits
    public function visitFilter(Request $request) {
        //Get visit
        $query = Visit::where('mr_id', auth()->id())->with('doctor');
        //Filter by area_name
        if ($request->filled('area_name')) {
            $query->where('area_name', 'LIKE', '%' . $request->area_name . '%');
        }
        //Filter by status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        $all_visits = $query->orderBy('id', 'DESC')->paginate(5);
        
        return view('mr.visits.filter-visits', compact('all_visits'));
    }

    //Function for edit visit
    public function edit_visit($id) {
        //Get visit detail
        $visit_detail = Visit::with('mr','doctor')->find($id);
        $mr = auth()->user();
        $assignedDoctors = $mr->doctors()->where('status', 'active')->get();
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
            'pin_code' => 'required|string',
            'visit_date' => 'required|string',
            'comments' => 'required|string',
            'status' => 'required|string',
            'visit_type' => 'required|in:doctor,bams_rmp_dental,asha_workers,health_workers,anganwadi,school,villages,city,societies,ngo,religious_places,other',
            'doctor_id' => 'required_if:visit_type,doctor',
            'religious_place_name' => 'required_if:visit_type,religious_places',
            'school_type' => 'required_if:visit_type,school',
            'villages' => 'required_if:visit_type,villages',
            'city' => 'required_if:visit_type,city',
            'societies' => 'required_if:visit_type,societies',
            'ngo' => 'required_if:visit_type,ngo',
            'other_visit_details' => 'required_if:visit_type,other',
        ], [
            'doctor_id.required_if' => 'The doctor name field is required.',
            'religious_place_name.required_if' => 'The religious place name field is required.',
            'school_type.required_if' => 'The school type field is required.',
            'villages.required_if' => 'The villages type field is required.',
            'city.required_if' => 'The city type field is required.',
            'societies.required_if' => 'The societies type field is required.',
            'ngo.required_if' => 'The ngo type field is required.',
            'other_visit_details.required_if'  => 'The other visit details field is required.',
        ]);
        //Update visit
        $is_update_visit = Visit::where('id', $id)->update([
            'area_name' => $request->area_name,
            'area_block' => $request->area_block,
            'district' => $request->district,
            'state' => $request->state,
            'pin_code' => $request->pin_code,
            'visit_date' => $request->visit_date,
            'comments' => $request->comments,
            'status' => $request->status,
            'mr_id' => auth()->user()->id,
            'visit_type' => $request->visit_type,
            'doctor_id' => $request->visit_type == 'doctor' ? $request->doctor_id : NULL,
            'religious_place' => $request->visit_type == 'religious_places' ? $request->religious_place_name : NULL,
            'school_type' => $request->visit_type == 'school' ? $request->school_type : NULL,
            'villages' => $request->visit_type == 'villages' ? $request->villages : NULL,
            'city' => $request->visit_type == 'city' ? $request->city : NULL,
            'societies' => $request->visit_type == 'societies' ? $request->societies : NULL,
            'ngo' => $request->visit_type == 'ngo' ? $request->ngo : NULL,
            'other_visit' => $request->visit_type == 'other' ? $request->other_visit_details : NULL,
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
