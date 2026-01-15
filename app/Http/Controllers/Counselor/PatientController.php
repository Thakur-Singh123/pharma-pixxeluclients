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
    public function index()
    {
        $patients = CounselorPatient::where('counselor_id', Auth::id())
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('counselor.patients.index', compact('patients'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'booking_done' => 'required|in:Yes,No',
        ]);

        $patient = CounselorPatient::where('counselor_id', Auth::id())->findOrFail($id);
        $patient->update(['booking_done' => $validated['booking_done']]);

        return back()->with('success', 'Booking status updated to ' . $validated['booking_done'] . '.');
    }

    public function create()
    {
        return view('counselor.patients.create');
    }

    public function store(Request $request)
    {
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
        
        //User mail
        Mail::to($patient->email)->send(new UserBookingMail($patient));
        //Admin mail
        Mail::to('kapoorthakur906@gmail.com')->send(new AdminBookingMail($patient));

        //WHATSAPP MESSAGE
        // if ($patient->booking_done === 'Yes') {

        //     $message = urlencode(
        //         "Hello {$patient->patient_name},\n\n" .
        //         "Your booking with *Ad People* has been successfully received ✅\n\n" .
        //         "Department: {$patient->department}\n" .
        //         "Booking Amount: ₹{$patient->booking_amount}\n\n" .
        //         "Our team will contact you shortly.\n\n" .
        //         "– Team Ad People"
        //     );

        //     $whatsappUrl = "https://wa.me/91{$patient->mobile_no}?text={$message}";

        //     return redirect()->away($whatsappUrl);
        // }

        return redirect()->route('counselor.bookings.index')->with('success', 'Patient added successfully.');
    }

    public function edit($id)
    {
        $patient = CounselorPatient::where('counselor_id', Auth::id())->findOrFail($id);
        return view('counselor.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $patient = CounselorPatient::where('counselor_id', Auth::id())->findOrFail($id);

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

    public function destroy($id)
    {
        $patient = CounselorPatient::where('counselor_id', Auth::id())->findOrFail($id);
        $patient->delete();

        return redirect()->route('counselor.bookings.index')
            ->with('success', 'Patient booking deleted successfully.');
    }
}
