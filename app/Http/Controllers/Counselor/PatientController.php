<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\CounselorPatient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminBookingMail;
use App\Mail\UserBookingMail;

class PatientController extends Controller
{
    //Fucntion for all patients
    public function index() {
        //Get patients
        $patients = CounselorPatient::where('counselor_id', Auth::id())
            ->orderBy('id', 'DESC')
            ->paginate(5);
        return view('counselor.patients.index', compact('patients'));
    }

    //Function for update status
    public function updateStatus(Request $request, $id) {
        //Validate input fields
        $validated = $request->validate([
            'booking_done' => 'required|in:yes,no,on_hold',
        ]);
        //Get patients
        $patient = CounselorPatient::where('counselor_id', Auth::id())->findOrFail($id);
        $oldBookingDone = $patient->booking_done; // Store old status for comparison
        $patient->update(['booking_done' => $validated['booking_done']]);
        $patient->refresh();
        //Send mail to admin       
         if ($validated['booking_done'] === 'yes' && $oldBookingDone !== 'yes') {
            // USER EMAIL
            Mail::to($patient->email)
                ->send(new UserBookingMail($patient));
            // ADMIN EMAIL
            Mail::to('kapoorthakur906@gmail.com')
                ->send(new AdminBookingMail($patient));
        }
        return back()->with('success', 'Booking status updated to ' . $validated['booking_done'] . '.');
    }

    //Function for create patient
    public function create() {
        return view('counselor.patients.create');
    }

    //Function for store patient
    public function store(Request $request) {
        //Validate input fields
        $validated = $request->validate([
            'patient_name'       => 'required|string|max:255',
            'mobile_no'          => 'required|digits_between:8,15',
            'email'              => 'required|email',
            'department'         => 'required|string',
            'uhid_no'            => 'nullable|string|max:50',
            'booking_amount'     => 'nullable|numeric|min:0',
            'booking_date'       => 'required|date',
            'estimated_amount'   => 'required|numeric|min:0',
            'booking_done'       => 'required|in:yes,no,on_hold',
            'other_department'   => 'nullable|string|max:255',
            'remark'             => 'nullable|string',
            'attachment'         => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Conditional Reason Required
        if (in_array($request->booking_done, ['No', 'On Hold'])) {
            $request->validate([
                'booking_reason' => 'required|string|max:1000'
            ]);
        }

        // Others Department
        if ($validated['department'] === 'Others' && !empty($validated['other_department'])) {
            $validated['department'] = 'Others (' . $validated['other_department'] . ')';
        }

        // Upload Attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            // Ensure folder exists
            $destinationPath = public_path('counsellar_patient_bill');
            // Create unique file name
            $fileName = time() . '_' . $file->getClientOriginalName();
            // Move file to public folder
            $file->move($destinationPath, $fileName);
            // Save path in database
            $validated['attachment'] = 'counsellar_patient_bill/' . $fileName;
        }


        $validated['booking_reason'] = $request->booking_reason ?? null;
        $validated['counselor_id'] = Auth::id();

        $patient =CounselorPatient::create($validated);
        
        //Send mail ONLY if booking is YES
        if ($patient->booking_done === 'yes') {
            Mail::to($patient->email)->send(new UserBookingMail($patient));
            //ADMIN EMAIL
            Mail::to('kapoorthakur906@gmail.com')->send(new AdminBookingMail($patient));
        }
        return redirect()->route('counselor.bookings.index')->with('success', 'Patient added successfully.');
    }

    //Function for edit patient
    public function edit($id) {
        //Get patient
        $patient = CounselorPatient::where('counselor_id', Auth::id())->findOrFail($id);
        return view('counselor.patients.edit', compact('patient'));
    }

    //Function for update patient
    public function update(Request $request, $id)
    {
        // Get patient (security check)
        $patient = CounselorPatient::where('counselor_id', Auth::id())->findOrFail($id);
        $oldBookingDone = $patient->booking_done; // Store old status for comparison
        // Validate
        $validated = $request->validate([
            'patient_name'       => 'required|string|max:255',
            'mobile_no'          => 'required|digits_between:8,15',
            'email'              => 'required|email',
            'department'         => 'required|string',
            'uhid_no'            => 'nullable|string|max:50',
            'booking_amount'     => 'nullable|numeric|min:0',
            //'booking_date'       => 'required|date',
            'estimated_amount'   => 'required|numeric|min:0',
            'booking_done'       => 'required|in:yes,no,on_hold',
            'other_department'   => 'nullable|string|max:255',
            'remark'             => 'nullable|string',
            'attachment'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Conditional Reason Required
        if (in_array($request->booking_done, ['yes','no','on_hold'])) {
            if (in_array($request->booking_done, ['no','on_hold'])) {
                $request->validate([
                    'booking_reason' => 'required|string|max:1000'
                ]);
            }
        }

        // Others Department
        if ($validated['department'] === 'Others' && !empty($validated['other_department'])) {
            $validated['department'] = 'Others (' . $validated['other_department'] . ')';
        }

        // Upload New Attachment (optional in update)
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($patient->attachment && file_exists(public_path($patient->attachment))) {
                unlink(public_path($patient->attachment));
            }
            $file = $request->file('attachment');
            $destinationPath = public_path('counsellar_patient_bill');
            // Create folder if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $validated['attachment'] = 'counsellar_patient_bill/' . $fileName;
        }

        // Save booking reason
        $validated['booking_reason'] = $request->booking_reason ?? null;

        // Update record
        $update = $patient->update($validated);
        //Send mail ONLY if booking is YES
        $patient->refresh();
        // Send mail ONLY if booking is YES
        if ($patient->booking_done === 'yes' && $oldBookingDone !== 'yes') {
            // USER EMAIL
            Mail::to($patient->email)
                ->send(new UserBookingMail($patient));
            // ADMIN EMAIL
            Mail::to('kapoorthakur906@gmail.com')
                ->send(new AdminBookingMail($patient));
        }

        return redirect()->route('counselor.bookings.index')
                        ->with('success', 'Patient booking updated successfully.');
    }

    //Function for destory patient
    public function destroy($id) {
        //Get patient
        $patient = CounselorPatient::where('counselor_id', Auth::id())->findOrFail($id);
        //Delete patent
        $patient->delete();
        return redirect()->route('counselor.bookings.index')
            ->with('success', 'Patient booking deleted successfully.');
    }
}
