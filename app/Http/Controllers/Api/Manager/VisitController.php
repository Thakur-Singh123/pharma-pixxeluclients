<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User; 
use App\Models\Doctor;
use App\Models\DoctorMrAssignement;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VisitController extends Controller
{
    //Function for Ensure authenticated
    private function ensureAuthenticated() {
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => "Unauthorized access. Please login first.",
                'data' => null,
            ], 401);
        }

        return null;
    }
    
    //Function for all visits
    public function index(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //all mrs
        $mrs = auth()->user()->mrs->pluck('id');
        //query
        $query = Visit::whereIn('mr_id', $mrs)
            ->with('mr', 'doctor')
            ->orderBy('id', 'DESC');
        //filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('area_name', 'LIKE', "%$search%")
                    ->orWhere('area_block', 'LIKE', "%$search%")
                    ->orWhere('district', 'LIKE', "%$search%")
                    ->orWhere('state', 'LIKE', "%$search%")
                    ->orWhere('pin_code', 'LIKE', "%$search%")
                    ->orWhere('visit_date', 'LIKE', "%$search%")
                    ->orWhere('status', 'LIKE', "%$search%")
                    ->orWhereHas('doctor', function ($q2) use ($search) {
                        $q2->where('doctor_name', 'LIKE', "%$search%");
                    });
            });
        }
        //all visits
        $visits = $query->paginate(10);
        //response
        return response()->json([
            'status' => true,
            'message' => 'Visits fetched successfully',
            'data' => $visits
        ]);
    }
    
    //Function for approve visit
    public function approve($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //visit detail
        $visit = Visit::find($id);
        //check if visit not found
        if (!$visit) {
            return response()->json([
                'status' => false, 'message' => 'Visit not found'
            ], 404);
        }
        //check if status approved or not
        if ($visit->status == 'Approved') {
            return response()->json([
                'status' => false,
                'message' => 'This visit is already approved. Reject it first to approve again.'
            ], 400);
        }
        //save 
        $visit->status = 'Approved';
        $visit->save();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Visit approved successfully'
        ]);
    }
    
    //Function for reject visit
    public function reject($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //visit detail
        $visit = Visit::find($id);
        //check if visit found or not
        if (!$visit) {
            return response()->json([
                'status' => false, 
                'message' => 'Visit not found'
            ], 404);
        }
        //check if status reject or not
        if ($visit->status == 'Reject') {
            return response()->json([
                'status' => false,
                'message' => 'This visit is already rejected. Approve it first to reject again.'
            ], 400);
        }
        //save
        $visit->status = 'Reject';
        $visit->save();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Visit rejected successfully'
        ]);
    }

    //Function for update visit
    public function update(Request $request, $id) {
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
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //get visit detail
        $visit = Visit::find($id);
        //check if visit found r not
        if (!$visit) {
            return response()->json([
                'status' => false,
                'message' => 'Visit not found'
            ], 404);
        }
        //update visit
        $visit->update([
            'mr_id' => $request->mr_id,
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
            'status' => true,
            'message' => 'Visit updated successfully',
            'data' => $visit
        ]);
    }

    //Function for delete visit
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get visit detail
        $visit = Visit::find($id);
        //if check visit found or not
        if (!$visit) {
            return response()->json([
                'status' => false,
                'message' => 'Visit not found'
            ], 404);
        }
        //delete visit
        $visit->delete();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Visit deleted successfully'
        ]);
    }
}
