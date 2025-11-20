<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Doctor;
use App\Models\VisitPlan;
use App\Models\VisitPlanInterest;
use App\Models\VisitPlanAssignement;
use App\Models\VisitPlanComment;
use App\Models\User;
use App\Notifications\VisitPlanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VisitPlanController extends Controller
{
    //Function for ensure user is authenticated
    private function ensureAuthenticated(): ?JsonResponse {
        //Check if auth login or not
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 401);
        }

        return null;
    }

    //Function for all visit plans
    public function index(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //query
        $query = VisitPlan::orderBy('id', 'DESC')
            ->where('created_by', auth()->id())
            ->with('comments');
        //filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        //all plans
        $plans = $query->paginate(10);
        //response
        return response()->json([
            'status' => true,
            'message' => 'Visit plans fetched successfully.',
            'data' => $plans
        ], 200);
    }

    //Function for create visit plan
    public function store(Request $request) {
        //validate input fields
        $validator = Validator::make($request->all(), [
            'plan_type' =>'required|string',
            'visit_category' =>'required|string',
            'title' =>'required|string',
            'description' =>'nullable|string',
            'start_date' =>'required|date',
            'end_date'  =>'required|date|after_or_equal:start_date',
            'location' =>'nullable|string',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //create visit
        $plan = VisitPlan::create([
            'plan_type' => $request->plan_type,
            'visit_category' => $request->visit_category,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'created_by' => auth()->id(),
            'status' => 'Open',
        ]);
        //mrs
        if ($plan) {
            $mrs = auth()->user()->mrs->pluck('id')->toArray();
            //get mrs
            foreach ($mrs as $mr) {
                $user = User::find($mr);
                if ($user) {
                    //send notification
                    $user->notify(new VisitPlanNotification($plan));
                }
            }
            //response
            return response()->json([
                'status' => true,
                'message' => 'Visit plan created successfully.',
                'data' => $plan
            ], 200);
        }
        //response
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.'
        ], 500);
    }

    //Function for interested mrs
    public function interestedMRS() {
        //get auth login mrs
        $mrs = auth()->user()->mrs->pluck('id')->toArray();
        //all interests plan
        $interests = VisitPlanInterest::whereIn('mr_id', $mrs)
            ->with('mr','visitPlan')
            ->paginate(10);
        //response
        return response()->json([
            'status' => true,
            'message' => 'Interested MRs fetched successfully.',
            'data' => $interests
        ], 200);
    }

    //Function for mr interest approvel
    public function approveInterest($id) {
        //get interest detail
        $interest = VisitPlanInterest::find($id);
        //check if interest found or not
        if (!$interest) {
            return response()->json([
                'status' => false,
                'message' => 'Interest not found.'
            ], 404);
        }
        //Approve status
        $interest->visitPlan->update([
            'assigned_to' => $interest->mr_id,
            'status' => 'assigned',
        ]);
        //delete interest plan
        VisitPlanInterest::where('visit_plan_id', $interest->visit_plan_id)
            ->where('id', '!=', $interest->id)
            ->delete();
        //create assgin plan
        VisitPlanAssignement::create([
            'visit_plan_id' => $interest->visit_plan_id,
            'mr_id' => $interest->mr_id,
        ]);
        //delete current interest after approval
        $interest->delete();
        //response
        return response()->json([
            'status' => true,
            'message' => 'MR approved and visit plan assigned.'
        ], 200);
    }

    //Function for create comment
    public function addComment(Request $request) {
        //validate input fields
        $validator = Validator::make($request->all(), [
            'visit_id' => 'required|exists:visit_plans,id',
            'comment' => 'required|string',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //create comments
        $comment = VisitPlanComment::create([
            'visit_plan_id' => $request->visit_id,
            'comment' => $request->comment,
            'related_id' => auth()->id(),
            'role' => 'manager',
        ]);
        //response
        return response()->json([
            'status' => true,
            'message' => 'Comment added successfully.',
            'data' => $comment
        ], 200);
    }

    //Function for update visit plan
    public function update(Request $request, $id) {
        //validate input fields
        $validator = Validator::make($request->all(), [
            'plan_type' =>'required|string',
            'visit_category' =>'required|string',
            'title' =>'required|string',
            'description' =>'nullable|string',
            'start_date' =>'required|date',
            'end_date'  =>'required|date|after_or_equal:start_date',
            'location' =>'nullable|string',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //get visit plan detail
        $plan = VisitPlan::find($id);
        //check if plan found or not
        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Visit plan not found.'
            ], 404);
        }
        //update plan
        $plan->update([
            'plan_type' => $request->plan_type,
            'visit_category' => $request->visit_category,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'created_by' => auth()->id(),
        ]);
        //response
        return response()->json([
            'status' => true,
            'message' => 'Visit plan updated successfully.',
            'data' => $plan
        ], 200);
    }

    //Function for delete visit plan
    public function destroy($id) {
        //get visit plan
        $plan = VisitPlan::find($id);
        //check if plan found or not
        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Visit plan not found.'
            ], 404);
        }
        //plan delete
        $plan->delete();
        //respnse
        return response()->json([
            'status' => true,
            'message' => 'Visit plan deleted successfully.'
        ], 200);
    }

    //Function for update visit plan status
    public function updateStatus(Request $request, $id) {
        //get plan detail
        $plan = VisitPlan::find($id);
        //check if plan found or not
        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Visit plan not found.'
            ], 404);
        }
        //get status
        $plan->status = $request->status;
        //save
        $plan->save();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Visit plan status updated successfully.'
        ], 200);
    }
}
