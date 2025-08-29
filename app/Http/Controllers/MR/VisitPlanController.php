<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VisitPlan;
use App\Models\Doctor;
use App\Models\DoctorMrAssignement;
use App\Models\VisitPlanInterest;
use App\Models\VisitPlanAssignement;


class VisitPlanController extends Controller
{
    public function index()
    {
        $manager = auth()->user()->managers->pluck('id')->toArray();
        $visit_plans = VisitPlan::where('created_by', $manager)
        ->whereDoesntHave('assignments')
         ->paginate(10);

        foreach ($visit_plans as $plan) {
            $interest = VisitPlanInterest::where('visit_plan_id', $plan->id)
                ->where('mr_id', auth()->id())
                ->first();
            $plan->user_interest = $interest;
        }
        return view('mr.visit_plans.index', compact('visit_plans'));
    }

    //function to express interest in a visit plan
    public function expressInterest($id)
    {
        $visit_plan = VisitPlan::findOrFail($id);
        if($visit_plan){
            VisitPlanInterest::firstOrCreate([
                'visit_plan_id' => $visit_plan->id,
                'mr_id' => auth()->id(),
            ]);
        } else {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
        return redirect()->back()->with('success', 'You have expressed interest in this visit plan.');
    }

    //function for mr to view their interested visit plans
    public function myInterestedPlans()
    {
        $interests = VisitPlanInterest::where('mr_id', auth()->id())->with('visitPlan')->paginate();
        return view('mr.visit_plans.intrestedplan', compact('interests'));
    }

    //function for mr to view their assigned visit plans
    public function myAssignedPlans()
    {
        $assignments = VisitPlanAssignement::where('mr_id', auth()->id())->with('visitPlan')->paginate();
        return view('mr.visit_plans.assignedplan', compact('assignments'));
    }
}
