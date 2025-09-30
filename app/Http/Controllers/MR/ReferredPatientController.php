<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReferredPatient;
use App\Models\Doctor;
use App\Models\MangerMR;

class ReferredPatientController extends Controller
{
    //Function for show all patients
    public function index() {
        //Get login MR id
        $mr_id = auth()->id();
        //Get patients
        $all_patients = ReferredPatient::with('doctor_detail')->where('mr_id', $mr_id)->OrderBy('ID', 'DESC')->paginate(5); 
        return view('mr.referred-patients.all-patients', compact('all_patients'));
    }

    //Function for create patient
    public function create() {
        //Get login mr
        $mr = auth()->user();
        //Get doctors
        $all_doctors = $mr->doctors()->where('status', 'active')->get();
        return view('mr.referred-patients.add-new-patient', compact('all_doctors'));
    }

    //Function for store
    public function store(Request $request) {
        //Validate input fields
        $request->validate([
            'patient_name' => 'required',
            'contact_no' => 'required',
            'attachment' => 'required',
        ]);
        //Check if image is exit or not
        $filename = "";
        if($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/referred-patients'), $filename);
        }
        //Get current MR ID
        $mrId = auth()->id();
        //Get manager ID linked to this MR
        $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');
        //Create patient
        $is_create_patient = ReferredPatient::create([
            'mr_id' => $mrId,
            'manager_id' => $managerId,
            'patient_name' => $request->patient_name,
            'contact_no' => $request->contact_no,
            'address' => $request->address,
            'gender' => $request->gender,
            'emergency_contact' => $request->emergency_contact,
            'medical_history' => $request->medical_history,
            'referred_contact' => $request->referred_contact,
            'preferred_doctor' => $request->preferred_doctor,
            'place_referred' => $request->place_referred,
            'bill_amount' => $request->bill_amount,
            'status' => 'Pending',
            'attachment' => $filename,
        ]);
        //Check if patient created or not
        if ($is_create_patient) {
            return redirect()->route('mr.patients.index')->with('success', 'Referred patient created successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    //Function for edit
    public function edit($id) {
        //Get login mr
        $mr = auth()->user();
        //Get doctors
        $all_doctors = $mr->doctors()->where('status', 'active')->get();
        //Get patient detail
        $patient_detail = ReferredPatient::find($id);
        return view('mr.referred-patients.edit-patient', compact('all_doctors','patient_detail'));
    }

    //Function for update
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'patient_name' => 'required',
            'contact_no' => 'required',
        ]);
        //Check if image is exit or not
        $filename = "";
        if($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/referred-patients'), $filename);
            //Get current MR ID
            $mrId = auth()->id();
            //Get manager ID linked to this MR
            $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');
            //Update patient with image
            $is_update_patient = ReferredPatient::where('id', $id)->update([
                'mr_id' => $mrId,
                'manager_id' => $managerId,
                'doctor_id' => $request->doctor_id,
                'patient_name' => $request->patient_name,
                'contact_no' => $request->contact_no,
                'address' => $request->address,
                'gender' => $request->gender,
                'emergency_contact' => $request->emergency_contact,
                'medical_history' => $request->medical_history,
                'referred_contact' => $request->referred_contact,
                'preferred_doctor' => $request->preferred_doctor,
                'place_referred' => $request->place_referred,
                'bill_amount' => $request->bill_amount,
                'status' => 'Pending',
                'attachment' => $filename,
            ]);
            //Check if patient updated or not
            if ($is_update_patient) {
                return redirect()->route('mr.patients.index')->with('success', 'Referred patient updated successfully.');
            } else {
                return back()->with('error', 'Opps something went wrong!');
            }
        } else {
           //Get current MR ID
            $mrId = auth()->id();
            //Get manager ID linked to this MR
            $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');
            //Update patient without image
            $is_update_patient = ReferredPatient::where('id', $id)->update([
                'mr_id' => $mrId,
                'manager_id' => $managerId,
                'doctor_id' => $request->doctor_id,
                'patient_name' => $request->patient_name,
                'contact_no' => $request->contact_no,
                'address' => $request->address,
                'gender' => $request->gender,
                'emergency_contact' => $request->emergency_contact,
                'medical_history' => $request->medical_history,
                'referred_contact' => $request->referred_contact,
                'preferred_doctor' => $request->preferred_doctor,
                'place_referred' => $request->place_referred,
                'bill_amount' => $request->bill_amount,
                'status' => 'Pending',
            ]);
            //Check if patient updated or not
            if ($is_update_patient) {
                return redirect()->route('mr.patients.index')->with('success', 'Referred patient updated successfully.');
            } else {
                return back()->with('error', 'Opps something went wrong!');
            }
        }
    }

    //Function for delete
    public function destroy($id) {
        //Delete patient
        $is_delete_patient = ReferredPatient::where('id', $id)->delete();
        //Check if patient delete or not
        if ($is_delete_patient) {
            return redirect()->route('mr.patients.index')->with('success', 'Referred patient deleted successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }
}
