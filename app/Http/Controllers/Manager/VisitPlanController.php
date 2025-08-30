<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\VisitPlan;
use App\Models\VisitPlanInterest;
use App\Models\VisitPlanAssignement;
use App\Models\VisitPlanComment;
use Illuminate\Http\Request;

class VisitPlanController extends Controller
{
    public function index()
    {
        $visit_plans = VisitPlan::where('created_by', auth()->id())->with('comments')->paginate(10);
        //echo "<pre>";print_r($visit_plans->toArray());exit;
        return view('manager.visit_plans.index', compact('visit_plans'));
    }

    public function create()
    {
        $doctors = Doctor::where('user_id', auth()->id())->get();
        $mrs     = auth()->user()->mrs;
        return view('manager.visit_plans.create', compact('mrs', 'doctors'));
    }

    //function to store visit plan
    public function store(Request $request)
    {
        $request->validate([
            'plan_type'      => 'required|string',
            'visit_category' => 'required|string',
            'title'          => 'required|string',
            'description'    => 'nullable|string',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'location'       => 'nullable|string',
            'assigned_to'    => 'nullable|exists:users,id',
            'doctor_id'      => 'nullable|exists:doctors,id',
            'note'           => 'nullable|string',
        ]);

        VisitPlan::create([
            'plan_type'      => $request->plan_type,
            'visit_category' => $request->visit_category,
            'title'          => $request->title,
            'description'    => $request->description,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'location'       => $request->location,
            'created_by'     => auth()->id(),
            'assigned_to'    => $request->assigned_to,
            'doctor_id'      => $request->doctor_id,
            'note'           => $request->note,
            'status'         => $request->assigned_to ? 'assigned' : 'open',
        ]);

        return redirect()->route('manager.visit-plans.index')->with('success', 'Visit Plan created successfully.');
    }

    //function for show interested mrs for a visit plan
    public function showInterestedMRS()
    {
        $mrs           = auth()->user()->mrs->pluck('id')->toArray();
        $intrested_mrs = VisitPlanInterest::whereIn('mr_id', $mrs)->with('mr','visitPlan')->paginate(10);
        return view('manager.visit_plans.interested_mrs', compact('intrested_mrs'));
    }

    //function for approve or reject interested mr
    public function approveRejectInterest(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);
        $interest = VisitPlanInterest::findOrFail($id);
        if ($interest) {
            if ($request->action == 'approve') {
                $interest->visitPlan->update([
                    'assigned_to' => $interest->mr_id,
                    'status'      => 'assigned',
                ]);
                //delete other interests for the same visit plan
                VisitPlanInterest::where('visit_plan_id', $interest->visit_plan_id)
                    ->where('id', '!=', $interest->id)
                    ->delete();
                //assign the visit plan to the mr
                VisitPlanAssignement::create([
                    'visit_plan_id' => $interest->visit_plan_id,
                    'mr_id'         => $interest->mr_id,
                ]);
                //delete the interest record
                $interest->delete();    
                return redirect()->back()->with('success', 'MR approved and visit plan assigned.');
            } elseif ($request->action == 'reject') {
                return redirect()->back()->with('success', 'MR interest rejected.');
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');

        }
    }

    //function for add comment
    public function add_comment(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visit_plans,id',
            'comment' => 'required|string',
        ]);
        
        VisitPlanComment::create([
            'visit_plan_id' => $request->visit_id,
            'comment' => $request->comment,
            'related_id' => auth()->id(),
            'role' => 'manager',
        ]);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }
}
