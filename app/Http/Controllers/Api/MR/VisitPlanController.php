<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use App\Models\VisitPlan;
use App\Models\VisitPlanInterest;
use App\Models\VisitPlanAssignement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VisitPlanController extends Controller
{
    //Function Ensure the authenticated MR context exists
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

    //Function for all plans
    public function index() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get mrs
        $managerIds = auth()->user()->managers->pluck('id')->toArray();
        //all plans
        $plans = VisitPlan::with('myInterest')->whereIn('created_by', $managerIds)
            ->whereDoesntHave('assignments')
            ->orderByDesc('id')
            ->paginate(10);

        //If no plans found
        if ($plans->total() === 0) {
            return response()->json([
                'status' => 200,
                'message' => 'No visit plans found.',
                'data' => null,
            ], 200);
        }

        $plans->map(function ($plan) {
            $plan->is_interested = $plan->myInterest ? 'yes' : 'no';
            return $plan;
        });
        //If plans found
        return response()->json([
            'status' => 200,
            'message' => 'Visit plans fetched successfully.',
            'data' => $plans,
        ], 200);
    }
    
    //Function for express interest in a plan
    public function expressInterest($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get plan
        $plan = VisitPlan::find($id);
        //If plan not fond
        if (!$plan) {
            return response()->json([
                'status' => 404,
                'message' => 'Visit plan not found.',
                'data' => null,
            ], 404);
        }
        //Check if already expressed interest
        $exists = VisitPlanInterest::where('visit_plan_id', $id)
            ->where('mr_id', auth()->id())
            ->exists(); 

        if ($exists) {
            return response()->json([
                'status' => 404, 
                'message' => 'You have already expressed interest in this visit plan.',
                'data' => null,
            ], 404);
        }
        //Insert new interest
        VisitPlanInterest::create([
            'visit_plan_id' => $id,
            'mr_id' => auth()->id(),
        ]);
        //response
        return response()->json([
            'status' => 200,
            'message' => 'Interest expressed successfully.',
            'data' => null,
        ], 200);
    }

    //Function for interested plan
    public function myInterestedPlans() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //interests plan
        $interests = VisitPlanInterest::where('mr_id', auth()->id())
            ->with('visitPlan')
            ->orderByDesc('id')
            ->paginate(10);

        //If no interested plans
        if ($interests->total() === 0) {
            return response()->json([
                'status' => 200,
                'message' => 'You have not shown interest in any visit plan yet.',
                'data' => null,
            ], 200);
        }

        //If plans are available
        return response()->json([
            'status' => 200,
            'message' => 'Interested visit plans fetched successfully.',
            'data' => $interests,
        ], 200);
    }

    //Function for assigned plans
    public function myAssignedPlans() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //assigned
        $assignments = VisitPlanAssignement::where('mr_id', auth()->id())
            ->with('visitPlan')
            ->orderByDesc('id')
            ->paginate(10);

        //If no assigned plans
        if ($assignments->total() === 0) {
            return response()->json([
                'status' => 200,
                'message' => 'No visit plans assigned yet.',
                'data' => null,
            ], 200);
        }

        //If plans exist
        return response()->json([
            'status' => 200,
            'message' => 'Assigned visit plans fetched successfully.',
            'data' => $assignments,
        ], 200);
    }
}
