<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    //Function for add doctor
    public function add_doctor() {
        return view('mr.doctors.add-new-doctor');
    }

    //Function for submit doctor
    public function submit_doctor(Request $request) {
        //Validate input fields
        $request->validate([
            'area_name' =>'required|string',
            'area_block' =>'required|string',
            'district' =>'required|string',
            'state' =>'required|string',
            'area_code' =>'required|string',
            'doctor_name' =>'required|string',
            'doctor_contact' =>'required|string',
            'location' =>'required|string',
            'remarks' =>'required|string',
        ]);
        //Check if image is exit or not
        $filename = "";
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/doctors'), $filename);
        }
        //Create doctor
        $is_create_doctor = Doctor::create([
            'area_name' => $request->area_name,
            'area_block' => $request->area_block,
            'district' => $request->district,
            'state' => $request->state,
            'area_code' => $request->area_code,
            'doctor_name' => $request->doctor_name,
            'doctor_contact' => $request->doctor_contact,
            'location' => $request->location,
            'remarks' => $request->remarks,
            'visit_type' =>$request->visit_type,
            'picture' => $filename,
        ]);
        //Check if doctor created or not
        if ($is_create_doctor) {
            return back()->with('success', 'Doctor created successfully.');
        } else {
            return back()->with('unsuccess', 'Opps something went wrong!');
        }
    }

    //Function for all doctors
    public function all_doctors() {
        //Get doctors
        $all_doctors = Doctor::OrderBy('ID','DESC')->paginate(10);
        return view('mr.doctors.all-doctors', compact('all_doctors'));
    }

    //Function for edit doctor
    public function edit_doctor($id) {
        //Get doctor detail
        $doctor_detail = Doctor::find($id);
        return view('mr.doctors.edit-doctor', compact('doctor_detail'));
    }

    //Function for update doctor
    public function update_doctor(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'area_name' =>'required|string',
            'area_block' =>'required|string',
            'district' =>'required|string',
            'state' =>'required|string',
            'area_code' =>'required|string',
            'doctor_name' =>'required|string',
            'doctor_contact' =>'required|string',
            'location' =>'required|string',
            'remarks' =>'required|string',
        ]);
        //Check if image is exit or not
        $filename = "";
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/doctors'), $filename);
            //update doctor with image
            $is_updated_doctor = Doctor::where('id', $id)->update([
                'area_name' => $request->area_name,
                'area_block' => $request->area_block,
                'district' => $request->district,
                'state' => $request->state,
                'area_code' => $request->area_code,
                'doctor_name' => $request->doctor_name,
                'doctor_contact' => $request->doctor_contact,
                'location' => $request->location,
                'remarks' => $request->remarks,
                'visit_type' =>$request->visit_type,
                'picture' => $filename,
            ]);
            //Check if doctor updated or not
            if ($is_updated_doctor) {
                return back()->with('success', 'Doctor updated successfully.');
            } else {
                return back()->with('unsuccess', 'Opps something went wrong!');
            }
        } else {
            //update doctor without image
            $is_updated_doctor = Doctor::where('id', $id)->update([
                'area_name' => $request->area_name,
                'area_block' => $request->area_block,
                'district' => $request->district,
                'state' => $request->state,
                'area_code' => $request->area_code,
                'doctor_name' => $request->doctor_name,
                'doctor_contact' => $request->doctor_contact,
                'location' => $request->location,
                'remarks' => $request->remarks,
                'visit_type' =>$request->visit_type,
            ]);
            //Check if doctor updated or not
            if ($is_updated_doctor) {
                return back()->with('success', 'Doctor updated successfully.');
            } else {
                return back()->with('unsuccess', 'Opps something went wrong!');
            }
        }
    }

    //Function for delete doctor
    public function delete_doctor($id) {
        //Delete doctor
        $is_doctor_delete = Doctor::where('id', $id)->delete();
        //Check if doctor updated or not
        if ($is_doctor_delete) {
            return back()->with('success', 'Doctor deleted successfully.');
        } else {
            return back()->with('unsuccess', 'Opps something went wrong!');
        }
    }
}
