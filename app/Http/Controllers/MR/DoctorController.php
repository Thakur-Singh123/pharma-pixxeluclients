<?php
namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Models\DoctorMrAssignement;

class DoctorController extends Controller
{
    //fynction for view doctors
    public function index()
    {
        $mr              = auth()->user();
        $assignedDoctors = $mr->doctors()->paginate(10);
        return view('mr.doctors.index', compact('assignedDoctors'));
    }

    //function for create doctor
    public function submit_doctor(Request $request)
    {
        //Validate input fields
        $request->validate([
            'doctor_name'    => 'required|string',
            'doctor_contact' => 'required|string',
            'location'       => 'required|string',
            'remarks'        => 'required|string',
        ]);
        //Check if image is exit or not
        $filename = "";
        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename  = time() . '.' . $extension;
            $file->move(public_path('uploads/doctors'), $filename);
        }

        //get manager of this mr
        $mr = auth()->user();
        $manager = $mr->managers()->first();
        $manager_id = $manager->id;
        //Create doctor
        $is_create_doctor = Doctor::create([
            'user_id'        => $manager_id,
            'doctor_name'    => $request->doctor_name,
            'doctor_contact' => $request->doctor_contact,
            'location'       => $request->location,
            'remarks'        => $request->remarks,
            'picture'        => $filename,
            'status'         => 'pending',
        ]);
        //Check if doctor created or not
        if ($is_create_doctor) {
            //Assign MR to doctor
            DoctorMrAssignement::create([
                'doctor_id' => $is_create_doctor->id,
                'mr_id'     => auth()->id(),
            ]);
            return back()->with('success', 'Doctor created successfully.');
        } else {
            return back()->with('unsuccess', 'Opps something went wrong!');
        }
    }
}
