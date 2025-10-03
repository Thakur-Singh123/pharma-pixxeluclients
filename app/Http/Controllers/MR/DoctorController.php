<?php
namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Models\DoctorMrAssignement;

class DoctorController extends Controller
{
    //Function for all doctors
    public function index() {
        //Get auth login
        $mr = auth()->user();
        //Get all doctors
        $assignedDoctors = $mr->doctors()->where('status', 'Active')->paginate(5);
        return view('mr.doctors.index', compact('assignedDoctors'));
    }

    //Function for create doctor
    public function submit_doctor(Request $request) {
        //Validate input fields
        $request->validate([
            'doctor_name'  => 'required|string',
            'doctor_contact' => 'required|string',
            'location' => 'required|string',
            'remarks'  => 'required|string',
        ]);
        //Check if image is exit or not
        $filename = "";
        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename  = time() . '.' . $extension;
            $file->move(public_path('uploads/doctors'), $filename);
        }
        //Get manager of this mr
        $mr = auth()->user();
        $manager = $mr->managers()->first();
        $manager_id = $manager->id;
        //Create doctor
        $is_create_doctor = Doctor::create([
            'user_id' => $manager_id,
            'hospital_name' => $request->hospital_name,
            'hospital_type' => $request->hospital_type,
            'doctor_name' => $request->doctor_name,
            'specialist' => $request->specialist,
            'doctor_contact' => $request->doctor_contact,
            'location' => $request->location, 
            'area_code' => $request->area_code, 
            'remarks' => $request->remarks,
            'created_by' => 'mr',
            'picture' => $filename,
            'status' => 'Pending',
            'approval_status' => 'Pending',
        ]);
        //Check if doctor created or not
        if ($is_create_doctor) {
            //Assign MR to doctor
            DoctorMrAssignement::create([
                'mr_id' => auth()->id(),
                'doctor_id' => $is_create_doctor->id,
            ]);
            return back()->with('success', 'Doctor created successfully.');
        } else {
            return back()->with('unsuccess', 'Opps something went wrong!');
        }
    }
}
