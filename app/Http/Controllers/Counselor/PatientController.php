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
            'booking_done' => 'required|in:Yes,No',
        ]);
        //Get patients
        $patient = CounselorPatient::where('counselor_id', Auth::id())->findOrFail($id);
        $patient->update(['booking_done' => $validated['booking_done']]);

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
            'patient_name'     => 'required|string|max:255',
            'mobile_no'        => 'required|digits_between:8,15',
            'email'            => 'required|email',
            'department'       => 'required|string',
            'uhid_no'          => 'nullable|string|max:50',
            'booking_amount'   => 'nullable|numeric|min:0',
            'booking_done'     => 'required|in:Yes,No',
            'other_department' => 'nullable|string|max:255',
            'remark' => 'nullable|string',
        ]);

        if ($validated['department'] === 'Others' && !empty($validated['other_department'])) {
            $validated['department'] = 'Others (' . $validated['other_department'] . ')';
        }

        $validated['counselor_id'] = Auth::id();

        $patient = CounselorPatient::create($validated);
        
        //Send mail ONLY if booking is YES
        if ($patient->booking_done === 'Yes') {
            // $adminEmail = 'kapoorthakur906@gmail.com';
            // $adminWhatsapp = '9418496408';
            //USER EMAIL
            Mail::to($patient->email)->send(new UserBookingMail($patient));
            //ADMIN EMAIL
            Mail::to('kapoorthakur906@gmail.com')->send(new AdminBookingMail($patient));
            //ADMIN WHATSAPP MESSAGE
            // $adminMessage = urlencode(
            //     "*New Booking Received*\n\n" .
            //     "Patient: {$patient->patient_name}\n" .
            //     "Mobile: {$patient->mobile_no}\n" .
            //     "Department: {$patient->department}\n" .
            //     "Amount: ₹{$patient->booking_amount}\n\n" .
            //     "– Ad People Panel"
            // );
            //USER WHATSAPP MESSAGE
            // $userMessage = urlencode(
            //     "Hello {$patient->patient_name},\n\n" .
            //     "Your booking with *Ad People* has been confirmed \n\n" .
            //     "Department: {$patient->department}\n" .
            //     "Booking Amount: ₹{$patient->booking_amount}\n\n" .
            //     "Our team will contact you shortly.\n\n" .
            //     "– Team Ad People"
            // );
            // session()->flash(
            //     'user_whatsapp_link',
            //     "https://wa.me/91{$patient->mobile_no}?text={$userMessage}"
            // );
            // return redirect()->away(
            //     "https://wa.me/91{$adminWhatsapp}?text={$adminMessage}"
            // );
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
    public function update(Request $request, $id) {
        //Get patient
        $patient = CounselorPatient::where('counselor_id', Auth::id())->findOrFail($id);
        //Validate input fields
        $validated = $request->validate([
            'patient_name'     => 'required|string|max:255',
            'mobile_no'        => 'required|digits_between:8,15',
            'email'            => 'required|email',
            'department'       => 'required|string',
            'uhid_no'          => 'nullable|string|max:50',
            'booking_amount'   => 'nullable|numeric|min:0',
            //'booking_done'     => 'required|in:Yes,No',
            'other_department' => 'nullable|string|max:255',
            'remark' => 'nullable|string',
        ]);

        if ($validated['department'] === 'Others' && !empty($validated['other_department'])) {
            $validated['department'] = 'Others (' . $validated['other_department'] . ')';
        }

        $patient->update($validated);

        return redirect()->route('counselor.bookings.index')->with('success', 'Patient updated successfully.');
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
