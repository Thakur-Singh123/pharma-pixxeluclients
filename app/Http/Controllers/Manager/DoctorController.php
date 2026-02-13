<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Models\DoctorMrAssignement;

class DoctorController extends Controller
{
    //Function for add doctor
    public function add_doctor() {
        //Get auth login user
        $current_user = User::find(auth()->id());
        //Get all MRs
        $mrs = $current_user->mrs;
        //Check if current user is manager
        return view('manager.doctors.add-new-doctor', compact('mrs'));
    }

    //Function for submit doctor
    public function submit_doctor(Request $request) {
        //Validate input fields
        $request->validate([
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
            'created_by' => 'manager',
            'picture' => $filename,
            'status' => 'Active',
            'approval_status' => 'Approved',
        ]);
        //Check if doctor created or not
        if ($is_create_doctor) {
            //Assign MR to doctor
            if ($request->mr_id) {
                foreach ($request->mr_id as $mr_id) {
                   DoctorMrAssignement::create([
                        'doctor_id' => $is_create_doctor->id,
                        'mr_id' => $mr_id,
                    ]);
                }
            }
            return redirect()->route('manager.doctors')->with('success', 'Doctor created successfully.');
        } else {
            return back()->with('unsuccess', 'Opps something went wrong!');
        }
    }

    //Function for all doctors
    public function all_doctors() {
        //Filter
        $query = Doctor::where('user_id', auth()->id());
        if(request()->filled('created_by')) {
            $query = $query->where('created_by', request('created_by'));
        }
        if(request()->filled('created_date')) {
            $query = $query->whereDate('created_at', request('created_date'));
        }
        //Get doctors
        $all_doctors = $query->OrderBy('ID', 'DESC')->where('approval_status', 'Approved')->paginate(5);
        
        return view('manager.doctors.all-doctors', compact('all_doctors'));
    }

    //Function for edit doctor
    public function edit_doctor($id) {
        $current_user = User::find(auth()->id());
        $mrs = $current_user->mrs;
        //Get doctor detail
        $doctor_detail = Doctor::where('user_id',auth()->id())->find($id);
        $assignedMrsIds = $doctor_detail->mr->pluck('id')->toArray();
        return view('manager.doctors.edit-doctor', compact('doctor_detail','mrs','assignedMrsIds'));
    }

    //Function for approve doctor record
    public function approve($id) {
        //Get doctor
        $doctor_record = Doctor::findOrFail($id);
        //update doctor
        $doctor_record->approval_status = 'Approved'; 
        $doctor_record->status = 'Active'; 
        $doctor_record->save();
        return redirect()->route('manager.doctors')->with('success', 'Doctor approved successfully.');
    }

    //Function for reject doctor record
    public function reject($id) {
        //Get doctor
        $doctor_record = Doctor::findOrFail($id);
        //update doctor
        $doctor_record->approval_status = 'Reject'; 
        $doctor_record->save();
        return back()->with('success', 'Doctor reject successfully.');
    }

    //Function for update doctor status
    public function update_doctor_status(Request $request, $id) {
        //Get doctor detail
        Doctor::where('id', $id)->update([
            'status' => $request->status,
        ]);
        return back()->with('success', 'Doctor status updated successfully');
    }

    //Function for update doctor
    public function update_doctor(Request $request, $id) {
        //Validate input fields
        $request->validate([
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
            ]);
            //Check if doctor updated or not
            if ($is_updated_doctor) {
                return redirect()->route('manager.doctors')->with('success', 'Doctor updated successfully.');
            } else {
                return back()->with('unsuccess', 'Opps something went wrong!');
            }
        } else {
            //update doctor without image
            $is_updated_doctor = Doctor::where('id', $id)->update([
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
            ]);
            //Check if doctor updated or not
            if ($is_updated_doctor) {
                //delete previous MR Assigne
                if($request->mr_id) {
                    DoctorMrAssignement::where('doctor_id', $id)->delete();
                    //Assign MR to doctor
                    foreach ($request->mr_id as $mr_id) {
                        DoctorMrAssignement::create([
                            'doctor_id' => $id,
                            'mr_id' => $mr_id,
                        ]);
                    }
                }
                return redirect()->route('manager.doctors')->with('success', 'Doctor updated successfully.');
            } else {
                return back()->with('unsuccess', 'Opps something went wrong!');
            }
        }
    }

    //Function for delete doctor
    public function delete_doctor($id) {
        //Delete doctor
        $is_doctor_delete = Doctor::where('id', $id)->delete();
        //Check if doctor deleted or not
        if ($is_doctor_delete) {
            //Delete doctor MR assignment
            DoctorMrAssignement::where('doctor_id', $id)->delete();
            return redirect()->route('manager.doctors')->with('success', 'Doctor deleted successfully.');
        } else {
            return back()->with('unsuccess', 'Opps something went wrong!');
        }
    }

    //Function for waiting for approval doctor mr
    public function waiting_for_approval() {
        //Query
        $query = Doctor::OrderBy('ID', 'Desc')->whereIn('approval_status', ['Pending','Reject']);
        //Filter by data
        if(request()->filled('created_date')) {
            $query = $query->whereDate('created_at', request('created_date'));
        }
        $all_pending_doctors = $query->paginate(5);
        
        return view('manager.doctors.waiting-for-approval', compact('all_pending_doctors'));
    }
}
