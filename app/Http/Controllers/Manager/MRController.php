<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MangerMR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MRController extends Controller
{
   
    public function index()
    {
        $manager = User::find(auth()->id());
        $mrs = $manager->mrs()->paginate(10);
        return view('manager.mr-management.index', compact('mrs'));
    }

    //function for showing the create MR form
    public function create()
    {
        return view('manager.mr-management.create');
    }

    //function for storing the MR data
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
            'phone'         => 'nullable|string|max:15',
            'employee_code' => 'nullable|string|unique:users,employee_code',
            'territory'     => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:255',
            'state'         => 'nullable|string|max:255',
            'joining_date'  => 'nullable|date',
            'status'        => 'required|in:Active,Pending',
        ]);

        // Save MR
        $create = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone'         => $request->phone,
            'employee_code' => $request->employee_code,
            'territory'     => $request->territory,
            'city'          => $request->city,
            'state'         => $request->state,
            'joining_date'  => $request->joining_date,
            'status'        => $request->status,
            'user_type'          => 'MR',

        ]);

         //check user created
        if (!$create) {
            return redirect()->back()->with('error', 'Failed to add MR');
        } else {
            // Create MangerMR record
            MangerMR::create([
                'manager_id' => Auth::id(),
                'mr_id'      => $create->id,
            ]);
        }
        return redirect()->route('manager.mrs.index')->with('success', 'MR added successfully');
    }
}
