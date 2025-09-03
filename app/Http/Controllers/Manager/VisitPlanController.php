<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\VisitPlan;
use App\Models\VisitPlanInterest;
use App\Models\VisitPlanAssignement;
use App\Models\VisitPlanComment;
use App\Models\User;
use App\Notifications\VisitPlanNotification;
use Illuminate\Http\Request;

class VisitPlanController extends Controller
{
    //Function for show all visit plans   
    public function index() {
        //Get visit plans
        $visit_plans = VisitPlan::OrderBy('ID', 'DESC')->where('created_by', auth()->id())->with('comments')->paginate(10);
        return view('manager.visit_plans.index', compact('visit_plans'));
    }

    //Function for create visit plan
    public function create() {
        //Get doctors
        $doctors = Doctor::where('user_id', auth()->id())->get();
        //Get mrs
        $mrs = auth()->user()->mrs;
        return view('manager.visit_plans.create', compact('doctors','mrs'));
    }

    //Function to store visit plan
    public function store(Request $request) {
        //Validate inputs fields
        $request->validate([
            'plan_type' =>'required|string',
            'visit_category' =>'required|string',
            'title' =>'required|string',
            'description' =>'nullable|string',
            'start_date' =>'required|date',
            'end_date'  =>'required|date|after_or_equal:start_date',
            'location' =>'nullable|string',
            //'assigned_to' =>'nullable|exists:users,id',
            //'doctor_id' => 'nullable|exists:doctors,id',
            //'note' => 'nullable|string',
        ]);
        //Create visit plan
        $is_create_visit = VisitPlan::create([
            'plan_type' => $request->plan_type,
            'visit_category' => $request->visit_category,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'created_by' => auth()->id(),
            //'assigned_to' => $request->assigned_to,
            //'doctor_id' => $request->doctor_id,
            //'note' => $request->note,
            'status' => $request->status,
        ]);
        //Check if visit plan created or not
        if($is_create_visit) {
            //Send notification to interested mrs
            $mrs = auth()->user()->mrs->pluck('id')->toArray();
            foreach ($mrs as $mr) {
                $user = User::find($mr);
                $user->notify(new VisitPlanNotification($is_create_visit));
            }
            return redirect()->route('manager.visit-plans.index')->with('success', 'Visit Plan created successfully.');
        } else {
           return back()->with('error', 'Opps something went wrong!'); 
        }
    }

    //Function for show interested mrs for a visit plan
    public function showInterestedMRS() {
        //Get mrs
        $mrs = auth()->user()->mrs->pluck('id')->toArray();
        //Get interested mrs
        $intrested_mrs = VisitPlanInterest::whereIn('mr_id', $mrs)->with('mr','visitPlan')->paginate(10);
        return view('manager.visit_plans.interested_mrs', compact('intrested_mrs'));
    }

    //Function for approve or reject interested mr
    public function approveRejectInterest(Request $request, $id) {
        //Validate inputs fields
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);
        //Get visit plan interest
        $interest = VisitPlanInterest::findOrFail($id);
        if ($interest) {
            if ($request->action == 'approve') {
                $interest->visitPlan->update([
                    'assigned_to' => $interest->mr_id,
                    'status' => 'assigned',
                ]);
                //Delete other interests for the same visit plan
                VisitPlanInterest::where('visit_plan_id', $interest->visit_plan_id)
                    ->where('id', '!=', $interest->id)
                    ->delete();
                //Assign the visit plan to the mr
                VisitPlanAssignement::create([
                    'visit_plan_id' => $interest->visit_plan_id,
                    'mr_id' => $interest->mr_id,
                ]);
                //Delete the interest record
                $interest->delete();    
                return redirect()->back()->with('success', 'MR approved and visit plan assigned.');
            } elseif ($request->action == 'reject') {
                return redirect()->back()->with('success', 'MR interest rejected.');
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');

        }
    }

    //Function for add comment
    public function add_comment(Request $request) {
        //Validate input fields
        $request->validate([
            'visit_id' => 'required|exists:visit_plans,id',
            'comment' => 'required|string',
        ]);
        //Create visit plan comment
        VisitPlanComment::create([
            'visit_plan_id' => $request->visit_id,
            'comment' => $request->comment,
            'related_id' => auth()->id(),
            'role' => 'manager',
        ]);
        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    //Function for edit visit plan
    public function edit($id) {
        //Get visit plan
        $visit_detail = VisitPlan::find($id);
        return view('manager.visit_plans.edit-visit-plan', compact('visit_detail'));
    }

    //Function for update visit plan
    public function update(Request $request, $id) {
        //Validate inputs fields
        $request->validate([
            'plan_type' =>'required|string',
            'visit_category' =>'required|string',
            'title' =>'required|string',
            'description' =>'nullable|string',
            'start_date' =>'required|date',
            'end_date'  =>'required|date|after_or_equal:start_date',
            'location' =>'nullable|string',
            //'assigned_to' =>'nullable|exists:users,id',
            //'doctor_id' => 'nullable|exists:doctors,id',
            //'note' => 'nullable|string',
        ]);
        //Update visit plan
        $is_update_visit = VisitPlan::where('id', $id)->update([
            'plan_type' => $request->plan_type,
            'visit_category' => $request->visit_category,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'created_by' => auth()->id(),
            //'assigned_to' => $request->assigned_to,
            //'doctor_id' => $request->doctor_id,
            //'note' => $request->note,
            'status' => $request->status,
        ]);
        //Check if visit plan updated or not
        if($is_update_visit) {
            return redirect()->route('manager.visit-plans.index')->with('success', 'Visit Plan updated successfully.');
        } else {
           return back()->with('error', 'Opps something went wrong!'); 
        }
    }

    //Function for delete visit plan
    public function delete($id) {
        //Delete visit plan
        $is_delete_visit = VisitPlan::where('id', $id)->delete();
        //Check if visit plan deleted or not
        if($is_delete_visit) {
            return redirect()->route('manager.visit-plans.index')->with('success', 'Visit Plan deleted successfully.');
        } else {
           return back()->with('error', 'Opps something went wrong!'); 
        }
    }
}
