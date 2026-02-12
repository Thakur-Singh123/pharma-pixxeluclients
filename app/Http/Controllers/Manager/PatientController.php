<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\MangerMR;
use App\Models\ReferredPatient;

class PatientController extends Controller
{
    //Function for show all patients
    public function index(Request $request) {
        //Get mrs
        $mrs = auth()->user()->mrs->pluck('id');
        //Query
        $query = ReferredPatient::whereIn('mr_id',$mrs)->with('mr_detail','doctor_detail')->OrderBy('ID','DESC');
        //MR Filter
        if ($request->filled('mr_id')) {
            $query->where('mr_id', $request->mr_id);
        }
        //Date Filter
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }
        //Get patient
        $all_patients = $query->paginate(5);
        
        return view('manager.patients.all-patients', compact('all_patients'));
    }

    //Function for approve patient record
    public function approve_patient($id) {
        //Get patient
        $patient_record = ReferredPatient::findOrFail($id);
        //update patient
        $patient_record->status = 'approved'; 
        $patient_record->save();
        return back()->with('success', 'Referred patient approved successfully.');
    }

    //Function for reject patient record
    public function reject_patient($id) {
        //Get patient
        $patient_record = ReferredPatient::findOrFail($id);
        //update patient
        $patient_record->status = 'rejected'; 
        $patient_record->save();
        return back()->with('success', 'Referred patient reject successfully.');
    }

    //Function for edit
    public function edit($id) {
        //Get login mr
        $mr = auth()->user();
        //Get doctors
        $all_doctors = $mr->doctors()->where('status', 'active')->get();
        //Get patient detail
        $patient_detail = ReferredPatient::find($id);
        return view('manager.patients.edit-patient', compact('all_doctors','patient_detail'));
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
            //Get patient detail
            $patient = ReferredPatient::findOrFail($id);
            //Get mr id
            $mrId = $patient->mr_id;
            //Get manager ID linked to that MR
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
                'attachment' => $filename,
            ]);
            //Check if patient updated or not
            if ($is_update_patient) {
                return redirect()->route('manager.patients.index')->with('success', 'Referred patient updated successfully.');
            } else {
                return back()->with('error', 'Opps something went wrong!');
            }
        } else {
            //Get patient detail
            $patient = ReferredPatient::findOrFail($id);
            //Get mr id
            $mrId = $patient->mr_id;
            //Get manager ID linked to that MR
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
            ]);
            //Check if patient updated or not
            if ($is_update_patient) {
                return redirect()->route('manager.patients.index')->with('success', 'Referred patient updated successfully.');
            } else {
                return back()->with('error', 'Opps something went wrong!');
            }
        }
    }

    //Function for delete patient
    public function destroy($id) {
        //Delete patient
        $is_delete_patient = ReferredPatient::where('id', $id)->delete();
        //Check if patient updated or not
        if ($is_delete_patient) {
            return redirect()->route('manager.patients.index')->with('success', 'Referred patient deleted successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }
}