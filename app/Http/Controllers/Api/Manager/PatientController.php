<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MangerMR;
use App\Models\ReferredPatient;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    //Function for authentication
    private function ensureAuthenticated(): ?JsonResponse {
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
        //Get all mrs
        $mrs = auth()->user()->mrs->pluck('id');
        //all patients
        $all_patients = ReferredPatient::whereIn('mr_id', $mrs)
            ->with('mr_detail', 'doctor_detail')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        //response
        return response()->json([
            'status' => true,
            'message' => 'Patients fetched successfully.',
            'data' => $all_patients
        ]);
    }

    //Function for approve patient
    public function approve_patient($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get patient
        $patient = ReferredPatient::find($id);
        //check patient not found
        if (!$patient) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found'
            ], 404);
        }
        //check already approved or not
        if ($patient->status === 'approved') {
            return response()->json([
                'status' => false,
                'message' => 'This patient is already approved. First reject then approve again.'
            ], 400);
        }
        //save status
        $patient->status = 'approved';
        $patient->save();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Patient approved successfully.'
        ]);
    }

    //Function for reject patient
    public function reject_patient($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get patient detail
        $patient = ReferredPatient::find($id);
        //check patient fonnd or not
        if (!$patient) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found'
            ], 404);
        }
        //check already rejected or not
        if ($patient->status === 'rejected') {
            return response()->json([
                'status' => false,
                'message' => 'This patient is already rejected. First approve then reject again.'
            ], 400);
        }
        //save status
        $patient->status = 'rejected';
        $patient->save();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Patient rejected successfully.'
        ]);
    }

    //Function for update patient
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validation input fields
        $validator = Validator::make($request->all(), [
            'patient_name' => 'required',
            'contact_no' => 'required',
        ]);
        //reponse
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }
        //get patient detail
        $patient = ReferredPatient::find($id);
        //check patient found or not
        if (!$patient) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found'
            ], 404);
        }
        //get mr
        $mrId = $patient->mr_id;
        $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');
        //file Upload
        $filename = $patient->attachment;
        //check file exists or not
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . "." . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/referred-patients'), $filename);
        }
        //Update data
        $patient->update([
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
            'attachment' => $filename
        ]);
        $patient->attachment = $patient->attachment
            ? asset('public/uploads/referred-patients/' . $patient->attachment)
            : null;
        return response()->json([
            'status' => true,
            'message' => 'Patient updated successfully.',
            'data' => $patient,
        ]);
    }

    //Function for delete patient
    public function destroy($id)  {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get patient detail
        $patient = ReferredPatient::find($id);
        //check patient found or not
        if (!$patient) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found'
            ], 404);
        }
        //delete patient
        $patient->delete();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Patient deleted successfully.'
        ]);
    }
}
