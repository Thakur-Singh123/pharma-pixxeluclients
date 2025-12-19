<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Doctor;
use App\Models\User;
use App\Models\DoctorMrAssignement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{

    //Function Ensure the authenticated MR context exists
    private function ensureAuthenticated(): ?JsonResponse {
        //Check if auth login or not
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 401);
        }

        return null;
    }
    
    //Function for all doctors
    public function index() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //all doctors
        $doctors = Doctor::where('user_id', auth()->id())
            ->where('approval_status', 'Approved')
            ->orderBy('id', 'DESC')
            ->get();
            //Get images
            foreach ($doctors as $doc) {
                $doc->image_url = $doc->picture ? url('public/uploads/doctors/'.$doc->picture) : null;
            }
        //response    
        return response()->json([
            'status' => true,
            'message' => 'Doctors fetched successfully.',
            'data' => $doctors
        ], 200);
    }

    //Function for add doctor
    public function store(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //validate inputs fields
        $validator = Validator::make($request->all(), [
            'hospital_name' =>'required|string',
            'hospital_type' =>'required|string',
            'area_name' =>'required|string',
            'area_block' =>'required|string',
            'district' =>'required|string',
            'state' =>'required|string',
            'area_code' =>'required|string',
            'doctor_name' =>'required|string',
            'speciality' =>'required',
            'doctor_contact' =>'required|string',
            'location' =>'required|string',
            'remarks' =>'required|string',
            'image' => 'nullable|image|max:2048'
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //Upload image
        $filename = "";
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/doctors'), $filename);
        }
        //Create doctor
        $doctor = Doctor::create([
            'user_id' => auth()->id(),
            'hospital_name' => $request->hospital_name,
            'hospital_type' => $request->hospital_type,
            'area_name' => $request->area_name,
            'area_block' => $request->area_block,
            'district' => $request->district,
            'state' => $request->state,
            'area_code' => $request->area_code,
            'doctor_name' => $request->doctor_name,
            'specialist' => $request->speciality,
            'doctor_contact' => $request->doctor_contact,
            'location' => $request->location,
            'remarks' => $request->remarks,
            'picture' => $filename,
            'created_by' => 'manager',
            'status' => 'Active',
            'approval_status' => 'Approved',
        ]);
        //Assign MR
        if ($request->mr_id) {
            foreach ($request->mr_id as $mr_id) {
                DoctorMrAssignement::create([
                    'doctor_id' => $doctor->id,
                    'mr_id' => $mr_id,
                ]);
            }
        }
        //image url
        $doctor->refresh();
        $doctor->picture = $doctor->picture
            ? asset('public/uploads/doctors/' . $doctor->picture)
            : null;

        //response
        return response()->json([
            'status' => true,
            'message' => 'Doctor created successfully.',
            'data' => $doctor,
        ], 200);
    }

    //Function for update doctor
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validate inputs fields
        $validator = Validator::make($request->all(), [
            'hospital_name' =>'required|string',
            'hospital_type' =>'required|string',
            'area_name' =>'required|string',
            'area_block' =>'required|string',
            'district' =>'required|string',
            'state' =>'required|string',
            'area_code' =>'required|string',
            'doctor_name' =>'required|string',
            'speciality' =>'required',
            'doctor_contact' =>'required|string',
            'location' =>'required|string',
            'remarks' =>'required|string',
            'image' => 'nullable|image|max:2048'
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //Get doctors
        $doctor = Doctor::find($id);
        //If doctor not found
        if (!$doctor) {
            return response()->json([
                'status' => false,
                'message' => 'Doctor not found'
            ], 404);
        }
        //Update picture
        $filename = $doctor->picture;
        //If image uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/doctors'), $filename);
        }
        //Update doctor
        $doctor->update([
            'hospital_name' => $request->hospital_name,
            'hospital_type' => $request->hospital_type,
            'area_name' => $request->area_name,
            'area_block' => $request->area_block,
            'district' => $request->district,
            'state' => $request->state,
            'area_code' => $request->area_code,
            'doctor_name' => $request->doctor_name,
            'specialist' => $request->speciality,
            'doctor_contact' => $request->doctor_contact,
            'location' => $request->location,
            'remarks' => $request->remarks,
            'picture' => $filename
        ]);
        //image url
        $doctor->picture = $doctor->picture
            ? asset('public/uploads/doctors/' . $doctor->picture)
            : null;
        //Update MR assignment
        DoctorMrAssignement::where('doctor_id', $id)->delete();
        //Create mr
        if ($request->mr_id) {
            foreach ($request->mr_id as $mr) {
                DoctorMrAssignement::create([
                    'doctor_id' => $id,
                    'mr_id' => $mr,
                ]);
            }
        }
        //respnse
        return response()->json([
            'status' => true,
            'message' => 'Doctor updated successfully.',
            'data' => $doctor,
        ], 200);
    }

    //Function for delete doctor
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get doctor detail
        $doctor = Doctor::find($id);
        //If doctor not found
        if (!$doctor) {
            return response()->json([
                'status' => false,
                'message' => 'Doctor not found'
            ], 404);
        }
        //Delete old data
        DoctorMrAssignement::where('doctor_id', $id)->delete();
        //delete doctor
        $doctor->delete();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Doctor deleted successfully.'
        ], 200);
    }

    //Function for doctor waiting for approval
    public function waitingForApproval(Request $request) {
        //Check auth exists or not
            if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get doctors
        $doctors = Doctor::orderBy('id', 'desc')
            ->whereIn('approval_status', ['Pending', 'Reject'])
            ->paginate(5);

        // Check if doctor exists or not
        if ($doctors->total() == 0) {
            return response()->json([
                'status' => 400,
                'message' => 'No doctors waiting for approval.',
                'data' => []
            ], 400);
        }
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Waiting for approval doctors fetched successfully.',
            'data' => $doctors
        ], 200);
    }

    //Function for approve doctor
    public function approveDoctor($id) {
        //Check auth exists or not
            if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get doctor
        $doctor = Doctor::find($id);
        //Check if doctor fond or not
        if (!$doctor) {
            return response()->json([
                'status' => 404,
                'message' => 'Doctor not found.',
                'data' => null
            ], 404);
        }
        //Check if already approved 
        if ($doctor->approval_status === 'Approved') {
            return response()->json([
                'status' => 400,
                'message' => 'This doctor is already approved. Please reject first, then approve again.',
                'data' => null
            ], 400);
        }
        //Approve doctor
        $doctor->approval_status = 'Approved';
        $doctor->status = 'Active';
        $doctor->save();
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Doctor approved successfully.',
            'data' => $doctor
        ], 200);
    }

    //Function for reject doctor
    public function rejectDoctor($id) {
        //Check auth exists or not
            if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get doctor detail
        $doctor = Doctor::find($id);
        //Check if doctor found or not
        if (!$doctor) {
            return response()->json([
                'status' => 404,
                'message' => 'Doctor not found.',
                'data' => null
            ], 404);
        }
        //Check if already rejected
        if ($doctor->approval_status === 'Reject') {
            return response()->json([
                'status' => 400,
                'message' => 'This doctor is already rejected. Please approve first, then reject again.',
                'data' => null
            ], 400);
        }
        //Reject doctor
        $doctor->approval_status = 'Reject';
        $doctor->save();
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Doctor rejected successfully.',
            'data' => $doctor
        ], 200);
    }
}

