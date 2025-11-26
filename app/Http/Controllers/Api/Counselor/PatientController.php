<?php

namespace App\Http\Controllers\Api\Counselor;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\CounselorPatient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    //Function Ensure the authenticated MR context exists
    private function ensureAuthenticated(): ?JsonResponse {
        //Get auth detail
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 401);
        }
        return null;
    }

    //Function for all patients
    public function index() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get patients
        $patients = CounselorPatient::where('counselor_id', Auth::id())
            ->orderBy('id', 'DESC')
            ->paginate(5);
        //Response
        return response()->json([
            'status' => true,
            'message' => 'Patients fetched successfully',
            'data' => $patients
        ], 200);
    }

    //Function for store patient
    public function store(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validate input fileds
        $validator = Validator::make($request->all(), [
            'patient_name' => 'required|string|max:255',
            'mobile_no' => 'required|digits_between:8,15',
            'email' => 'required|email',
            'department' => 'required|string',
            'uhid_no' => 'nullable|string|max:50',
            'booking_amount' => 'nullable|numeric|min:0',
            'booking_done' => 'required|in:Yes,No',
            'other_department' => 'nullable|string|max:255',
            'remark' => 'nullable|string',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //Validated
        $data = $validator->validated();
        //Check if department
        if ($data['department'] === 'Others' && !empty($data['other_department'])) {
            $data['department'] = 'Others (' . $data['other_department'] . ')';
        }
        //Get auth detail
        $data['counselor_id'] = Auth::id();
        //Create patient
        $patient = CounselorPatient::create($data);
        //Create
        return response()->json([
            'status' => true,
            'message' => 'Patient created successfully.',
            'data' => $patient
        ], 200);
    }

    //Function for update booking status
    public function updateStatus(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validate input fields
        $validator = Validator::make($request->all(), [
            'booking_done' => 'required|in:Yes,No',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //Get patient
        $patient = CounselorPatient::where('counselor_id', Auth::id())->find($id);
        //Check patient found or not
        if (!$patient) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found'
            ], 404);
        }
        //Update patient booking
        $patient->update([
            'booking_done' => $request->booking_done,
        ]);
        //Response
        return response()->json([
            'status' => true,
            'message' => 'Booking status updated successfully',
            'data' => Null
        ], 200);
    }

    //Function for update patient
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get patient
        $patient = CounselorPatient::where('counselor_id', Auth::id())->find($id);
        //Check patient found or not
        if (!$patient) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found'
            ], 404);
        }
        //Validate input fields
        $validator = Validator::make($request->all(), [
            'patient_name'     => 'required|string|max:255',
            'mobile_no'        => 'required|digits_between:8,15',
            'email'            => 'required|email',
            'department'       => 'required|string',
            'uhid_no'          => 'nullable|string|max:50',
            'booking_amount'   => 'nullable|numeric|min:0',
            'other_department' => 'nullable|string|max:255',
            'remark'           => 'nullable|string',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //Validated
        $data = $validator->validated();
        //Department
        if ($data['department'] === 'Others' && !empty($data['other_department'])) {
            $data['department'] = 'Others (' . $data['other_department'] . ')';
        }
        //Update patient
        $patient->update($data);
        //Response
        return response()->json([
            'status' => true,
            'message' => 'Patient updated successfully',
            'data' => $patient
        ], 200);
    }

    //Function for delete patient
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get patient detail
        $patient = CounselorPatient::where('counselor_id', Auth::id())->find($id);
        //Check patient found or not
        if (!$patient) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found'
            ], 404);
        }
        //Delete patient
        $patient->delete();
        //Response
        return response()->json([
            'status' => true,
            'message' => 'Patient deleted successfully'
        ], 200);
    }
}
