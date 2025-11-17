<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\Doctor;
use App\Models\DoctorMrAssignement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VisitController extends Controller
{
    //Function for ensure user is authenticated
    private function ensureAuthenticated() {
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null
            ], 401);
        }
        return null;
    }
    
    //Function for show all visits
    public function index() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //all visits
        $visits = Visit::where('mr_id', Auth::id())
            ->orderBy('id', 'DESC')
            ->paginate(10);
        //check
        if ($visits->total() == 0) {
            return response()->json([
                'status' => 200,
                'message' => 'No visits found.',
                'data' => null
            ]);
        }
        //response
        return response()->json([
            'status' => 200,
            'message' => 'Visits fetched successfully.',
            'data' => $visits
        ]);
    }

    //Function for create visit
    public function store(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validation inputs fields
        $validator = Validator::make($request->all(), [
            'area_name' => 'required|string',
            'area_block' => 'required|string',
            'district' => 'required|string',
            'state' => 'required|string',
            'pin_code' => 'required|string',
            'visit_date' => 'required|string',
            'comments' => 'required|string',
            'visit_type' =>'required|in:doctor,bams_rmp_dental,asha_workers,health_workers,anganwadi,school,villages,city,societies,ngo,religious_places,other',
            'doctor_id' => 'required_if:visit_type,doctor',
            'religious_place_name' => 'required_if:visit_type,religious_places',
            'school_type' => 'required_if:visit_type,school',
            'villages' => 'required_if:visit_type,villages',
            'city' => 'required_if:visit_type,city',
            'societies' => 'required_if:visit_type,societies',
            'ngo' => 'required_if:visit_type,ngo',
            'other_visit_details' => 'required_if:visit_type,other',
        ]);
        //Check
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }
        //Create visit
        $visit = Visit::create([
            'area_name' => $request->area_name,
            'area_block' => $request->area_block,
            'district' => $request->district,
            'state' => $request->state,
            'pin_code' => $request->pin_code,
            'visit_date' => $request->visit_date,
            'comments' => $request->comments,
            'status' => 'Pending',
            'mr_id' => Auth::id(),
            'doctor_id' => $request->doctor_id ?? null,
            'visit_type' => $request->visit_type,
            'religious_place' => $request->religious_place_name ?? null,
            'school_type' => $request->school_type ?? null,
            'villages' => $request->villages ?? null,
            'city' => $request->city ?? null,
            'societies' => $request->societies ?? null,
            'ngo' => $request->ngo ?? null,
            'other_visit' => $request->other_visit_details ?? null,
        ]);
        //respnse
        return response()->json([
            'status' => 200,
            'message' => 'Visit created successfully.',
            'data' => $visit
        ]);
    }
    
    //Function for update visit
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validate input fields
        $validator = Validator::make($request->all(), [
            'area_name' => 'required|string',
            'area_block' => 'required|string',
            'district' => 'required|string',
            'state' => 'required|string',
            'pin_code' => 'required|string',
            'visit_date' => 'required|string',
            'comments' => 'required|string',
            'visit_type' =>'required|in:doctor,bams_rmp_dental,asha_workers,health_workers,anganwadi,school,villages,city,societies,ngo,religious_places,other',
            'doctor_id' => 'required_if:visit_type,doctor',
            'religious_place_name' => 'required_if:visit_type,religious_places',
            'school_type' => 'required_if:visit_type,school',
            'villages' => 'required_if:visit_type,villages',
            'city' => 'required_if:visit_type,city',
            'societies' => 'required_if:visit_type,societies',
            'ngo' => 'required_if:visit_type,ngo',
            'other_visit_details' => 'required_if:visit_type,other',
        ]);
        //check
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }
        //Get visit
        $visit = Visit::find($id);
        //check
        if (!$visit) {
            return response()->json([
                'status' => 404,
                'message' => 'Visit not found.',
                'data' => null
            ]);
        }
        //update
        $visit->update([
            'area_name' => $request->area_name,
            'area_block' => $request->area_block,
            'district' => $request->district,
            'state' => $request->state,
            'pin_code' => $request->pin_code,
            'visit_date' => $request->visit_date,
            'comments' => $request->comments,
            'visit_type' => $request->visit_type,
            'doctor_id' => $request->visit_type == 'doctor' ? $request->doctor_id : null,
            'religious_place' => $request->visit_type == 'religious_places' ? $request->religious_place_name : null,
            'school_type' => $request->visit_type == 'school' ? $request->school_type : null,
            'villages' => $request->visit_type == 'villages' ? $request->villages : null,
            'city' => $request->visit_type == 'city' ? $request->city : null,
            'societies' => $request->visit_type == 'societies' ? $request->societies : null,
            'ngo' => $request->visit_type == 'ngo' ? $request->ngo : null,
            'other_visit' => $request->visit_type == 'other' ? $request->other_visit_details : null,
        ]);
        //response
        return response()->json([
            'status' => 200,
            'message' => 'Visit updated successfully.',
            'data' => $visit
        ]);
    }

    //Function for delete visit
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get visit
        $visit = Visit::find($id);
        //check
        if (!$visit) {
            return response()->json([
                'status' => 404,
                'message' => 'Visit not found.',
                'data' => null
            ]);
        }
        //delete visit
        $visit->delete();
        //response
        return response()->json([
            'status' => 200,
            'message' => 'Visit deleted successfully.',
            'data' => null
        ]);
    }
}
