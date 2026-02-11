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
    //Function for all visit plans
    public function index(Request $request) {
        //Get managers
        $manager = auth()->user()->managers->pluck('id')->toArray();
        //Query
        $query = VisitPlan::OrderBy('ID', 'DESC')->where('created_by', $manager)->whereDoesntHave('assignments');
        //Date Filter
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', $request->start_date);
        }
        //Get visit plans
        $visit_plans = $query->paginate(5);
        //Get interest plans
        foreach ($visit_plans as $plan) {
            $interest = VisitPlanInterest::where('visit_plan_id', $plan->id)
                ->where('mr_id', auth()->id())
                ->first();
            $plan->user_interest = $interest;
        }

        return view('mr.visit_plans.index', compact('visit_plans'));
    }

    //Function to express interest in a visit plan
    public function expressInterest($id) {
        //Get visit plan
        $visit_plan = VisitPlan::findOrFail($id);
        //Check if visit plan updated or not
        if($visit_plan) {
            VisitPlanInterest::firstOrCreate([
                'visit_plan_id' => $visit_plan->id,
                'mr_id' => auth()->id(),
            ]);
        } else {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
        return redirect()->back()->with('success', 'You have expressed interest in this visit plan.');
    }

    //Function for mr to view their interested visit plans
    public function myInterestedPlans(Request $request) {
        //Query
        $query = VisitPlanInterest::OrderBy('ID', 'DESC')->where('mr_id', auth()->id());
        //Date Filter
        if ($request->filled('start_date')) {
            $query->whereHas('visitPlan', function ($q) use ($request) {
                $q->whereDate('start_date', $request->start_date);
            });
        }
        //Interests visit plans
        $interests = $query->with('visitPlan')->paginate(5);

        return view('mr.visit_plans.intrestedplan', compact('interests'));
    }

    //Function for mr to view their assigned visit plans
    public function myAssignedPlans(Request $request) {
        //Query
        $query = VisitPlanAssignement::OrderBy('ID', 'DESC')->where('mr_id', auth()->id());
        //Date Filter
        if ($request->filled('start_date')) {
            $query->whereHas('visitPlan', function ($q) use ($request) {
                $q->whereDate('start_date', $request->start_date);
            });
        }
        //Assigments plans
        $assignments = $query->with('visitPlan')->paginate(5);
        
        return view('mr.visit_plans.assignedplan', compact('assignments'));
    }
}
