<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CounselorPatient;
use App\Models\CounsellorPatientComment;
use App\Models\User;

class CounsellorController extends Controller
{
    //Function for all patients
    public function all_cpatients(Request $request) {
        //Get auth detail
        $manager = Auth::user();
        $counsellorIds = $manager->counsellors()->pluck('counsellor_id');
        //Query
        $query = CounselorPatient::with(['counsellor', 'comments'])
            ->whereIn('counselor_id', $counsellorIds);
        //Counsellor filter
        if ($request->filled('counsellor_id')) {
            $query->where('counselor_id', $request->counsellor_id);
        }
        //Booking done yes/no filter
        if ($request->filled('booking_done')) {
            $query->where('booking_done', $request->booking_done);
        }
        //Get patients
        $patients = $query->orderBy('id', 'DESC')->paginate(5);
        //All counsellors
        $counsellors = $manager->counsellors()->get();

        return view('manager.counsellor-patients.all-patients', compact('patients', 'counsellors'));
    }

    //Function for edit patient
    public function edit_booking($id) {
        //Get auth detail
        $manager = Auth::user();
        //Get counsellors
        $counsellorIds = $manager->counsellors()->pluck('counsellor_id');
        //Get patient detail
        $patient = CounselorPatient::whereIn('counselor_id', $counsellorIds)->findOrFail($id);
        
        return view('manager.counsellor-patients.edit', compact('patient'));
    }

    //Function for update booking
    public function update_booking(Request $request, $id) {
        //Get id
        $patient = CounselorPatient::findOrFail($id);
        //Validate input fileds
        $validated = $request->validate([
            'patient_name'     => 'required|string|max:255',
            'mobile_no'        => 'required|digits_between:8,15',
            'email'            => 'required|email',
            'department'       => 'required|string',
            'uhid_no'          => 'nullable|string|max:50',
            'booking_amount'   => 'nullable|numeric|min:0',
            'remark'           => 'nullable|string',
        ]);
        //Other department
        if ($request->department === 'Others' && !empty($request->other_department)) {
            $validated['department'] = 'Others (' . $request->other_department . ')';
        }
        //Update patient record
        $patient->update($validated);

        //Redirect back with success message
        return redirect()->route('manager.all.cpatients')->with('success', 'Patient updated successfully.');
    }
    
    //Function for delete booking 
    public function delete_booking($id) {
        //Delete booking
        $patient = CounselorPatient::where('id', $id)->delete();
        //Success msg
        return redirect()->route('manager.all.cpatients')->with('success', 'Patient booking deleted successfully.');
    }

    //Function for add comment (follow-up / reason for hold or no booking)
    public function add_comment(Request $request) {
        $request->validate([
            'counselor_patient_id' => 'required|exists:counselor_patients,id',
            'comment' => 'required|string|max:2000',
        ]);
        $manager = Auth::user();
        $counsellorIds = $manager->counsellors()->pluck('counsellor_id');
        $patient = CounselorPatient::whereIn('counselor_id', $counsellorIds)->findOrFail($request->counselor_patient_id);
        CounsellorPatientComment::create([
            'counselor_patient_id' => $patient->id,
            'user_id' => $manager->id,
            'role' => 'manager',
            'comment' => $request->comment,
        ]);
        return redirect()->back()->with('success', 'Comment added successfully.');
    }
}
