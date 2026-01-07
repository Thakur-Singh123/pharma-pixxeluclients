<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Models\Doctor;
use App\Models\DoctorMrAssignement;
use App\Models\Task;
use App\Models\TaskTourPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TourStatusNotification;
use Illuminate\Support\Facades\Validator;
use App\Services\FirebaseService;

class TourPlanController extends Controller
{
    protected $fcmService;

    public function __construct(FirebaseService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    //Function for Ensure authenticated
    private function ensureAuthenticated() {
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => "Unauthorized access. Please login first.",
                'data' => null,
            ], 401);
        }
        return null;
    }

    //Function for all tour plans
    public function index() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //all tasktourplan
        $plans = TaskTourPlan::with('doctor','mr')
            ->orderBy('id', 'DESC')
            ->whereDate('start_date', Carbon::now()->addDays(1))
            ->where('manager_id', auth()->id())
            ->paginate(10);
        //response
        return response()->json([
            'status' => true,
            'message' => 'Tour plans fetched successfully.',
            'data' => $plans
        ], 200);
    }

    //Function for update tour plan
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //validate input fields
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        //Validation error
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ], 400);
        }
        //Check if tour plan exists or not
        $tour_plan = TaskTourPlan::find($id);
        //check tour plan found or not
        if (!$tour_plan) {
            return response()->json([
                'status' => false,
                'message' => 'Tour plan not found!'
            ], 404);
        }
        //Update tour plan
        $tour_plan->update([
            'doctor_id' => $request->doctor_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'pin_code' => $request->pin_code,
        ]);
        //response
        return response()->json([
            'status' => true,
            'message' => 'Tour plan updated successfully.'
        ], 200);
    }

    //Function for approve tour plan
    public function approve_tour_plan(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Check if tour plan exists or not
        $tour_plan = TaskTourPlan::find($id);
        //Check tour plan found or not
        if (!$tour_plan) {
            return response()->json([
                'status' => false,
                'message' => 'Tour plan not found!'
            ], 404);
        }
        //Check if already status approved
        if ($tour_plan->approval_status === 'Approved') {
            return response()->json([
                'status' => false,
                'message' => 'This tour plan is already approved. Please reject first, then approve again.'
            ], 400);
        }
        //Get tast id
        $task = Task::findOrFail($tour_plan->task_id);
        //Update data
        $task->update([
            'title' => $tour_plan->title,
            'description' => $tour_plan->description,
            'location' => $tour_plan->location,
            'pin_code' => $tour_plan->pin_code,
            'doctor_id' => $tour_plan->doctor_id,
            'start_date' => $tour_plan->start_date,
            'end_date' => $tour_plan->end_date,
            'is_approval' => 'Approved',
        ]);
        //update status
        $tour_plan->update([
            'approval_status' => 'Approved',
        ]);
        //get mr id
        $fcmResponses = [];
        $mr = User::find($tour_plan->mr_id);
        if ($mr) {
            $mr->notify(new TourStatusNotification($tour_plan));
            //fcm notification
            $fcmResponses = $this->fcmService->sendToUser($mr, [
                'id' => $tour_plan->id,
                'title' => $tour_plan->title,
                'message' => 'Your tour plan has been approved.',
                'type' => 'tour_plan',
                'is_read' => 'false',
                'created_at' => now()->toDateTimeString(),
            ]);
        }
        //response
        return response()->json([
            'status' => true,
            'message' => 'Tour plan approved successfully.',
            'fcm_response' => $fcmResponses,
        ], 200);
    }

    //Function for reject tour plan (API)
    public function reject_tour_plan(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Check if tour plan exists or not
        $tour_plan = TaskTourPlan::find($id);
        //Check tour plan found or not
        if (!$tour_plan) {
            return response()->json([
                'status' => false,
                'message' => 'Tour plan not found!'
            ], 404);
        }
        //Check if status already rejected
        if ($tour_plan->approval_status === 'Rejected') {
            return response()->json([
                'status' => false,
                'message' => 'This tour plan is already rejected. Please approve first, then reject again.'
            ], 400);
        }
        //get task id
        $task = Task::findOrFail($tour_plan->task_id);
        //update tour plan status
        $tour_plan->update([
            'approval_status' => 'Rejected',
        ]);
        //update task status
        $task->update([
            'is_approval' => 'Rejected',
        ]);
        //get mr id
        $fcmResponses = [];
        $mr = User::find($tour_plan->mr_id);
        if ($mr) {
            $mr->notify(new TourStatusNotification($tour_plan));
            //fcm notification
            $fcmResponses = $this->fcmService->sendToUser($mr, [
                'id' => $tour_plan->id,
                'title' => $tour_plan->title,
                'message' => 'Your tour plan has been rejected.',
                'type' => 'tour_plan',
                'is_read' => 'false',
                'created_at' => now()->toDateTimeString(),
            ]);
        }
        //response
        return response()->json([
            'status' => true,
            'message' => 'Tour plan rejected successfully.',
            'fcm_response' => $fcmResponses,
        ], 200);
    }

    //Function for tour plan craete
    public function store(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Create tour plan
        $validator = Validator::make($request->all(), [
            'mr_id'      => 'required|exists:users,id',
            'doctor_id'  => 'required|exists:doctors,id',
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'location'   => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'pin_code'   => 'nullable|string|max:20',
        ]);
        //Validate input fields
        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'message' => $validator->errors()->first(),
                'data'    => null,
            ], 400);
        }
        //Create task
        $task = Task::create([
            'mr_id'       => $request->mr_id,
            'manager_id'  => Auth::id(),
            'doctor_id'   => $request->doctor_id,
            'title'       => $request->title,
            'description' => $request->description,
            'location'    => $request->location,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'pin_code'    => $request->pin_code,
            'created_by'  => 'manager',
            'status'      => 'pending',
            'is_active'   => 1,
        ]);

        if ($request->filled('doctor_id')) {
            DoctorMrAssignement::firstOrCreate([
                'doctor_id' => $request->doctor_id,
                'mr_id'     => $request->mr_id,
            ]);
        }

        $fcmResponses = [];
        $user = User::find($request->mr_id);
        if ($user) {
            $user->notify(new TaskAssignedNotification($task));

            //fcm notification
           $fcmResponses = $this->fcmService->sendToUser($user, [
                'id'         => $task->id,
                'title'      => $task->title, 
                'message'    => 'You have been assigned a new task: ' . $task->title,
                'type'       => 'task',
                'is_read'    => 'false',
                'created_at'=> now()->toDateTimeString(),
            ]);

        }

        $task->load(['mr', 'doctor']);

        return response()->json([
            'status'  => 200,
            'message' => 'Tour plan created successfully.',
            'data'    => $task,
            'fcm_responses' => $fcmResponses
        ], 200);
    }
}
