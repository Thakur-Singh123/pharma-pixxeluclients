<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Doctor;
use App\Models\DoctorMrAssignement;

class DoctorController extends Controller
{
    //Function for check auth exists or not
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

    //Function for submit doctor
    public function store(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validate input fields
        $validator = Validator::make($request->all(), [
            'doctor_name' => 'required|string',
            'doctor_contact' => 'required|string',
            'location' => 'required|string',
            'remarks' => 'required|string',
            'picture' => 'required'
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //Check if picture is exit or not
        $filename = "";
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $extension = $file->getClientOriginalExtension();
            $filename  = time() . '.' . $extension;
            $file->move(public_path('uploads/doctors'), $filename);
        }
        //Logged-in MR
        $mr = $request->user();
        //Get manager of MR
        $manager = $mr->managers()->first();
        if (!$manager) {
            return response()->json([
                'status' => false,
                'message' => 'Manager not found for this MR'
            ], 400);
        }
        //Create Doctor
        $doctor = Doctor::create([
            'user_id'          => $manager->id,
            'hospital_name'    => $request->hospital_name,
            'hospital_type'    => $request->hospital_type,
            'doctor_name'      => $request->doctor_name,
            'specialist'       => $request->specialist,
            'doctor_contact'   => $request->doctor_contact,
            'location'         => $request->location,
            'area_code'        => $request->area_code,
            'remarks'          => $request->remarks,
            'created_by'       => 'mr',
            'picture'          => $filename,
            'status'           => 'Pending',
            'approval_status'  => 'Pending',
        ]);
        $doctor->refresh();
        $doctor->picture = $doctor->picture
            ? asset('public/uploads/doctors/' . $doctor->picture)
            : null;
        //Assign MR to doctor
        DoctorMrAssignement::create([
            'mr_id'     => $mr->id,
            'doctor_id' => $doctor->id,
        ]);
        return response()->json([
            'status' => 200,
            'message' => 'Doctor created successfully.',
            'data' => $doctor
        ], 200);
    }
}

