<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VisitPlan;
use App\Models\Doctor;
use App\Models\DoctorMrAssignement;


class VisitPlanController extends Controller
{
    public function index()
    {
        $visit_plans = VisitPlan::where('manager_id', auth()->id())->with('mr','doctor')->paginate(10);
        return view('manager.visit_plans.index', compact('visit_plans'));
    }

    public function create()
    {
        $doctors = Doctor::where('user_id', auth()->id())->get();
        $mrs = auth()->user()->mrs;
        return view('manager.visit_plans.create', compact('mrs','doctors'));
    }

    //function to store visit plan
    public function store(Request $request)
    {
        $request->validate([
            'mr_id' => 'required|exists:users,id',
            'visit_date' => 'required|date',
            'location' => 'required|string|max:255',
            'doctor_id' => 'required|exists:doctors,id',
            'note' => 'nullable|string',
            'status' => 'required|in:planned,cancelled,completed',
        ]); 
        $is_create = VisitPlan::create([
            'manager_id' => auth()->id(),
            'mr_id' => $request->mr_id,
            'visit_date' => $request->visit_date,
            'location' => $request->location,
            'doctor_id' => $request->doctor_id,
            'notes' => $request->note,
            'status' => $request->status,
        ]);
        //check if created
        if($is_create){
            DoctorMrAssignement::firstOrCreate([
                'doctor_id' => $request->doctor_id,
                'mr_id' => $request->mr_id,
            ]);
             return redirect()->route('manager.visit-plans.index')->with('success', 'Visit Plan created successfully.');
        } else {
            return back()->with('error', 'Something went wrong. Please try again.');
        }
       
    }
}
