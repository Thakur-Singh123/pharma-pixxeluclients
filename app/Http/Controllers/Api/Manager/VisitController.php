<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Doctor;
use App\Models\DoctorMrAssignement;
use App\Models\Visit;
use Auth;

class VisitController extends Controller
{
    // =====================================================
    // 1️⃣ Get All Visits (Listing + Search)
    // =====================================================
    public function index(Request $request)
    {
        $mrs = auth()->user()->mrs->pluck('id');

        $query = Visit::whereIn('mr_id', $mrs)
            ->with('mr', 'doctor')
            ->orderBy('id', 'DESC');

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

        $visits = $query->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Visits fetched successfully',
            'data' => $visits
        ]);
    }

    // =====================================================
    // 3️⃣ Approve Visit
    // =====================================================
    public function approve($id)
    {
        $visit = Visit::find($id);

        if (!$visit) {
            return response()->json([
                'status' => false, 'message' => 'Visit not found'
            ], 404);
        }

        
        if ($visit->status == 'Approved') {
            return response()->json([
                'status' => false,
                'message' => 'This visit is already approved. Reject it first to approve again.'
            ], 400);
        }

        $visit->status = 'Approved';
        $visit->save();

        return response()->json([
            'status' => true,
            'message' => 'Visit approved successfully'
        ]);
    }

    // =====================================================
    // 4️⃣ Reject Visit
    // =====================================================
    public function reject($id)
    {
        $visit = Visit::find($id);

        if (!$visit) {
            return response()->json([
                'status' => false, 
                'message' => 'Visit not found'
            ], 404);
        }

        if ($visit->status == 'Reject') {
            return response()->json([
                'status' => false,
                'message' => 'This visit is already rejected. Approve it first to reject again.'
            ], 400);
        }

        $visit->status = 'Reject';
        $visit->save();

        return response()->json([
            'status' => true,
            'message' => 'Visit rejected successfully'
        ]);
    }

    // =====================================================
    // 5️⃣ Update Visit
    // =====================================================
    public function update(Request $request, $id)
    {
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

        $visit = Visit::find($id);

        if (!$visit) {
            return response()->json([
                'status' => false,
                'message' => 'Visit not found'
            ], 404);
        }

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

        return response()->json([
            'status' => true,
            'message' => 'Visit updated successfully',
            'data' => $visit
        ]);
    }

    // =====================================================
    // 6️⃣ Delete Visit
    // =====================================================
    public function delete($id)
    {
        $visit = Visit::find($id);

        if (!$visit) {
            return response()->json([
                'status' => false,
                'message' => 'Visit not found'
            ], 404);
        }

        $visit->delete();

        return response()->json([
            'status' => true,
            'message' => 'Visit deleted successfully'
        ]);
    }
}
