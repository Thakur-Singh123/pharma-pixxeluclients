<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CounselorPatient;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CounsellorController extends Controller
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
    
    //Function for all patients
    public function index(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get auth login
        $manager = Auth::user();
        //Get manager counsellors 
        $counsellorIds = $manager->counsellors()->pluck('counsellor_id');
        //Get counsellors
        $query = CounselorPatient::with('counsellor')
            ->whereIn('counselor_id', $counsellorIds);
        //Filter
        if ($request->filled('counsellor_id')) {
            $query->where('counselor_id', $request->counsellor_id);
        }
        //Filter
        if ($request->filled('booking_done')) {
            $query->where('booking_done', $request->booking_done);
        }
        //query
        $patients = $query->orderBy('id', 'DESC')->paginate(10);
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Patients fetched successfully.',
            'data' => $patients
        ]);
    }
   
    //Function for update counsellor
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get counsellors
        $patient = CounselorPatient::find($id);
        //Check patient found or not
        if (!$patient) {
            return response()->json([
                'status' => 404,
                'message' => 'Patient not found.',
                'data' => null
            ], 404);
        }

        // Custom Validator for API
        $validator = Validator::make($request->all(), [
            'patient_name'   => 'required|string|max:255',
            'mobile_no'      => 'required|digits_between:8,15',
            'email'          => 'required|email',
            'department'     => 'required|string',
            'uhid_no'        => 'required|string|max:50',
            'booking_amount' => 'required|numeric|min:0',
            'remark'         => 'nullable|string',
        ]);
        //Validate input faileds
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => null
            ], 400);
        }
        //validated
        $validated = $validator->validated();
        //Department "Others"
        if ($request->department === 'Others' && !empty($request->other_department)) {
            $validated['department'] = 'Others (' . $request->other_department . ')';
        }
        //Update patient
        $patient->update($validated);
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Patient updated successfully.',
            'data' => $patient
        ]);
    }

    //Function for delete patient
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Find counselorPatient
        $patient = CounselorPatient::find($id);
        //Check patient found or not
        if (!$patient) {
            return response()->json([
                'status' => 404,
                'message' => 'Patient not found.',
                'data' => null
            ], 404);
        }
        //delete patient
        $patient->delete();
        //respnse
        return response()->json([
            'status' => 200,
            'message' => 'Patient deleted successfully.',
            'data' => null
        ]);
    }
}
