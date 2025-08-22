<?php
namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Auth::user()->patients()->paginate(10);
        return view('mr.patients.index', compact('patients'));
    }

    // Show create form
    public function create()
    {
        return view('mr.patients.create');
    }

    // Store patient
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required',
            'age'            => 'nullable|integer',
            'gender'         => 'nullable|string',
            'disease'        => 'nullable|string',
            'address'        => 'nullable|string',
            'contact_number' => 'required|string',
        ]);

        $create = Patient::create([
            'mr_id'          => auth()->id(),
            'name'           => $request->name,
            'age'            => $request->age,
            'gender'         => $request->gender,
            'disease'        => $request->disease,
            'address'        => $request->address,
            'contact_number' => $request->contact_number,
        ]);

        if (! $create) {
            return redirect()->back()->with('error', 'Failed to add patient.');
        }
        return redirect()->route('mr.patients.index')->with('success', 'Patient added successfully.');
    }
}
