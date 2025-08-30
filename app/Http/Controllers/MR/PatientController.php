<?php
namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    //Function for show all patients
    public function index() {
        //Get patients
        $patients = Auth::user()->patients()->orderBy('id', 'DESC')->paginate(10);
        return view('mr.patients.all-patients', compact('patients'));
    }

    //Function for show patient
    public function create() {
        return view('mr.patients.add-new-patient');
    }

    //Function for submit patient
    public function store(Request $request) {
        //Validate inputs fields
        $request->validate([
            'name' =>'required',
            'age' =>'nullable|integer',
            'gender' =>'nullable|string',
            'disease' =>'nullable|string',
            'address' =>'nullable|string',
            'contact_number' =>'required|string',
        ]);
        //Create patient
        $is_create_patient = Patient::create([
            'mr_id' => auth()->id(),
            'name' => $request->name,
            'age' => $request->age,
            'gender' => $request->gender,
            'disease' => $request->disease,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
        ]);
        //Check if patient created or not
        if (!$is_create_patient) {
            return redirect()->back()->with('error', 'Failed to add patient.');
        }
        return redirect()->route('mr.patients.index')->with('success', 'Patient added successfully.');
    }

    //Function for edit patient
    public function edit($id) {
        //Get patient detail by ID
        $patient_detail = Patient::find($id);
        return view('mr.patients.edit-patient', compact('patient_detail'));
    }

    //Function for udpate patient
    public function update(Request $request, $id) {
        //Validate inputs fields
        $request->validate([
            'name' =>'required',
            'age' =>'nullable|integer',
            'gender' =>'nullable|string',
            'disease' =>'nullable|string',
            'address' =>'nullable|string',
            'contact_number' =>'required|string',
        ]);
        //Update patient
        $is_update_patient = Patient::where('id', $id)->update([ 
            'mr_id' => auth()->id(),
            'name' => $request->name,
            'age' => $request->age,
            'gender' => $request->gender,
            'disease' => $request->disease,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
        ]);
        //Check if patient updated or not
        if (!$is_update_patient) {
            return redirect()->back()->with('error', 'Failed to updated patient.');
        }
        return redirect()->route('mr.patients.index')->with('success', 'Patient updated successfully.');
    }

    //Function for delete patient
    public function destroy($id) {
        //Delete patient
        $is_delete_patient = Patient::where('id', $id)->delete();
        //Check if patient updated or not
        if (!$is_delete_patient) {
            return redirect()->back()->with('error', 'Failed to deleted patient.');
        }
        return redirect()->route('mr.patients.index')->with('success', 'Patient deleted successfully.');
    }
}
