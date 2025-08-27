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
        $visit_plans = VisitPlan::where('created_by', auth()->id())->paginate(10);
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
            'plan_type' => 'required|string',
            'visit_category' => 'required|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'note' => 'nullable|string',
        ]);

        VisitPlan::create([
            'plan_type' => $request->plan_type,
            'visit_category' => $request->visit_category,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'created_by' => auth()->id(),
            'assigned_to' => $request->assigned_to,
            'doctor_id' => $request->doctor_id,
            'note' => $request->note,
            'status' => $request->assigned_to ? 'assigned' : 'open',
        ]);

        return redirect()->route('manager.visit-plans.index')->with('success', 'Visit Plan created successfully.');   
    }
}
