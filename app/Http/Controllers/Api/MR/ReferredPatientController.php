<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use App\Models\MangerMR;
use App\Models\ReferredPatient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReferredPatientController extends Controller
{
    /**
     * Ensure the authenticated MR context exists.
     */
    private function ensureAuthenticated(): ?JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 401);
        }

        return null;
    }

    /**
     * Build attachment url when file is available.
     */
    private function buildAttachmentUrl(?string $filename): ?string
    {
        return $filename
            ? asset('public/uploads/referred-patients/' . ltrim($filename, '/'))
            : null;
    }

    /**
     * Append computed attributes on single patient model.
     */
    private function transformPatient(?ReferredPatient $patient): ?ReferredPatient
    {
        if ($patient) {
            $patient->attachment_url = $this->buildAttachmentUrl($patient->attachment);
        }

        return $patient;
    }

    /**
     * Append computed attributes for paginated result.
     */
    private function transformPaginator($paginator)
    {
        if ($paginator && method_exists($paginator, 'getCollection')) {
            $paginator->getCollection()->transform(function ($patient) {
                return $this->transformPatient($patient);
            });
        }

        return $paginator;
    }

    /**
     * List referred patients for authenticated MR.
     */
    public function index(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        // REMOVED per_page
        $patients = ReferredPatient::where('mr_id', auth()->id())
            ->orderByDesc('id')
            ->paginate(); // DEFAULT PAGINATION

        $this->transformPaginator($patients);

        $message = $patients->count()
            ? 'Referred patients fetched successfully.'
            : 'No referred patients found.';

        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $patients,
        ], 200);
    }

    /**
     * Store a newly referred patient.
     */
    public function store(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'patient_name' => 'required|string|max:255',
            'contact_no' => 'required|string|max:50',
            'attachment' => 'required|file',
            'doctor_id' => 'nullable|exists:doctors,id',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'emergency_contact' => 'nullable|string|max:50',
            'medical_history' => 'nullable|string',
            'referred_contact' => 'nullable|string|max:50',
            'preferred_doctor' => 'nullable|string|max:255',
            'place_referred' => 'nullable|string|max:255',
            'bill_amount' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 400);
        }

        $filename = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/referred-patients'), $filename);
        }

        $mrId = auth()->id();
        $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');

        $payload = [
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
        ];

        $patient = ReferredPatient::create($payload);
        $this->transformPatient($patient);

        return response()->json([
            'status' => 200,
            'message' => 'Referred patient created successfully.',
            'data' => $patient,
        ], 200);
    }

    /**
     * Update referred patient detail.
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'patient_name' => 'required|string|max:255',
            'contact_no' => 'required|string|max:50',
            'attachment' => 'nullable|file',
            'doctor_id' => 'nullable|exists:doctors,id',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'emergency_contact' => 'nullable|string|max:50',
            'medical_history' => 'nullable|string',
            'referred_contact' => 'nullable|string|max:50',
            'preferred_doctor' => 'nullable|string|max:255',
            'place_referred' => 'nullable|string|max:255',
            'bill_amount' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 400);
        }

        $patient = ReferredPatient::where('id', $id)
            ->where('mr_id', auth()->id())
            ->first();

        if (!$patient) {
            return response()->json([
                'status' => 404,
                'message' => 'Referred patient not found.',
                'data' => null,
            ], 404);
        }

        $filename = $patient->attachment;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/referred-patients'), $filename);
        }

        $mrId = auth()->id();
        $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');

        $payload = [
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
        ];

        $patient->update($payload);
        $this->transformPatient($patient);

        return response()->json([
            'status' => 200,
            'message' => 'Referred patient updated successfully.',
            'data' => $patient,
        ], 200);
    }

    /**
     * Remove the referred patient.
     */
    public function destroy($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $patient = ReferredPatient::where('id', $id)
            ->where('mr_id', auth()->id())
            ->first();

        if (!$patient) {
            return response()->json([
                'status' => 404,
                'message' => 'Referred patient not found.',
                'data' => null,
            ], 404);
        }

        $patient->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Referred patient deleted successfully.',
            'data' => null,
        ], 200);
    }
}
